<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubmissionRequest;
use App\Models\Conference;
use App\Models\Participant;
use App\Models\Submission;
use App\Models\SubmissionAuthor;
use App\Models\Topic;
use App\Mail\SubmissionStatusMail;
use App\Exports\SubmissionsExport;
use App\Jobs\SendCameraReadyApprovedWhatsApp;
use App\Jobs\SendCameraReadyCorrectionWhatsApp;
use App\Notifications\ConferenceNotification;
use App\Services\CertificateGenerationService;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubmissionController extends Controller
{
    public function index()
    {
        $submissions = Submission::with([
            'conference',
            'participant',
            'topic',
        ])
            ->latest()
            ->paginate(15);

        return view('admin.submissions.index', compact('submissions'));
    }

    public function create()
    {
        $conferences = Conference::orderByDesc('year')
            ->get();

        $participants = Participant::with('conference')
            ->latest('registered_at')
            ->get();

        $topics = Topic::with('conference')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view(
            'admin.submissions.create',
            compact(
                'conferences',
                'participants',
                'topics'
            )
        );
    }

    public function store(SubmissionRequest $request)
    {
        $data = $request->validated();

        DB::transaction(function () use (
            $request,
            $data
        ) {
            $data['submission_code'] =
                $this->generateSubmissionCode();

            if (
                $data['status'] === 'submitted'
                && empty($data['submitted_at'])
            ) {
                $data['submitted_at'] = now();
            }

            if ($request->hasFile('paper_file')) {
                $data['paper_file'] = $request
                    ->file('paper_file')
                    ->store(
                        'submissions/papers',
                        'local'
                    );
            }

            unset($data['authors']);

            $submission = Submission::create($data);

            foreach (
                $request->validated('authors') as $index => $author
            ) {
                $submission->authors()->create([
                    'name' => $author['name'],
                    'email' => $author['email'] ?? null,
                    'institution' => $author['institution'] ?? null,
                    'department' => $author['department'] ?? null,
                    'is_corresponding' =>
                    !empty($author['is_corresponding']),
                    'sort_order' =>
                    $author['sort_order'] ?? $index + 1,
                ]);
            }
        });

        return redirect()
            ->route('admin.submissions.index')
            ->with(
                'success',
                'Submission created successfully.'
            );
    }

    public function show(Submission $submission)
    {
        $submission->load([
            'conference',
            'participant',
            'topic',
            'authors',
            'reviews.reviewer.user',
        ]);

        return view('admin.submissions.show', compact('submission'));
    }

    public function edit(Submission $submission)
    {
        $submission->load('authors');

        $conferences = Conference::orderByDesc('year')
            ->get();

        $participants = Participant::where(
            'conference_id',
            $submission->conference_id
        )
            ->orderBy('full_name')
            ->get();

        $topics = Topic::where(
            'conference_id',
            $submission->conference_id
        )
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('admin.submissions.edit', compact('submission', 'conferences', 'participants', 'topics'));
    }

    public function update(
        SubmissionRequest $request,
        Submission $submission
    ) {
        $data = $request->validated();

        DB::transaction(function () use (
            $request,
            $data,
            $submission
        ) {
            if (
                $data['status'] === 'submitted'
                && empty($data['submitted_at'])
                && !$submission->submitted_at
            ) {
                $data['submitted_at'] = now();
            }

            if ($request->hasFile('paper_file')) {

                if ($submission->paper_file) {
                    Storage::disk('local')
                        ->delete($submission->paper_file);
                }

                $data['paper_file'] = $request
                    ->file('paper_file')
                    ->store(
                        'submissions/papers',
                        'local'
                    );
            }

            unset($data['authors']);

            $submission->update($data);

            $submission->authors()->delete();

            foreach (
                $request->validated('authors') as $index => $author
            ) {
                $submission->authors()->create([
                    'name' => $author['name'],
                    'email' => $author['email'] ?? null,
                    'institution' => $author['institution'] ?? null,
                    'department' => $author['department'] ?? null,
                    'is_corresponding' =>
                    !empty($author['is_corresponding']),
                    'sort_order' =>
                    $author['sort_order'] ?? $index + 1,
                ]);
            }
        });

        return redirect()
            ->route(
                'admin.submissions.show',
                $submission
            )
            ->with(
                'success',
                'Submission updated successfully.'
            );
    }

    public function destroy(
        Submission $submission
    ) {
        DB::transaction(
            function () use ($submission) {

                if ($submission->paper_file) {
                    Storage::disk('local')
                        ->delete(
                            $submission->paper_file
                        );
                }

                if ($submission->revised_file) {
                    Storage::disk('local')
                        ->delete(
                            $submission->revised_file
                        );
                }

                if ($submission->camera_ready_file) {
                    Storage::disk('local')
                        ->delete(
                            $submission->camera_ready_file
                        );
                }

                $submission->delete();
            }
        );

        return redirect()
            ->route(
                'admin.submissions.index'
            )
            ->with(
                'success',
                'Submission deleted successfully.'
            );
    }

    public function approveCameraReady(
        Submission $submission,
        \App\Services\CertificateGenerationService $certificateGenerationService
    ) {
        if (
            $submission->submission_stage !== 'full_paper'
            || $submission->status !== 'camera_ready'
        ) {
            return back()
                ->with(
                    'error',
                    'Only submitted camera-ready full papers can be approved.'
                );
        }

        if (
            !$submission->camera_ready_file
        ) {
            return back()
                ->with(
                    'error',
                    'Camera-ready file is not available.'
                );
        }

        if (
            !Storage::disk('local')->exists(
                $submission->camera_ready_file
            )
        ) {
            return back()
                ->with(
                    'error',
                    'The camera-ready file could not be found on the server.'
                );
        }

        DB::transaction(function () use (
            $submission
        ) {
            $submission->update([
                'status' => 'published',
            ]);
        });

        /*
    |--------------------------------------------------------------------------
    | Generate presenter certificate
    |--------------------------------------------------------------------------
    */

        try {
            $certificate =
                $certificateGenerationService
                ->createForSubmission(
                    $submission->fresh()
                );
        } catch (\Throwable $e) {
            /*
        |--------------------------------------------------------------------------
        | Certificate failure should not undo publication
        |--------------------------------------------------------------------------
        |
        | The paper has already been approved and published.
        | Certificate can be generated again from the Admin certificate
        | management page.
        |
        */

            report($e);

            $certificate = null;
        }

        $submission->load([
            'participant.user',
        ]);

        if (
            $submission->participant?->email
        ) {
            Mail::to(
                $submission->participant->email
            )->queue(
                new SubmissionStatusMail(
                    $submission,
                    'Your camera-ready paper has been approved and your paper has been published successfully.'
                )
            );
        }

        if (
            $submission->participant?->phone
        ) {
            SendCameraReadyApprovedWhatsApp::dispatch(
                $submission->participant->id,
                $submission->id
            );
        }

        if (
            $submission->participant?->user
        ) {
            $submission
                ->participant
                ->user
                ->notify(
                    new ConferenceNotification(
                        'Camera Ready Approved',
                        'Your camera-ready paper has been approved and your paper has been published successfully.',
                        'View Submission',
                        route(
                            'participant.submissions.show',
                            $submission
                        ),
                        'success'
                    )
                );
        }

        $message =
            $certificate
            ? 'Camera-ready paper approved, published, and presenter certificate generated successfully.'
            : 'Camera-ready paper approved and published successfully. Certificate generation will need to be completed from Certificate Management.';

        return redirect()
            ->route(
                'admin.submissions.show',
                $submission
            )
            ->with(
                'success',
                $message
            );
    }

    public function requestCameraReadyCorrection(Request $request, Submission $submission)
    {
        if (
            $submission->submission_stage !== 'full_paper'
            || $submission->status !== 'camera_ready'
        ) {
            return back()
                ->with(
                    'error',
                    'Only submitted camera-ready full papers can receive a correction request.'
                );
        }

        $validated = $request->validate([
            'correction_reason' => [
                'required',
                'string',
                'max:5000',
            ],
        ]);

        $submission->update([
            'status' => 'accepted',
            'camera_ready_correction_reason' => $validated['correction_reason'],
        ]);

        $submission->load([
            'participant.user',
        ]);

        if ($submission->participant?->email) {
            Mail::to(
                $submission->participant->email
            )->queue(
                new SubmissionStatusMail(
                    $submission,
                    'Your camera-ready paper requires correction. Please review the correction reason in the participant portal and upload the corrected manuscript.'
                )
            );
        }

        if ($submission->participant?->phone) {
            SendCameraReadyCorrectionWhatsApp::dispatch(
                $submission->participant->id,
                $submission->id
            );
        }

        if ($submission->participant?->user) {
            $submission->participant->user->notify(
                new ConferenceNotification(
                    'Camera-Ready Correction Required',
                    'Your camera-ready paper requires correction. Please review the correction reason and upload the corrected manuscript.',
                    'Upload Camera Ready',
                    route(
                        'participant.submissions.camera-ready',
                        $submission
                    ),
                    'warning'
                )
            );
        }

        return redirect()
            ->route(
                'admin.submissions.show',
                $submission
            )
            ->with(
                'success',
                'Camera-ready correction requested from participant.'
            );
    }

    private function generateSubmissionCode(): string
    {
        do {
            $code = 'ICON26-' . strtoupper(
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

    public function downloadCameraReady(
        Submission $submission
    ) {
        abort_unless(
            $submission->camera_ready_file
                && Storage::disk('local')->exists(
                    $submission->camera_ready_file
                ),
            404
        );

        return Storage::disk('local')->response(
            $submission->camera_ready_file,
            basename($submission->camera_ready_file),
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' .
                    basename($submission->camera_ready_file) .
                    '"',
            ]
        );
    }

    public function downloadPaper(Submission $submission)
    {
        abort_unless(
            $submission->paper_file
                && Storage::disk('local')->exists(
                    $submission->paper_file
                ),
            404
        );

        return Storage::disk('local')->download(
            $submission->paper_file,
            basename($submission->paper_file)
        );
    }

    public function downloadRevisedPaper(Submission $submission)
    {
        abort_unless(
            $submission->revised_file
                && Storage::disk('local')->exists(
                    $submission->revised_file
                ),
            404
        );

        return Storage::disk('local')->download(
            $submission->revised_file,
            basename($submission->revised_file)
        );
    }

    public function export(Request $request)
    {
        $conferenceId = $request->integer('conference_id');

        $suffix = $conferenceId
            ? '-conference-' . $conferenceId
            : '-all';

        return Excel::download(
            new SubmissionsExport($conferenceId),
            'submissions' .
                $suffix .
                '-' .
                now()->format('Y-m-d') .
                '.xlsx'
        );
    }
}
