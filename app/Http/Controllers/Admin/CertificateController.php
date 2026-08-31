<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Participant;
use App\Models\Submission;
use App\Exports\CertificatesExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class CertificateController extends Controller
{
    public function index()
    {
        $certificates = Certificate::with([
            'participant',
            'conference',
            'submission',
        ])
            ->latest()
            ->paginate(15);

        return view(
            'admin.certificates.index',
            compact('certificates')
        );
    }

    public function create()
    {
        $participants = Participant::with([
            'conference.setting',
            'conference.configuration',
            'submissions',
        ])
            ->where(
                'registration_status',
                'confirmed'
            )
            ->whereHas(
                'conference.setting',
                function ($query) {
                    $query
                        ->where(
                            'certificate_enabled',
                            true
                        )
                        ->where(
                            'maintenance_mode',
                            false
                        );
                }
            )
            ->orderBy('full_name')
            ->get();

        return view(
            'admin.certificates.create',
            compact('participants')
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'participant_id' => [
                'required',
                'exists:participants,id',
            ],

            'type' => [
                'required',
                'in:participant,presenter,speaker,committee,reviewer',
            ],

            'submission_id' => [
                'nullable',
                'exists:submissions,id',
            ],
        ]);

        $participant = Participant::with([
            'conference.setting',
            'conference.configuration',
        ])
            ->findOrFail(
                $validated['participant_id']
            );

        if (
            !$participant->conference?->setting?->certificate_enabled
            || $participant->conference?->setting?->maintenance_mode
        ) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Certificate generation is currently disabled for this conference.'
                );
        }

        $submission = null;

        if (!empty($validated['submission_id'])) {
            $submission = Submission::where(
                'id',
                $validated['submission_id']
            )
                ->where(
                    'participant_id',
                    $participant->id
                )
                ->where(
                    'conference_id',
                    $participant->conference_id
                )
                ->where(
                    'status',
                    'published'
                )
                ->firstOrFail();
        }

        /*
        |--------------------------------------------------------------------------
        | Presenter certificate
        |--------------------------------------------------------------------------
        |
        | Presenter certificates are tied to a published submission.
        | Therefore duplicate detection must use submission_id.
        |
        */

        if (
            $validated['type'] === 'presenter'
            && !$submission
        ) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'A presenter certificate requires a published submission.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Duplicate protection
        |--------------------------------------------------------------------------
        */

        $existingCertificate = null;

        if ($submission) {
            $existingCertificate = Certificate::where(
                'participant_id',
                $participant->id
            )
                ->where(
                    'conference_id',
                    $participant->conference_id
                )
                ->where(
                    'submission_id',
                    $submission->id
                )
                ->where(
                    'type',
                    $validated['type']
                )
                ->first();
        } else {
            $existingCertificate = Certificate::where(
                'participant_id',
                $participant->id
            )
                ->where(
                    'conference_id',
                    $participant->conference_id
                )
                ->whereNull(
                    'submission_id'
                )
                ->where(
                    'type',
                    $validated['type']
                )
                ->first();
        }

        if ($existingCertificate) {
            return redirect()
                ->route(
                    'admin.certificates.show',
                    $existingCertificate
                )
                ->with(
                    'error',
                    'This certificate has already been generated.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Create certificate
        |--------------------------------------------------------------------------
        */

        $certificate = DB::transaction(
            function () use (
                $participant,
                $submission,
                $validated
            ) {
                return Certificate::create([
                    'participant_id' =>
                    $participant->id,

                    'conference_id' =>
                    $participant->conference_id,

                    'submission_id' =>
                    $submission?->id,

                    'certificate_number' =>
                    $this->generateCertificateNumber(
                        $participant->conference
                    ),

                    'type' =>
                    $validated['type'],

                    'issued_at' =>
                    now(),
                ]);
            }
        );

        /*
        |--------------------------------------------------------------------------
        | Generate PDF
        |--------------------------------------------------------------------------
        */

        $certificate->load([
            'participant',
            'conference.configuration',
            'submission',
        ]);

        $this->generatePdf(
            $certificate
        );

        return redirect()
            ->route(
                'admin.certificates.show',
                $certificate
            )
            ->with(
                'success',
                'Certificate generated successfully.'
            );
    }

    public function show(
        Certificate $certificate
    ) {
        $certificate->load([
            'participant.conference',
            'submission',
        ]);

        return view(
            'admin.certificates.show',
            compact('certificate')
        );
    }

    public function download(
        Certificate $certificate
    ) {
        abort_unless(
            $certificate->file_path,
            404
        );

        abort_unless(
            Storage::disk('public')->exists(
                $certificate->file_path
            ),
            404
        );

        return Storage::disk('public')
            ->download(
                $certificate->file_path,
                $certificate->certificate_number . '.pdf'
            );
    }

    public function destroy(
        Certificate $certificate
    ) {
        if (
            $certificate->file_path
        ) {
            Storage::disk('public')
                ->delete(
                    $certificate->file_path
                );
        }

        $certificate->delete();

        return redirect()
            ->route(
                'admin.certificates.index'
            )
            ->with(
                'success',
                'Certificate deleted successfully.'
            );
    }

    public function regenerate(
        Certificate $certificate
    ) {
        $certificate->load([
            'participant',
            'conference.configuration',
            'submission',
        ]);

        $this->generatePdf(
            $certificate
        );

        return redirect()
            ->route(
                'admin.certificates.show',
                $certificate
            )
            ->with(
                'success',
                'Certificate PDF regenerated successfully.'
            );
    }

    private function generateCertificateNumber(
        $conference
    ): string {
        do {
            $code =
                'CERT-' .
                strtoupper(
                    $conference->short_name
                ) .
                '-' .
                $conference->year .
                '-' .
                strtoupper(
                    Str::random(6)
                );
        } while (
            Certificate::where(
                'certificate_number',
                $code
            )->exists()
        );

        return $code;
    }

    private function generatePdf(
        Certificate $certificate
    ): string {
        $certificate->load([
            'participant',
            'conference.configuration',
            'submission',
        ]);

        $pdf = Pdf::loadView(
            'certificates.pdf',
            compact('certificate')
        );

        $pdf->setPaper(
            'a4',
            'landscape'
        );

        $fileName =
            $certificate->certificate_number .
            '.pdf';

        $filePath =
            'certificates/' .
            $fileName;

        /*
        |--------------------------------------------------------------------------
        | Delete old PDF if regenerating
        |--------------------------------------------------------------------------
        */

        if (
            $certificate->file_path
        ) {
            Storage::disk('public')
                ->delete(
                    $certificate->file_path
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Save PDF
        |--------------------------------------------------------------------------
        */

        Storage::disk('public')
            ->put(
                $filePath,
                $pdf->output()
            );

        $certificate->update([
            'file_path' =>
            $filePath,
        ]);

        return $filePath;
    }

    public function export(
        Request $request
    ) {
        $conferenceId =
            $request->integer(
                'conference_id'
            );

        $suffix =
            $conferenceId
            ? '-conference-' . $conferenceId
            : '-all';

        return Excel::download(
            new CertificatesExport(
                $conferenceId
            ),
            'certificates' .
                $suffix .
                '-' .
                now()->format('Y-m-d') .
                '.xlsx'
        );
    }
}
