<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubmissionRequest;
use App\Mail\SubmissionStatusMail;
use App\Models\Conference;
use App\Models\Participant;
use App\Models\Submission;
use App\Models\SubmissionAuthor;
use App\Models\Topic;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
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
                    ->store('submissions/papers', 'public');
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
                    Storage::disk('public')
                        ->delete($submission->paper_file);
                }

                $data['paper_file'] = $request
                    ->file('paper_file')
                    ->store('submissions/papers', 'public');
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

    public function destroy(Submission $submission)
    {
        DB::transaction(function () use ($submission) {

            if ($submission->paper_file) {
                Storage::disk('public')
                    ->delete($submission->paper_file);
            }

            if ($submission->revised_file) {
                Storage::disk('public')
                    ->delete($submission->revised_file);
            }

            if ($submission->camera_ready_file) {
                Storage::disk('public')
                    ->delete($submission->camera_ready_file);
            }

            $submission->delete();
        });

        return redirect()
            ->route('admin.submissions.index')
            ->with(
                'success',
                'Submission deleted successfully.'
            );
    }

    public function approveCameraReady(Submission $submission)
    {
        if ($submission->status !== 'camera_ready') {
            return back()
                ->with(
                    'error',
                    'This submission is not currently awaiting camera-ready approval.'
                );
        }

        if (!$submission->camera_ready_file) {
            return back()
                ->with(
                    'error',
                    'Camera-ready file is not available.'
                );
        }

        $submission->update([
            'status' => 'published',
        ]);

        $submission->load('participant');

        if ($submission->participant?->email) {

            Mail::to(
                $submission->participant->email
            )->send(
                new SubmissionStatusMail(
                    $submission,
                    'Your camera-ready paper has been approved and published successfully.'
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
                'Camera-ready paper approved and marked as published.'
            );
    }

    public function requestCameraReadyCorrection(Submission $submission)
    {
        if ($submission->status !== 'camera_ready') {
            return back()
                ->with(
                    'error',
                    'This submission is not currently awaiting camera-ready approval.'
                );
        }

        $submission->update([
            'status' => 'accepted',
        ]);

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
}
