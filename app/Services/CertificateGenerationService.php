<?php

namespace App\Services;

use App\Models\Certificate;
use App\Models\Submission;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CertificateGenerationService
{
    /**
     * Generate or return the presenter certificate
     * for a published submission.
     */
    public function createForSubmission(
        Submission $submission
    ): Certificate {
        $submission->load([
            'participant',
            'participant.conference',
            'participant.conference.setting',
            'participant.conference.configuration',
            'conference',
            'conference.setting',
            'conference.configuration',
            'authors',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Basic validation
        |--------------------------------------------------------------------------
        */

        if (
            $submission->status !== 'published'
        ) {
            throw new \RuntimeException(
                'A certificate can only be generated for a published submission.'
            );
        }

        if (
            !$submission->participant
        ) {
            throw new \RuntimeException(
                'The submission does not have a valid participant.'
            );
        }

        $conference =
            $submission->conference
            ?? $submission->participant->conference;

        if (!$conference) {
            throw new \RuntimeException(
                'The submission does not belong to a valid conference.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Certificate setting
        |--------------------------------------------------------------------------
        */

        if (
            $conference->setting
            && !$conference->setting->certificate_enabled
        ) {
            throw new \RuntimeException(
                'Certificate generation is disabled for this conference.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Existing certificate
        |--------------------------------------------------------------------------
        |
        | One presenter certificate per submission.
        |
        */

        $existingCertificate =
            Certificate::where(
                'participant_id',
                $submission->participant_id
            )
            ->where(
                'conference_id',
                $conference->id
            )
            ->where(
                'submission_id',
                $submission->id
            )
            ->where(
                'type',
                'presenter'
            )
            ->first();

        if ($existingCertificate) {
            /*
            |----------------------------------------------------------------------
            | Make sure PDF exists.
            |----------------------------------------------------------------------
            */

            if (
                $existingCertificate->file_path
                && Storage::disk('public')->exists(
                    $existingCertificate->file_path
                )
            ) {
                return $existingCertificate;
            }

            /*
            |----------------------------------------------------------------------
            | Certificate exists but PDF is missing.
            |----------------------------------------------------------------------
            */

            $this->generatePdf(
                $existingCertificate
            );

            return $existingCertificate->fresh();
        }

        /*
        |--------------------------------------------------------------------------
        | Create certificate
        |--------------------------------------------------------------------------
        */

        $certificate =
            DB::transaction(
                function () use (
                    $submission,
                    $conference
                ) {
                    return Certificate::create([
                        'participant_id' =>
                        $submission->participant_id,

                        'conference_id' =>
                        $conference->id,

                        'submission_id' =>
                        $submission->id,

                        'certificate_number' =>
                        $this->generateCertificateNumber(
                            $conference
                        ),

                        'type' =>
                        'presenter',

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

        $this->generatePdf(
            $certificate
        );

        return $certificate->fresh();
    }

    /**
     * Generate the certificate PDF and save it.
     */
    public function generatePdf(
        Certificate $certificate
    ): string {
        $certificate->load([
            'participant',
            'conference.configuration',
            'conference.setting',
            'submission',
        ]);

        $pdf =
            Pdf::loadView(
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
        | Delete old file when regenerating
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

    /**
     * Generate a unique certificate number.
     */
    private function generateCertificateNumber(
        $conference
    ): string {
        do {
            $certificateNumber =
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
                $certificateNumber
            )->exists()
        );

        return $certificateNumber;
    }
}
