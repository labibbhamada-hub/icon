<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Participant\SubmissionRequest;
use App\Models\Participant;
use App\Models\Submission;
use App\Models\Topic;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        $participants = Participant::with('conference')
            ->where('user_id', Auth::id())
            ->where(
                'registration_status',
                'confirmed'
            )
            ->whereHas('conference', function ($query) {
                $query->where(
                    'status',
                    'submission_open'
                );
            })
            ->get();

        $participant = $participants->first();

        if ($participants->isEmpty()) {
            return redirect()
                ->route('participant.submissions.index')
                ->with(
                    'error',
                    'You do not have any confirmed registration with submissions currently open.'
                );
        }

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
                'participants',
                'topics'
            )
        );
    }

    public function store(SubmissionRequest $request)
    {
        $data = $request->validated();

        $participant = Participant::where(
            'id',
            $data['participant_id']
        )
            ->where(
                'user_id',
                Auth::id()
            )
            ->where(
                'registration_status',
                'confirmed'
            )
            ->whereHas('conference', function ($query) {
                $query->where(
                    'status',
                    'submission_open'
                );
            })
            ->firstOrFail();

        $submission = DB::transaction(function () use (
            $request,
            $data,
            $participant
        ) {
            $paperFile = $request
                ->file('paper_file')
                ->store(
                    'submissions/papers',
                    'public'
                );

            $submission = Submission::create([
                'conference_id' =>
                $participant->conference_id,

                'participant_id' =>
                $participant->id,

                'topic_id' =>
                $data['topic_id'],

                'submission_code' =>
                $this->generateSubmissionCode(),

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
                $data['authors'] as $index => $author
            ) {
                $submission->authors()->create([
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
                    $author['sort_order'] ??
                        ($index + 1),
                ]);
            }

            return $submission;
        });

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

    public function show(Submission $submission)
    {
        $participant = Participant::where(
            'user_id',
            Auth::id()
        )
            ->where(
                'id',
                $submission->participant_id
            )
            ->first();

        abort_unless($participant, 403);

        $submission->load([
            'conference',
            'topic',
            'authors',
        ]);

        return view(
            'participant.submissions.show',
            compact('submission')
        );
    }

    private function generateSubmissionCode(): string
    {
        do {
            $code = 'ICON26-' .
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
