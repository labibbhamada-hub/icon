@extends('layouts.participant')

@section('title', 'Submission Detail')

@section('header')
    <div class="row align-items-top">
        <div class="col-sm-6">
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('participant.submissions.index') }}" class="btn btn-secondary btn-sm rounded-0">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h1 class="mb-0 fs-3">
                    Submission Detail
                </h1>
            </div>
            <p class="text-muted mb-0">
                View your submitted conference paper.
            </p>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('participant.dashboard') }}">
                        Dashboard
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('participant.submissions.index') }}">
                        My Submissions
                    </a>
                </li>
                <li class="breadcrumb-item active">
                    Detail
                </li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="card rounded-0">
        <div class="card-header">
            <h3 class="card-title">
                <i class="bi bi-file-earmark-text me-2"></i>
                {{ $submission->submission_code }}
            </h3>
            <div class="float-end">
                @if ($submission->paper_file)
                    <a href="{{ asset('storage/' . $submission->paper_file) }}" target="_blank"
                        class="btn btn-outline-danger btn-sm rounded-0">
                        <i class="bi bi-file-earmark-pdf"></i>
                        Original Paper
                    </a>
                @endif
                @if ($submission->revised_file)
                    <a href="{{ asset('storage/' . $submission->revised_file) }}" target="_blank"
                        class="btn btn-outline-warning btn-sm rounded-0">
                        <i class="bi bi-file-earmark-pdf"></i>
                        Revised Paper
                    </a>
                @endif
                @if ($submission->camera_ready_file)
                    <a href="{{ asset('storage/' . $submission->camera_ready_file) }}" target="_blank"
                        class="btn btn-outline-success btn-sm rounded-0">
                        <i class="bi bi-file-earmark-pdf"></i>
                        Camera Ready
                    </a>
                @endif
            </div>
        </div>
        <div class="card-body">
            <h3 class="fw-bold mb-2">{{ $submission->title }}</h3>
            <div class="mb-2">
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
            @if ($submission->status === 'revision')
                <div class="alert alert-warning rounded-0 mb-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>
                                Revision Required
                            </strong>
                            <div class="small">
                                Your paper requires revision based on the review result.
                            </div>
                        </div>
                        <a href="{{ route('participant.submissions.revision', $submission) }}"
                            class="btn btn-warning btn-sm">
                            <i class="bi bi-arrow-repeat me-1"></i>
                            Submit Revision
                        </a>
                    </div>
                </div>
            @endif
            @if ($submission->status === 'accepted')
                <div class="alert alert-success rounded-0 mb-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>
                                Paper Accepted
                            </strong>
                            <div class="small">
                                Please upload the final camera-ready version.
                            </div>
                        </div>
                        <a href="{{ route('participant.submissions.camera-ready', $submission) }}"
                            class="btn btn-success btn-sm">
                            <i class="bi bi-file-earmark-check me-1"></i>
                            Upload Camera Ready
                        </a>
                    </div>
                </div>
            @endif
            @if ($submission->status === 'camera_ready')
                <div class="alert alert-info rounded-0 mb-2">
                    <i class="bi bi-hourglass-split me-2"></i>
                    Your camera-ready paper has been submitted and is currently
                    waiting for administrative approval.
                </div>
            @endif
            @if ($submission->status === 'published')
                <div class="alert alert-success rounded-0 mt-4">
                    <i class="bi bi-check-circle me-2"></i>
                    Your paper has been approved and published successfully.
                </div>
            @endif
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
        <div class="card-body border-top">
            <h5 class="fw-semibold mb-2">Abstract</h5>
            <div class="mb-2">
                {!! nl2br(e($submission->abstract)) !!}
            </div>
        </div>
        <div class="card-body border-top">
            <h5 class="fw-semibold mb-2">Keywords</h5>
            <p class="mb-2">{{ $submission->keywords }}</p>
        </div>
        <div class="card-body border-top">
            <h5 class="fw-semibold mb-2">Authors</h5>
            <ol class="mb-2">
                @foreach ($submission->authors as $author)
                    <li class="mb-2">
                        <strong>
                            {{ $author->name }}
                        </strong>
                        @if ($author->is_corresponding)
                            <span class="badge text-bg-success rounded-0 ms-1">
                                Corresponding
                            </span>
                        @endif
                        @if ($author->institution)
                            <small class="text-muted d-block">
                                {{ $author->institution }}
                            </small>
                        @endif
                    </li>
                @endforeach
            </ol>
        </div>
    </div>
@endsection
