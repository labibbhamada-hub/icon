<?php

namespace App\Services;

use App\Models\Certificate;
use App\Models\ConferenceAttendance;
use App\Models\Participant;
use App\Models\Submission;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CertificateGenerationService
{
    /**
     * Generate or return the participant attendance certificate.
     */
    public function createForParticipant(
        Participant $participant
    ): Certificate {
        $participant->load([
            'conference',
            'conference.setting',
            'conference.configuration',
        ]);

        if (
            $participant->registration_status !== 'confirmed'
        ) {
            throw new \RuntimeException(
                'A certificate can only be generated for a confirmed registration.'
            );
        }

        $conference = $participant->conference;

        if (!$conference) {
            throw new \RuntimeException(
                'The participant does not belong to a valid conference.'
            );
        }

        if (
            $conference->setting
            && (
                !$conference->setting->certificate_enabled
                || $conference->setting->maintenance_mode
            )
        ) {
            throw new \RuntimeException(
                'Certificate generation is currently disabled for this conference.'
            );
        }

        $attendance = ConferenceAttendance::where(
            'participant_id',
            $participant->id
        )
            ->where(
                'conference_id',
                $conference->id
            )
            ->first();

        if (
            !$attendance
            || !in_array(
                $attendance->attendance_status,
                [
                    'checked_in',
                    'verified',
                ],
                true
            )
        ) {
            throw new \RuntimeException(
                'Conference attendance has not been recorded.'
            );
        }

        /*
    |--------------------------------------------------------------------------
    | Existing attendance certificate
    |--------------------------------------------------------------------------
    */

        $existingCertificate = Certificate::where(
            'participant_id',
            $participant->id
        )
            ->where(
                'conference_id',
                $conference->id
            )
            ->whereNull(
                'submission_id'
            )
            ->where(
                'type',
                'participant'
            )
            ->first();

        if ($existingCertificate) {
            if (
                $existingCertificate->file_path
                && Storage::disk('public')->exists(
                    $existingCertificate->file_path
                )
            ) {
                return $existingCertificate;
            }

            $this->generatePdf(
                $existingCertificate
            );

            return $existingCertificate->fresh();
        }

        /*
    |--------------------------------------------------------------------------
    | Create attendance certificate
    |--------------------------------------------------------------------------
    */

        $certificate = DB::transaction(
            function () use (
                $participant,
                $conference
            ) {
                return Certificate::create([
                    'participant_id' =>
                    $participant->id,

                    'conference_id' =>
                    $conference->id,

                    'submission_id' =>
                    null,

                    'certificate_number' =>
                    $this->generateCertificateNumber(
                        $conference
                    ),

                    'type' =>
                    'participant',

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

        if ($submission->status !== 'published') {
            throw new \RuntimeException(
                'A certificate can only be generated for a published submission.'
            );
        }

        if (!$submission->participant) {
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
            |--------------------------------------------------------------------------
            | Make sure PDF exists
            |--------------------------------------------------------------------------
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
            |--------------------------------------------------------------------------
            | Certificate exists but PDF is missing
            |--------------------------------------------------------------------------
            */

            $this->generatePdf(
                $existingCertificate
            );

            return $existingCertificate->fresh();
        }

        /*
        |--------------------------------------------------------------------------
        | Create certificate record
        |--------------------------------------------------------------------------
        */

        $certificate = DB::transaction(
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

        try {
            $this->generatePdf(
                $certificate
            );
        } catch (\Throwable $e) {
            /*
            |--------------------------------------------------------------------------
            | Roll back certificate record if PDF generation fails
            |--------------------------------------------------------------------------
            |
            | generatePdf() already removes the newly created PDF when its
            | own operation fails. Here we remove the certificate record so
            | the database does not contain an incomplete certificate.
            |
            */

            try {
                $certificate->delete();
            } catch (\Throwable $deleteException) {
                report($deleteException);
            }

            throw $e;
        }

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

        /*
        |--------------------------------------------------------------------------
        | Generate unique file path
        |--------------------------------------------------------------------------
        |
        | A new unique path is used instead of overwriting the old PDF.
        | This keeps the old file intact until the database update succeeds.
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

            Storage::disk('public')->put(
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
            | Database or file operation failed
            |--------------------------------------------------------------------------
            |
            | Remove only the newly created file.
            | The old certificate file remains untouched.
            |
            */

            Storage::disk('public')->delete(
                $filePath
            );

            throw $e;
        }

        /*
        |--------------------------------------------------------------------------
        | Remove old PDF only after database update succeeds
        |--------------------------------------------------------------------------
        */

        if (
            $oldFile
            && $oldFile !== $filePath
        ) {
            try {
                Storage::disk('public')->delete(
                    $oldFile
                );
            } catch (\Throwable $e) {
                /*
                |--------------------------------------------------------------------------
                | Old file cleanup failure must not invalidate the new
                | certificate that is already stored and referenced by DB.
                |--------------------------------------------------------------------------
                */

                report($e);
            }
        }

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
