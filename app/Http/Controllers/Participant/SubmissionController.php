<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Participant\CameraReadyRequest;
use App\Http\Requests\Participant\RevisionRequest;
use App\Http\Requests\Participant\SubmissionRequest;
use App\Mail\SubmissionStatusMail;
use App\Models\ImportantDate;
use App\Models\Participant;
use App\Models\Review;
use App\Models\Submission;
use App\Models\Topic;
use App\Services\PaymentCalculationService;
use Barryvdh\DomPDF\Facade\Pdf;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SubmissionController extends Controller
{
    public function index()
    {
        $participantIds = Participant::where(
            'user_id',
            Auth::id()
        )
            ->pluck('id');

        $submissions = Submission::with([
            'conference',
            'topic',
            'authors',
        ])
            ->whereIn(
                'participant_id',
                $participantIds
            )
            ->latest()
            ->paginate(10);

        return view(
            'participant.submissions.index',
            compact('submissions')
        );
    }

    public function create()
    {
        $participant = Participant::with([
            'conference.setting',
            'conference.configuration',
            'registrationType',
        ])
            ->where(
                'user_id',
                Auth::id()
            )
            ->where(function ($query) {
                $query
                    ->where(
                        'registration_status',
                        'confirmed'
                    )
                    ->orWhere(function ($query) {
                        $query
                            ->where(
                                'registration_status',
                                'pending'
                            )
                            ->whereHas(
                                'registrationType',
                                function ($query) {
                                    $query->where(
                                        'category',
                                        'presenter'
                                    );
                                }
                            );
                    });
            })
            ->whereHas(
                'conference.setting',
                function ($query) {
                    $query
                        ->where(
                            'is_active',
                            true
                        )
                        ->where(
                            'published',
                            true
                        )
                        ->where(
                            'submission_enabled',
                            true
                        )
                        ->where(
                            'maintenance_mode',
                            false
                        );
                }
            )
            ->latest()
            ->first();

        if (!$participant) {
            return redirect()
                ->route(
                    'participant.submissions.index'
                )
                ->with(
                    'error',
                    'You do not have a conference registration currently open for paper submission.'
                );
        }

        if (
            !$this->isSubmissionOpen(
                $participant->conference_id
            )
        ) {
            return redirect()
                ->route(
                    'participant.submissions.index'
                )
                ->with(
                    'error',
                    'The paper submission deadline has passed for this conference.'
                );
        }

        $submissionDeadline =
            ImportantDate::where(
                'conference_id',
                $participant->conference_id
            )
            ->where(
                'type',
                'full_paper_submission'
            )
            ->where(
                'is_active',
                true
            )
            ->orderByDesc('date')
            ->first();

        $topics = Topic::where(
            'conference_id',
            $participant->conference_id
        )
            ->where(
                'is_active',
                true
            )
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view(
            'participant.submissions.create',
            compact(
                'participant',
                'topics',
                'submissionDeadline'
            )
        );
    }

    public function store(
        SubmissionRequest $request
    ) {
        $data = $request->validated();

        $participant = Participant::with([
            'conference.setting',
            'conference.configuration',
            'registrationType',
        ])
            ->where(
                'id',
                $data['participant_id']
            )
            ->where(
                'user_id',
                Auth::id()
            )
            ->where(function ($query) {
                $query
                    ->where(
                        'registration_status',
                        'confirmed'
                    )
                    ->orWhere(function ($query) {
                        $query
                            ->where(
                                'registration_status',
                                'pending'
                            )
                            ->whereHas(
                                'registrationType',
                                function ($query) {
                                    $query->where(
                                        'category',
                                        'presenter'
                                    );
                                }
                            );
                    });
            })
            ->firstOrFail();

        $canSubmit =
            $participant->registration_status === 'confirmed'
            || (
                $participant->registration_status === 'pending'
                && $participant->registrationType?->category === 'presenter'
            );

        abort_unless(
            $canSubmit,
            403
        );

        if (
            !$participant->conference?->setting?->submission_enabled
            || $participant->conference?->setting?->maintenance_mode
        ) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Submission is currently unavailable for this conference.'
                );
        }

        if (
            !$this->isSubmissionOpen(
                $participant->conference_id
            )
        ) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'The paper submission deadline has passed for this conference.'
                );
        }

        $topic = Topic::where(
            'id',
            $data['topic_id']
        )
            ->where(
                'conference_id',
                $participant->conference_id
            )
            ->where(
                'is_active',
                true
            )
            ->firstOrFail();

        $submission = DB::transaction(
            function () use (
                $request,
                $data,
                $participant,
                $topic
            ) {
                $paperFile =
                    $request
                    ->file('paper_file')
                    ->store(
                        'submissions/papers',
                        'local'
                    );

                $submission = Submission::create([
                    'conference_id' =>
                    $participant->conference_id,

                    'participant_id' =>
                    $participant->id,

                    'topic_id' =>
                    $topic->id,

                    'submission_code' =>
                    $this->generateSubmissionCode(
                        $participant->conference
                    ),

                    'title' =>
                    $data['title'],

                    'abstract' =>
                    $data['abstract'],

                    'keywords' =>
                    $data['keywords'],

                    'paper_file' =>
                    $paperFile,

                    'status' =>
                    'submitted',

                    'submitted_at' =>
                    now(),
                ]);

                foreach (
                    $data['authors']
                    as $index => $author
                ) {
                    $submission
                        ->authors()
                        ->create([
                            'name' =>
                            $author['name'],

                            'email' =>
                            $author['email'] ?? null,

                            'institution' =>
                            $author['institution'] ?? null,

                            'department' =>
                            $author['department'] ?? null,

                            'is_corresponding' =>
                            !empty($author['is_corresponding']),

                            'sort_order' =>
                            $author['sort_order']
                                ?? ($index + 1),
                        ]);
                }

                return $submission;
            }
        );

        $submission->load([
            'participant',
        ]);

        if (
            $submission->participant?->email
        ) {
            Mail::to(
                $submission->participant->email
            )->queue(
                new SubmissionStatusMail(
                    $submission,
                    'Your submission has been received successfully and is now waiting for the review process.'
                )
            );
        }

        return redirect()
            ->route(
                'participant.submissions.show',
                $submission
            )
            ->with(
                'success',
                'Submission created successfully.'
            );
    }

    public function show(
        Submission $submission
    ) {
        $participant = Participant::where(
            'user_id',
            Auth::id()
        )
            ->where(
                'id',
                $submission->participant_id
            )
            ->first();

        abort_unless(
            $participant,
            403
        );

        $submission->load([
            'conference.setting',
            'topic',
            'authors',
        ]);

        return view(
            'participant.submissions.show',
            compact('submission')
        );
    }

    public function loa(
        Submission $submission
    ) {
        $participant =
            $this->getOwnedSubmissionParticipant(
                $submission
            );

        $submission->load([
            'conference.configuration',
            'topic',
            'authors',
        ]);

        if (
            !in_array(
                $submission->status,
                [
                    'accepted',
                    'camera_ready',
                    'published',
                ],
                true
            )
        ) {
            return redirect()
                ->route(
                    'participant.submissions.show',
                    $submission
                )
                ->with(
                    'error',
                    'The Letter of Acceptance is only available for accepted papers.'
                );
        }

        $importantDates =
            ImportantDate::where(
                'conference_id',
                $submission->conference_id
            )
            ->where(
                'is_active',
                true
            )
            ->whereIn(
                'type',
                [
                    'registration',
                    'abstract_submission',
                    'full_paper_submission',
                    'conference',
                ]
            )
            ->orderBy('date')
            ->orderBy('sort_order')
            ->get();

        return view(
            'participant.submissions.loa',
            compact(
                'submission',
                'participant',
                'importantDates'
            )
        );
    }

    public function downloadLoa(
        Submission $submission
    ) {
        $participant =
            $this->getOwnedSubmissionParticipant(
                $submission
            );

        $submission->load([
            'conference.configuration',
            'topic',
            'authors',
        ]);

        if (
            !in_array(
                $submission->status,
                [
                    'accepted',
                    'camera_ready',
                    'published',
                ],
                true
            )
        ) {
            return redirect()
                ->route(
                    'participant.submissions.show',
                    $submission
                )
                ->with(
                    'error',
                    'The Letter of Acceptance is only available for accepted papers.'
                );
        }

        $importantDates =
            ImportantDate::where(
                'conference_id',
                $submission->conference_id
            )
            ->where(
                'is_active',
                true
            )
            ->whereIn(
                'type',
                [
                    'registration',
                    'abstract_submission',
                    'full_paper_submission',
                    'conference',
                ]
            )
            ->orderBy('date')
            ->orderBy('sort_order')
            ->get();

        $loaNumber =
            $this->getLoaNumber(
                $submission
            );

        $verificationUrl =
            route(
                'loa.verify',
                $submission->submission_code
            );

        $qrCode =
            Builder::create()
            ->writer(new PngWriter())
            ->writerOptions([])
            ->data($verificationUrl)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(
                ErrorCorrectionLevel::High
            )
            ->size(180)
            ->margin(8)
            ->roundBlockSizeMode(
                RoundBlockSizeMode::Margin
            )
            ->validateResult(false)
            ->build();

        $qrCodeDataUri =
            $qrCode->getDataUri();

        $pdf =
            Pdf::loadView(
                'participant.submissions.loa-pdf',
                compact(
                    'submission',
                    'participant',
                    'importantDates',
                    'loaNumber',
                    'verificationUrl',
                    'qrCodeDataUri'
                )
            )
            ->setPaper(
                'a4',
                'portrait'
            );

        return $pdf->download(
            'LOA-' .
                $submission->submission_code .
                '.pdf'
        );
    }

    public function revision(
        Submission $submission
    ) {
        $participant =
            $this->getOwnedSubmissionParticipant(
                $submission
            );

        $submission->load([
            'conference.setting',
        ]);

        $revisionDeadline =
            $this->getRevisionDeadline(
                $submission->conference_id
            );

        if (
            !$submission->conference?->setting?->review_enabled
        ) {
            return redirect()
                ->route(
                    'participant.submissions.show',
                    $submission
                )
                ->with(
                    'error',
                    'Review workflow is currently disabled.'
                );
        }

        if (
            !$this->isRevisionOpen(
                $submission->conference_id
            )
        ) {
            return redirect()
                ->route(
                    'participant.submissions.show',
                    $submission
                )
                ->with(
                    'error',
                    'The revision deadline has passed for this conference.'
                );
        }

        if (
            $submission->status !== 'revision'
        ) {
            return redirect()
                ->route(
                    'participant.submissions.show',
                    $submission
                )
                ->with(
                    'error',
                    'This submission is not currently requesting a revision.'
                );
        }

        return view(
            'participant.submissions.revision',
            compact(
                'submission',
                'participant',
                'revisionDeadline'
            )
        );
    }

    public function uploadRevision(
        RevisionRequest $request,
        Submission $submission
    ) {
        $this->getOwnedSubmissionParticipant(
            $submission
        );

        $submission->load([
            'conference.setting',
        ]);

        if (
            !$submission->conference?->setting?->review_enabled
        ) {
            return redirect()
                ->route(
                    'participant.submissions.show',
                    $submission
                )
                ->with(
                    'error',
                    'Review workflow is currently disabled.'
                );
        }

        if (
            !$this->isRevisionOpen(
                $submission->conference_id
            )
        ) {
            return redirect()
                ->route(
                    'participant.submissions.show',
                    $submission
                )
                ->with(
                    'error',
                    'The revision deadline has passed for this conference.'
                );
        }

        if (
            $submission->status !== 'revision'
        ) {
            return redirect()
                ->route(
                    'participant.submissions.show',
                    $submission
                )
                ->with(
                    'error',
                    'This submission is not currently requesting a revision.'
                );
        }

        $oldFile =
            $submission->revised_file;

        $newFile =
            $request
            ->file('revised_file')
            ->store(
                'submissions/revisions',
                'local'
            );

        DB::transaction(
            function () use (
                $submission,
                $oldFile,
                $newFile
            ) {
                $currentRound =
                    Review::where(
                        'submission_id',
                        $submission->id
                    )
                    ->max('review_round');

                $nextRound =
                    $currentRound
                    ? $currentRound + 1
                    : 1;

                $submission->update([
                    'revised_file' =>
                    $newFile,

                    'status' =>
                    'under_review',
                ]);

                $oldReviews =
                    Review::where(
                        'submission_id',
                        $submission->id
                    )
                    ->where(
                        'review_round',
                        $currentRound
                    )
                    ->get();

                foreach (
                    $oldReviews
                    as $oldReview
                ) {
                    Review::create([
                        'submission_id' =>
                        $submission->id,

                        'reviewer_id' =>
                        $oldReview->reviewer_id,

                        'review_round' =>
                        $nextRound,

                        'score' =>
                        null,

                        'comment' =>
                        null,

                        'recommendation' =>
                        null,

                        'reviewed_at' =>
                        null,
                    ]);
                }

                if ($oldFile) {
                    Storage::disk('local')
                        ->delete(
                            $oldFile
                        );
                }
            }
        );

        return redirect()
            ->route(
                'participant.submissions.show',
                $submission
            )
            ->with(
                'success',
                'Revised paper uploaded successfully and sent back for review.'
            );
    }

    public function cameraReady(
        Submission $submission,
        PaymentCalculationService $paymentCalculationService
    ) {
        $participant =
            $this->getOwnedSubmissionParticipant(
                $submission
            );

        $participant->load([
            'registrationType.presentationPrices',
            'submissions',
            'payments',
        ]);

        $submission->load([
            'conference.setting',
        ]);

        $cameraReadyDeadline =
            $this->getCameraReadyDeadline(
                $submission->conference_id
            );

        if (
            !$submission->conference?->setting?->submission_enabled
            || $submission->conference?->setting?->maintenance_mode
        ) {
            return redirect()
                ->route(
                    'participant.submissions.show',
                    $submission
                )
                ->with(
                    'error',
                    'Submission workflow is currently unavailable.'
                );
        }

        if (
            !$this->isCameraReadyOpen(
                $submission->conference_id
            )
        ) {
            return redirect()
                ->route(
                    'participant.submissions.show',
                    $submission
                )
                ->with(
                    'error',
                    'The camera-ready submission deadline has passed for this conference.'
                );
        }

        if (
            $submission->status !== 'accepted'
        ) {
            return redirect()
                ->route(
                    'participant.submissions.show',
                    $submission
                )
                ->with(
                    'error',
                    'Camera-ready submission is only available for accepted papers.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Presentation Details Check
        |--------------------------------------------------------------------------
        */

        if (
            empty($submission->presentation_type)
            || empty($submission->presentation_mode)
            || empty($submission->presenter_author_id)
        ) {
            return redirect()
                ->route(
                    'participant.submissions.show',
                    $submission
                )
                ->with(
                    'error',
                    'Please complete the presentation details before submitting the camera-ready paper.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Payment Check
        |--------------------------------------------------------------------------
        */

        $paymentCalculation =
            $paymentCalculationService
            ->calculate(
                $participant
            );

        if (
            $paymentCalculation['outstanding_amount'] > 0
        ) {
            return redirect()
                ->route(
                    'participant.submissions.show',
                    $submission
                )
                ->with(
                    'error',
                    'Camera-ready submission is only available after your payment has been completed.'
                );
        }

        return view(
            'participant.submissions.camera-ready',
            compact(
                'submission',
                'participant',
                'cameraReadyDeadline'
            )
        );
    }

    public function uploadCameraReady(
        CameraReadyRequest $request,
        Submission $submission,
        PaymentCalculationService $paymentCalculationService
    ) {
        $participant =
            $this->getOwnedSubmissionParticipant(
                $submission
            );

        $participant->load([
            'registrationType.presentationPrices',
            'submissions',
            'payments',
        ]);

        $submission->load([
            'conference.setting',
        ]);

        if (
            !$submission->conference?->setting?->submission_enabled
            || $submission->conference?->setting?->maintenance_mode
        ) {
            return redirect()
                ->route(
                    'participant.submissions.show',
                    $submission
                )
                ->with(
                    'error',
                    'Submission workflow is currently unavailable.'
                );
        }

        if (
            !$this->isCameraReadyOpen(
                $submission->conference_id
            )
        ) {
            return redirect()
                ->route(
                    'participant.submissions.show',
                    $submission
                )
                ->with(
                    'error',
                    'The camera-ready submission deadline has passed for this conference.'
                );
        }

        if (
            $submission->status !== 'accepted'
        ) {
            return redirect()
                ->route(
                    'participant.submissions.show',
                    $submission
                )
                ->with(
                    'error',
                    'Camera-ready submission is only available for accepted papers.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Presentation Details Check
        |--------------------------------------------------------------------------
        */

        if (
            empty($submission->presentation_type)
            || empty($submission->presentation_mode)
            || empty($submission->presenter_author_id)
        ) {
            return redirect()
                ->route(
                    'participant.submissions.show',
                    $submission
                )
                ->with(
                    'error',
                    'Please complete the presentation details before submitting the camera-ready paper.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Payment Check
        |--------------------------------------------------------------------------
        */

        $paymentCalculation =
            $paymentCalculationService
            ->calculate(
                $participant
            );

        if (
            $paymentCalculation['outstanding_amount'] > 0
        ) {
            return redirect()
                ->route(
                    'participant.submissions.show',
                    $submission
                )
                ->with(
                    'error',
                    'Camera-ready submission is only available after your payment has been completed.'
                );
        }

        $oldFile =
            $submission->camera_ready_file;

        $newFile =
            $request
            ->file('camera_ready_file')
            ->store(
                'submissions/camera-ready',
                'local'
            );

        DB::transaction(
            function () use (
                $submission,
                $oldFile,
                $newFile
            ) {
                $submission->update([
                    'camera_ready_file' =>
                    $newFile,

                    'camera_ready_correction_reason' =>
                    null,

                    'status' =>
                    'camera_ready',
                ]);

                if ($oldFile) {
                    Storage::disk('local')
                        ->delete(
                            $oldFile
                        );
                }
            }
        );

        return redirect()
            ->route(
                'participant.submissions.show',
                $submission
            )
            ->with(
                'success',
                'Camera-ready paper uploaded successfully.'
            );
    }

    public function downloadPaper(
        Submission $submission
    ) {
        $this->getOwnedSubmissionParticipant(
            $submission
        );

        abort_unless(
            $submission->paper_file
                && Storage::disk('local')->exists(
                    $submission->paper_file
                ),
            404
        );

        return Storage::disk('local')->download(
            $submission->paper_file,
            basename(
                $submission->paper_file
            )
        );
    }

    public function downloadRevision(
        Submission $submission
    ) {
        $this->getOwnedSubmissionParticipant(
            $submission
        );

        abort_unless(
            $submission->revised_file
                && Storage::disk('local')->exists(
                    $submission->revised_file
                ),
            404
        );

        return Storage::disk('local')->download(
            $submission->revised_file,
            basename(
                $submission->revised_file
            )
        );
    }

    public function downloadCameraReady(
        Submission $submission
    ) {
        $this->getOwnedSubmissionParticipant(
            $submission
        );

        abort_unless(
            $submission->camera_ready_file
                && Storage::disk('local')->exists(
                    $submission->camera_ready_file
                ),
            404
        );

        return Storage::disk('local')->download(
            $submission->camera_ready_file,
            basename(
                $submission->camera_ready_file
            )
        );
    }

    private function getOwnedSubmissionParticipant(
        Submission $submission
    ): Participant {
        return Participant::where(
            'user_id',
            Auth::id()
        )
            ->where(
                'id',
                $submission->participant_id
            )
            ->firstOrFail();
    }

    private function getSubmissionDeadline(
        $conferenceId
    ): ?ImportantDate {
        return ImportantDate::where(
            'conference_id',
            $conferenceId
        )
            ->where(
                'type',
                'full_paper_submission'
            )
            ->where(
                'is_active',
                true
            )
            ->orderByDesc('date')
            ->first();
    }

    private function isSubmissionOpen(
        $conferenceId
    ): bool {
        $deadline =
            $this->getSubmissionDeadline(
                $conferenceId
            );

        if (!$deadline) {
            return true;
        }

        $today =
            now()->startOfDay();

        $startDate =
            $deadline->date
            ->copy()
            ->startOfDay();

        if ($deadline->end_date) {
            return $today->between(
                $startDate,
                $deadline->end_date
                    ->copy()
                    ->endOfDay()
            );
        }

        return $today->lte(
            $startDate
        );
    }

    private function getRevisionDeadline(
        $conferenceId
    ): ?ImportantDate {
        return ImportantDate::where(
            'conference_id',
            $conferenceId
        )
            ->where(
                'type',
                'revision'
            )
            ->where(
                'is_active',
                true
            )
            ->orderByDesc('date')
            ->first();
    }

    private function isRevisionOpen(
        $conferenceId
    ): bool {
        $deadline =
            $this->getRevisionDeadline(
                $conferenceId
            );

        if (!$deadline) {
            return true;
        }

        $today =
            now()->startOfDay();

        $startDate =
            $deadline->date
            ->copy()
            ->startOfDay();

        if ($deadline->end_date) {
            return $today->between(
                $startDate,
                $deadline->end_date
                    ->copy()
                    ->endOfDay()
            );
        }

        return $today->lte(
            $startDate
        );
    }

    private function getCameraReadyDeadline(
        $conferenceId
    ): ?ImportantDate {
        return ImportantDate::where(
            'conference_id',
            $conferenceId
        )
            ->where(
                'type',
                'camera_ready'
            )
            ->where(
                'is_active',
                true
            )
            ->orderByDesc('date')
            ->first();
    }

    private function isCameraReadyOpen(
        $conferenceId
    ): bool {
        $deadline =
            $this->getCameraReadyDeadline(
                $conferenceId
            );

        if (!$deadline) {
            return true;
        }

        $today =
            now()->startOfDay();

        $startDate =
            $deadline->date
            ->copy()
            ->startOfDay();

        if ($deadline->end_date) {
            return $today->between(
                $startDate,
                $deadline->end_date
                    ->copy()
                    ->endOfDay()
            );
        }

        return $today->lte(
            $startDate
        );
    }

    private function getLoaNumber(
        Submission $submission
    ): string {
        $year =
            $submission->conference?->year
            ?? now()->year;

        return 'LOA/ICON/' .
            $year .
            '/' .
            str_pad(
                $submission->id,
                4,
                '0',
                STR_PAD_LEFT
            );
    }

    private function generateSubmissionCode(
        $conference
    ): string {
        $prefix =
            strtoupper(
                $conference->short_name
            );

        $year =
            $conference->year;

        do {
            $code =
                $prefix .
                '-' .
                $year .
                '-' .
                strtoupper(
                    Str::random(8)
                );
        } while (
            Submission::where(
                'submission_code',
                $code
            )->exists()
        );

        return $code;
    }
}
