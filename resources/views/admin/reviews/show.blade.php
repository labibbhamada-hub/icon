@extends('layouts.admin')

@section('title', 'Review Detail')

@section('header')

    <div class="row">

        <div class="col-sm-6">

            <div class="d-flex align-items-center gap-2">

                <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary btn-sm rounded-0" title="Back">
                    <i class="bi bi-arrow-left"></i>
                </a>

                <h1 class="mb-0 fs-3">
                    Review Detail
                </h1>

            </div>

        </div>

        <div class="col-sm-6">

            <nav aria-label="breadcrumb">

                <ol class="breadcrumb float-sm-end mb-0">

                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">
                            Dashboard
                        </a>
                    </li>

                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.reviews.index') }}">
                            Reviews
                        </a>
                    </li>

                    <li class="breadcrumb-item active" aria-current="page">
                        Detail
                    </li>

                </ol>

            </nav>

        </div>

    </div>

@endsection


@section('content')

    @php

        $recommendationLabels = [
            'accept' => 'Accept',
            'minor_revision' => 'Minor Revision',
            'major_revision' => 'Major Revision',
            'reject' => 'Reject',
        ];

        $recommendationClasses = [
            'accept' => 'success',
            'minor_revision' => 'warning',
            'major_revision' => 'warning',
            'reject' => 'danger',
        ];

        $recommendationLabel = $recommendationLabels[$review->recommendation] ?? 'Pending';

        $recommendationClass = $recommendationClasses[$review->recommendation] ?? 'secondary';

    @endphp


    {{-- ============================================================
        REVIEW EVALUATION
    ============================================================= --}}

    <div class="card rounded-0 mb-3">

        <div class="card-header">

            <h3 class="card-title">

                <i class="bi bi-clipboard-check me-2"></i>
                Review Evaluation

            </h3>

            @if (!$review->reviewed_at)
                <div class="float-end">

                    <a href="{{ route('admin.reviews.edit', $review) }}" class="btn btn-primary btn-sm rounded-0">
                        <i class="bi bi-pencil me-1"></i>
                        Submit Review
                    </a>

                </div>
            @endif

        </div>


        <div class="card-body">

            <div class="row g-3">

                {{-- Score --}}
                <div class="col-md-4">

                    <div class="border rounded-0 p-3 text-center h-100">

                        <div class="text-muted small mb-2">
                            Score
                        </div>

                        @if (!is_null($review->score))
                            <div class="display-6 fw-bold">
                                {{ number_format((float) $review->score, 2) }}
                            </div>

                            <small class="text-muted">
                                out of 100
                            </small>
                        @else
                            <div class="display-6 fw-bold text-muted">
                                -
                            </div>
                        @endif

                    </div>

                </div>


                {{-- Recommendation --}}
                <div class="col-md-4">

                    <div class="border rounded-0 p-3 h-100">

                        <div class="text-muted small mb-2">
                            Recommendation
                        </div>

                        <span class="badge text-bg-{{ $recommendationClass }} rounded-0">
                            {{ $recommendationLabel }}
                        </span>

                    </div>

                </div>


                {{-- Review Status --}}
                <div class="col-md-4">

                    <div class="border rounded-0 p-3 h-100">

                        <div class="text-muted small mb-2">
                            Review Status
                        </div>

                        @if ($review->reviewed_at)
                            <span class="badge text-bg-success rounded-0">
                                <i class="bi bi-check-circle me-1"></i>
                                Completed
                            </span>
                        @else
                            <span class="badge text-bg-warning rounded-0">
                                <i class="bi bi-hourglass-split me-1"></i>
                                Pending
                            </span>
                        @endif

                    </div>

                </div>

            </div>

        </div>


        {{-- Reviewer Comment --}}
        <div class="card-body border-top">

            <div class="text-muted small mb-2">
                Reviewer Comment
            </div>

            @if ($review->comment)
                <div class="lh-lg">

                    {!! nl2br(e($review->comment)) !!}

                </div>
            @else
                <p class="text-muted mb-0">
                    No review comment has been submitted.
                </p>
            @endif

        </div>

    </div>


    {{-- ============================================================
        REVIEW INFORMATION
    ============================================================= --}}

    <div class="card rounded-0 mb-3">

        <div class="card-header">

            <h3 class="card-title">

                <i class="bi bi-info-circle me-2"></i>
                Review Information

            </h3>

        </div>


        <div class="card-body">

            <div class="row">

                {{-- Reviewer --}}
                <div class="col-md-6 mb-3">

                    <div class="text-muted small mb-1">
                        Reviewer
                    </div>

                    @if ($review->reviewer?->user)
                        <div class="fw-semibold">
                            {{ $review->reviewer->user->name }}
                        </div>

                        <small class="text-muted">
                            {{ $review->reviewer->user->email }}
                        </small>
                    @else
                        <span class="text-muted">
                            -
                        </span>
                    @endif

                </div>


                {{-- Institution --}}
                <div class="col-md-6 mb-3">

                    <div class="text-muted small mb-1">
                        Institution
                    </div>

                    <div>
                        {{ $review->reviewer?->institution ?: '-' }}
                    </div>

                </div>


                {{-- Conference --}}
                <div class="col-md-6 mb-3">

                    <div class="text-muted small mb-1">
                        Conference
                    </div>

                    @if ($review->submission?->conference)
                        <div class="fw-semibold">
                            {{ $review->submission->conference->name }}
                        </div>

                        <small class="text-muted">
                            {{ $review->submission->conference->short_name }}
                            ({{ $review->submission->conference->year }})
                        </small>
                    @else
                        <span class="text-muted">
                            -
                        </span>
                    @endif

                </div>


                {{-- Review Round --}}
                <div class="col-md-6 mb-3">

                    <div class="text-muted small mb-1">
                        Review Round
                    </div>

                    <span class="badge text-bg-secondary rounded-0">
                        Round {{ $review->review_round }}
                    </span>

                </div>


                {{-- Submission --}}
                <div class="col-md-6 mb-3">

                    <div class="text-muted small mb-1">
                        Submission
                    </div>

                    @if ($review->submission)
                        <a href="{{ route('admin.submissions.show', $review->submission) }}"
                            class="fw-semibold text-decoration-none">
                            {{ $review->submission->submission_code }}
                        </a>
                    @else
                        <span class="text-muted">
                            -
                        </span>
                    @endif

                </div>


                {{-- Reviewed At --}}
                <div class="col-md-6 mb-3">

                    <div class="text-muted small mb-1">
                        Reviewed At
                    </div>

                    <div>
                        {{ $review->reviewed_at?->format('d F Y H:i') ?? '-' }}
                    </div>

                </div>


                {{-- Created At --}}
                <div class="col-md-6">

                    <div class="text-muted small mb-1">
                        Assigned At
                    </div>

                    <div>
                        {{ $review->created_at?->format('d F Y H:i') ?? '-' }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
        SUBMISSION CONTEXT
    ============================================================= --}}

    @if ($review->submission)

        <div class="card rounded-0">

            <div class="card-header">

                <h3 class="card-title">

                    <i class="bi bi-file-earmark-text me-2"></i>
                    Submission

                </h3>

                <div class="float-end">

                    <a href="{{ route('admin.submissions.show', $review->submission) }}"
                        class="btn btn-info btn-sm rounded-0">
                        <i class="bi bi-eye me-1"></i>
                        View Submission
                    </a>

                </div>

            </div>


            <div class="card-body">

                <h5 class="fw-bold mb-2">

                    {{ $review->submission->title }}

                </h5>


                <div class="d-flex flex-wrap gap-1">

                    @if ($review->submission->topic)
                        <span class="badge text-bg-primary rounded-0">
                            {{ $review->submission->topic->name }}
                        </span>
                    @endif


                    @if ($review->submission->presentation_type)
                        <span class="badge text-bg-secondary rounded-0">
                            {{ ucfirst($review->submission->presentation_type) }}
                        </span>
                    @endif


                    @if ($review->submission->presentation_mode)
                        <span class="badge text-bg-secondary rounded-0">
                            {{ ucfirst($review->submission->presentation_mode) }}
                        </span>
                    @endif

                </div>

            </div>

        </div>

    @endif

@endsection
