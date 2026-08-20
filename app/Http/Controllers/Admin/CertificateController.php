<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Participant;
use App\Models\Submission;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
            'conference.settings',
            'conference.configuration',
            'submissions',
        ])
            ->where(
                'registration_status',
                'confirmed'
            )
            ->whereHas(
                'conference.settings',
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
            'conference.settings',
            'conference.configuration',
        ])
            ->findOrFail(
                $validated['participant_id']
            );

        if (
            !$participant->conference?->settings?->certificate_enabled
            || $participant->conference?->settings?->maintenance_mode
        ) {
            return back()
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
                    'status',
                    'published'
                )
                ->firstOrFail();
        }

        $existingCertificate = Certificate::where(
            'participant_id',
            $participant->id
        )
            ->where(
                'conference_id',
                $participant->conference_id
            )
            ->where(
                'type',
                $validated['type']
            )
            ->first();

        if ($existingCertificate) {
            return redirect()
                ->route(
                    'admin.certificates.show',
                    $existingCertificate
                )
                ->with(
                    'error',
                    'A certificate of this type has already been generated for this participant.'
                );
        }

        $certificate = Certificate::create([
            'participant_id' => $participant->id,
            'conference_id' => $participant->conference_id,
            'submission_id' => $submission?->id,
            'certificate_number' => $this->generateCertificateNumber($participant->conference),
            'type' => $validated['type'],
            'issued_at' => now(),
        ]);

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
            'A4',
            'landscape'
        );

        $filePath = $this->generatePdf($certificate);

        Storage::disk('public')->put(
            $filePath,
            $pdf->output()
        );

        $certificate->update([
            'file_path' => $filePath,
        ]);

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

    public function show(Certificate $certificate)
    {
        $certificate->load([
            'participant.conference',
            'submission',
        ]);

        return view(
            'admin.certificates.show',
            compact('certificate')
        );
    }

    public function download(Certificate $certificate)
    {
        abort_unless(
            $certificate->file_path,
            404
        );

        abort_unless(
            Storage::disk('public')
                ->exists($certificate->file_path),
            404
        );

        return Storage::disk('public')
            ->download(
                $certificate->file_path,
                $certificate->certificate_number . '.pdf'
            );
    }

    public function destroy(Certificate $certificate)
    {
        if ($certificate->file_path) {

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

    public function regenerate(Certificate $certificate)
    {
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
            'A4',
            'landscape'
        );

        $filePath = $this->generatePdf($certificate);

        if ($certificate->file_path) {
            Storage::disk('public')->delete(
                $certificate->file_path
            );
        }

        Storage::disk('public')->put(
            $filePath,
            $pdf->output()
        );

        $certificate->update([
            'file_path' => $filePath,
        ]);

        return redirect()
            ->route('admin.certificates.show', $certificate)
            ->with(
                'success',
                'Certificate PDF regenerated successfully.'
            );
    }

    private function generateCertificateNumber($conference): string
    {
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

    private function generatePdf(Certificate $certificate): string
    {
        $certificate->load([
            'participant',
            'conference.configuration',
            'submission',
        ]);

        $pdf = Pdf::loadView(
            'certificates.pdf',
            compact('certificate')
        );

        // Paksa A4 Landscape
        $pdf->setPaper('a4', 'landscape');

        $fileName = $certificate->certificate_number . '.pdf';

        $filePath = 'certificates/' . $fileName;

        // Hapus file lama
        if ($certificate->file_path) {
            Storage::disk('public')->delete(
                $certificate->file_path
            );
        }

        Storage::disk('public')->put(
            $filePath,
            $pdf->output()
        );

        return $filePath;
    }
}
