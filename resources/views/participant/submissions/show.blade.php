@extends('layouts.participant')

@section('title', 'Submission Detail')

@section('header')
    <div class="row align-items-top">
        <div class="col-sm-6">
            <div class="d-flex gap-2">
                <a href="{{ route('participant.submissions.index') }}" class="btn btn-secondary rounded-0">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h1 class="mb-0 fs-3">
                    Submission Detail
                </h1>
            </div>
            <p class="text-muted mb-0">
                View your conference submission details.
            </p>
        </div>
    </div>
@endsection

@section('content')

    <div class="card rounded-0 overflow-hidden mb-3">

        <div class="card-header rounded-0">

            <h3 class="card-title">
                <i class="bi bi-file-earmark-text me-2"></i>
                {{ $submission->submission_code }}
            </h3>
        </div>

        <div class="card-body">

            <h3 class="fw-bold mb-2">
                {{ $submission->title }}
            </h3>

            {{-- Stage --}}
            <div class="">

                @if ($submission->submission_stage === 'abstract')
                    <span class="badge text-bg-secondary rounded-0">
                        Abstract Stage
                    </span>
                @elseif ($submission->submission_stage === 'full_paper')
                    <span class="badge text-bg-primary rounded-0">
                        Full Paper Stage
                    </span>
                @endif

            </div>

            {{-- Status --}}
            <div class="">

                @if ($submission->status === 'draft')
                    <span class="badge text-bg-secondary rounded-0">
                        Draft
                    </span>
                @elseif ($submission->status === 'submitted')
                    <span class="badge text-bg-primary rounded-0">
                        Submitted
                    </span>
                @elseif ($submission->status === 'under_review')
                    <span class="badge text-bg-warning rounded-0">
                        Under Review
                    </span>
                @elseif ($submission->status === 'revision')
                    <span class="badge text-bg-warning rounded-0">
                        Revision
                    </span>
                @elseif ($submission->status === 'accepted')
                    <span class="badge text-bg-success rounded-0">
                        Accepted
                    </span>
                @elseif ($submission->status === 'rejected')
                    <span class="badge text-bg-danger rounded-0">
                        Rejected
                    </span>
                @elseif ($submission->status === 'camera_ready')
                    <span class="badge text-bg-info rounded-0">
                        Camera Ready
                    </span>
                @elseif ($submission->status === 'published')
                    <span class="badge text-bg-dark rounded-0">
                        Published
                    </span>
                @endif

            </div>

        </div>

        {{-- Workflow Messages --}}
        @if (
            $submission->status === 'revision' ||
                ($submission->submission_stage === 'abstract' && $submission->status === 'accepted') ||
                ($submission->submission_stage === 'full_paper' &&
                    ($submission->status === 'accepted' ||
                        $submission->status === 'camera_ready' ||
                        $submission->status === 'published')))

            <div class="card-body border-top">

                {{-- Revision --}}
                @if ($submission->status === 'revision')
                    <div class="alert alert-warning rounded-0 mb-2">

                        <div class="d-flex justify-content-between align-items-center gap-3">

                            <div>

                                <strong>
                                    Revision Required
                                </strong>

                                <div class="small">
                                    Your
                                    {{ $submission->submission_stage === 'abstract' ? 'abstract' : 'paper' }}
                                    requires revision based on the review result.
                                </div>

                            </div>

                            <a href="{{ route('participant.submissions.revision', $submission) }}"
                                class="btn btn-warning rounded-0">
                                <i class="bi bi-arrow-repeat me-1"></i>
                                Submit Revision
                            </a>

                        </div>

                    </div>
                @endif

                {{-- Abstract Accepted --}}
                @if ($submission->submission_stage === 'abstract' && $submission->status === 'accepted')
                    <div class="alert alert-success rounded-0 mb-2">

                        <div class="d-flex justify-content-between align-items-center gap-3">

                            <div>

                                <strong>
                                    Abstract Accepted
                                </strong>

                                <div class="small">
                                    Your abstract has been accepted. Please submit the full paper for the next review stage.
                                </div>

                            </div>

                            <a href="{{ route('participant.submissions.full-paper', $submission) }}"
                                class="btn btn-primary rounded-0 text-nowrap">
                                <i class="bi bi-upload me-1"></i>
                                Submit Full Paper
                            </a>

                        </div>

                    </div>
                @endif

                {{-- Full Paper Accepted --}}
                @if ($submission->submission_stage === 'full_paper' && $submission->status === 'accepted')

                    @if (!$submission->video_url)
                        <div class="alert alert-success rounded-0 mb-2">

                            <div class="d-flex justify-content-between align-items-center gap-3">

                                <div>

                                    <strong>
                                        Full Paper Accepted
                                    </strong>

                                    <div class="small">
                                        Your full paper has been accepted. Please submit your presentation video using your
                                        own Google Drive link.
                                    </div>

                                </div>

                                <a href="{{ route('participant.submissions.video.edit', $submission) }}"
                                    class="btn btn-primary rounded-0 text-nowrap">
                                    <i class="bi bi-camera-video me-1"></i>
                                    Submit Presentation Video
                                </a>

                            </div>

                        </div>
                    @else
                        <div class="alert alert-info rounded-0 mb-2">

                            <strong>
                                Technical Meeting Presenter
                            </strong>

                            <div class="small">
                                Your presentation video has been submitted successfully.
                                Please attend the Technical Meeting for Presenters according
                                to the conference schedule.
                            </div>

                        </div>
                    @endif

                @endif

                {{-- Published --}}
                @if ($submission->submission_stage === 'full_paper' && $submission->status === 'published')
                    <div class="alert alert-success rounded-0 mb-2">

                        <i class="bi bi-check-circle me-2"></i>

                        Your paper has been approved and published successfully.

                    </div>
                @endif

            </div>

        @endif

        {{-- Basic Information --}}
        <div class="card-body border-top">

            <div class="row mb-2">

                <div class="col-md-4">
                    <strong>
                        Conference
                    </strong>
                </div>

                <div class="col-md-8">
                    {{ $submission->conference?->name ?? '—' }}
                </div>

            </div>

            <div class="row mb-2">

                <div class="col-md-4">
                    <strong>
                        Submission Stage
                    </strong>
                </div>

                <div class="col-md-8">

                    @if ($submission->submission_stage === 'abstract')
                        Abstract
                    @elseif ($submission->submission_stage === 'full_paper')
                        Full Paper
                    @else
                        —
                    @endif

                </div>

            </div>

            <div class="row mb-2">

                <div class="col-md-4">
                    <strong>
                        Topic
                    </strong>
                </div>

                <div class="col-md-8">
                    {{ $submission->topic?->name ?? '—' }}
                </div>

            </div>

            <div class="row mb-2">

                <div class="col-md-4">
                    <strong>
                        Submitted At
                    </strong>
                </div>

                <div class="col-md-8">
                    {{ $submission->submitted_at?->format('d F Y H:i') ?? '—' }}
                </div>

            </div>

        </div>

        {{-- Abstract --}}
        <div class="card-body border-top">

            <h5 class="fw-semibold mb-2">
                Abstract
            </h5>

            <div class="mb-2">
                {!! nl2br(e($submission->abstract)) !!}
            </div>

        </div>

        {{-- Keywords --}}
        <div class="card-body border-top">

            <h5 class="fw-semibold mb-2">
                Keywords
            </h5>

            <p class="mb-2">
                {{ $submission->keywords }}
            </p>

        </div>

        {{-- Authors --}}
        <div class="card-body border-top">

            <h5 class="fw-semibold mb-2">
                Authors
            </h5>

            <ol class="mb-2">

                @foreach ($submission->authors as $author)
                    <li class="mb-3">

                        <div>
                            <strong>
                                {{ $author->name }}
                            </strong>

                            @if ($author->is_corresponding)
                                <span class="badge text-bg-success rounded-0 ms-1">
                                    Corresponding
                                </span>
                            @endif
                        </div>

                        @if ($author->email)
                            <small class="text-muted d-block">
                                Email: {{ $author->email }}
                            </small>
                        @endif

                        @if ($author->institution)
                            <small class="text-muted d-block">
                                Institution: {{ $author->institution }}
                            </small>
                        @endif

                        @if ($author->department)
                            <small class="text-muted d-block">
                                Department: {{ $author->department }}
                            </small>
                        @endif

                    </li>
                @endforeach

            </ol>

        </div>

        @if (
            $submission->paper_file ||
                $submission->revised_file ||
                $submission->camera_ready_file ||
                ($submission->submission_stage === 'full_paper' &&
                    in_array($submission->status, ['accepted', 'camera_ready', 'published'], true)))

            <div class="card-footer">
                {{-- Original Paper --}}
                @if ($submission->paper_file)
                    <a href="{{ route('participant.submissions.paper.download', $submission) }}"
                        class="btn btn-outline-danger rounded-0">
                        <i class="bi bi-file-earmark-pdf me-1"></i>
                        Full Paper
                    </a>
                @endif

                {{-- Revised File --}}
                @if ($submission->revised_file)
                    <a href="{{ route('participant.submissions.revision.download', $submission) }}"
                        class="btn btn-outline-warning rounded-0">
                        <i class="bi bi-file-earmark-pdf me-1"></i>
                        Revised Paper
                    </a>
                @endif

                {{-- Camera Ready --}}
                @if ($submission->camera_ready_file)
                    <a href="{{ route('participant.submissions.camera-ready.download', $submission) }}"
                        class="btn btn-outline-success rounded-0">
                        <i class="bi bi-file-earmark-pdf me-1"></i>
                        Camera Ready
                    </a>
                @endif

                {{-- LOA is only available for accepted FULL PAPER --}}
                @if (
                    $submission->submission_stage === 'full_paper' &&
                        in_array($submission->status, ['accepted', 'camera_ready', 'published'], true))
                    <a href="{{ route('participant.submissions.loa', $submission) }}" class="btn btn-success rounded-0">
                        <i class="bi bi-file-earmark-check me-1"></i>
                        View LOA
                    </a>
                @endif
            </div>

        @endif

    </div>

    @if ($submission->video_url)
        <div class="card rounded-0 overflow-hidden mb-3">
            <div class="card-header rounded-top-3">
                <h5 class="mb-0">
                    <i class="fas fa-video me-1"></i>
                    Presentation Video
                </h5>
            </div>

            <div class="card-body">
                <div class="mb-2">
                    <label class="form-label fw-semibold">
                        Video URL
                    </label>

                    <div class="input-group">
                        <input type="text" class="form-control rounded-0" value="{{ $submission->video_url }}"
                            readonly>

                        <a href="{{ $submission->video_url }}" target="_blank" rel="noopener noreferrer"
                            class="btn btn-outline-primary rounded-0">
                            <i class="fas fa-external-link-alt me-1"></i>
                            Open Video
                        </a>
                    </div>
                </div>

                @if ($submission->video_submitted_at)
                    <div class="text-muted small mb-2">
                        <i class="far fa-clock me-1"></i>
                        Submitted at:
                        {{ $submission->video_submitted_at->format('d F Y H:i') }}
                    </div>
                @endif

                <a href="{{ route('participant.submissions.video.edit', $submission) }}"
                    class="btn btn-primary rounded-0">
                    <i class="fas fa-edit me-1"></i>
                    Edit Video Link
                </a>
            </div>
        </div>
    @endif

@endsection
