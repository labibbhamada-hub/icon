<?php

namespace App\Http\Controllers\Admin;

use App\Exports\CertificatesExport;
use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Participant;
use App\Models\Submission;
use App\Services\CertificateEligibilityService;
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

    public function store(
        Request $request,
        CertificateEligibilityService $certificateEligibilityService
    ) {
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

        /*
        |--------------------------------------------------------------------------
        | Certificate setting
        |--------------------------------------------------------------------------
        */

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

        /*
        |--------------------------------------------------------------------------
        | Submission
        |--------------------------------------------------------------------------
        |
        | A submission may only be linked to a presenter certificate.
        |
        */

        if (
            $validated['type'] !== 'presenter'
            && !empty($validated['submission_id'])
        ) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'A submission can only be linked to a presenter certificate.'
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
        | Certificate eligibility
        |--------------------------------------------------------------------------
        |
        | Check the business rules for the selected certificate type:
        |
        | participant -> confirmed registration + verified attendance
        | presenter   -> published submission + verified attendance
        | speaker     -> confirmed speaker registration + verified attendance
        | committee   -> confirmed committee registration + verified attendance
        | reviewer    -> reviewer assignment + all assigned reviews completed
        |
        */

        $eligibility = $certificateEligibilityService->evaluate(
            $participant,
            $validated['type'],
            $submission
        );

        if (!$eligibility['eligible']) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    implode(' ', $eligibility['reasons'])
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

        try {
            $this->generatePdf(
                $certificate
            );
        } catch (\Throwable $e) {
            /*
            |--------------------------------------------------------------------------
            | PDF generation failed
            |--------------------------------------------------------------------------
            |
            | generatePdf() already cleans up the newly created PDF when its
            | own file/database operation fails. Remove the certificate record
            | as well so an incomplete certificate is not left in the database.
            |
            */

            try {
                $certificate->delete();
            } catch (\Throwable $deleteException) {
                report($deleteException);
            }

            throw $e;
        }

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
        $filePath =
            $certificate->file_path;

        /*
        |--------------------------------------------------------------------------
        | Delete database record first
        |--------------------------------------------------------------------------
        |
        | If the database delete fails, the PDF remains available.
        |
        */

        $certificate->delete();

        /*
        |--------------------------------------------------------------------------
        | Delete PDF only after database delete succeeds
        |--------------------------------------------------------------------------
        */

        if ($filePath) {
            Storage::disk('public')
                ->delete($filePath);
        }

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

        /*
        |--------------------------------------------------------------------------
        | Create a unique new PDF path
        |--------------------------------------------------------------------------
        |
        | Do not overwrite the existing PDF. The old PDF remains available
        | until the database successfully references the new PDF.
        |
        */

        $fileName =
            $certificate->certificate_number .
            '-' .
            Str::uuid() .
            '.pdf';

        $filePath =
            'certificates/' .
            $fileName;

        $oldFile =
            $certificate->file_path;

        try {
            /*
            |--------------------------------------------------------------------------
            | Write new PDF
            |--------------------------------------------------------------------------
            */

            Storage::disk('public')
                ->put(
                    $filePath,
                    $pdf->output()
                );

            /*
            |--------------------------------------------------------------------------
            | Update database reference
            |--------------------------------------------------------------------------
            */

            $certificate->update([
                'file_path' =>
                $filePath,
            ]);
        } catch (\Throwable $e) {
            /*
            |--------------------------------------------------------------------------
            | Cleanup newly created PDF
            |--------------------------------------------------------------------------
            |
            | The old PDF is intentionally not touched here.
            |
            */

            Storage::disk('public')
                ->delete($filePath);

            throw $e;
        }

        /*
        |--------------------------------------------------------------------------
        | Remove old PDF only after the database update succeeds
        |--------------------------------------------------------------------------
        */

        if (
            $oldFile
            && $oldFile !== $filePath
        ) {
            try {
                Storage::disk('public')
                    ->delete($oldFile);
            } catch (\Throwable $e) {
                /*
                |--------------------------------------------------------------------------
                | The database already points to the new valid PDF.
                | Failure to clean the old PDF should not invalidate it.
                |--------------------------------------------------------------------------
                */

                report($e);
            }
        }

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
