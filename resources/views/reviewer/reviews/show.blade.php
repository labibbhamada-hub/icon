@extends('layouts.reviewer')

@section('title', 'Review Detail')

@section('header')
    <div class="row align-items-center">
        <div class="col-sm-6">
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('reviewer.reviews.index') }}" class="btn btn-secondary btn-sm rounded-0">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h1 class="mb-0 fs-3">
                    Review Detail
                </h1>
            </div>
            <p class="text-muted mb-0 mt-1">
                View your submitted review.
            </p>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('reviewer.dashboard') }}">
                        Dashboard
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('reviewer.reviews.index') }}">
                        My Reviews
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
    <div class="row">
        <div class="col-lg-8">
            <div class="card rounded-0">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-file-earmark-text me-2"></i>
                        {{ $review->submission->submission_code }}
                    </h3>
                    <div class="float-end">
                        @if (!$review->reviewed_at)
                            <a href="{{ route('reviewer.reviews.edit', $review) }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-pencil me-1"></i>
                                Continue Review
                            </a>
                        @else
                            <span class="badge text-bg-success rounded-0">
                                Completed
                            </span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h4 class="fw-bold mb-2">
                            {{ $review->submission->title }}
                        </h4>
                        <span class="badge text-bg-primary rounded-0">
                            {{ $review->submission->topic?->name ?? 'No Topic' }}
                        </span>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <div class="border rounded-0 p-3 text-center">
                                <small class="text-muted d-block">
                                    Score
                                </small>
                                <div class="display-6 fw-bold">
                                    {{ $review->score ?? '—' }}
                                </div>
                                @if ($review->score !== null)
                                    <small class="text-muted">
                                        / 100
                                    </small>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-8 mb-2">
                            <div class="border rounded-0 p-3 h-100">
                                <small class="text-muted d-block mb-2">
                                    Recommendation
                                </small>
                                @if ($review->recommendation === 'accept')
                                    <span class="badge text-bg-success rounded-0 fs-6">
                                        Accept
                                    </span>
                                @elseif ($review->recommendation === 'minor_revision')
                                    <span class="badge text-bg-warning rounded-0 fs-6">
                                        Minor Revision
                                    </span>
                                @elseif ($review->recommendation === 'major_revision')
                                    <span class="badge text-bg-warning rounded-0 fs-6">
                                        Major Revision
                                    </span>
                                @elseif ($review->recommendation === 'reject')
                                    <span class="badge text-bg-danger rounded-0 fs-6">
                                        Reject
                                    </span>
                                @else
                                    <span class="badge text-bg-secondary rounded-0 fs-6">
                                        Pending
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body border-top">
                    <h5 class="fw-semibold mb-2">
                        Review Comment
                    </h5>
                    @if ($review->comment)
                        <div>
                            {!! nl2br(e($review->comment)) !!}
                        </div>
                    @else
                        <p class="text-muted mt-3 mb-0">
                            Your review has not been submitted yet.
                        </p>
                    @endif
                </div>
                @if ($review->submission?->paper_file)
                    <div class="card-footer">
                        <a href="{{ route('reviewer.reviews.paper.download', $review) }}"
                            class="btn btn-danger btn-sm rounded-0">
                            <i class="bi bi-file-earmark-pdf me-1"></i>
                            Open Paper
                        </a>
                    </div>
                @endif
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card rounded-0">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-info-circle me-2"></i>
                        Paper Information
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <strong>
                                Conference
                            </strong>
                        </div>
                        <div class="col-md-8">
                            {{ $review->submission?->conference?->short_name ?? '—' }}
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <strong>
                                Authors
                            </strong>
                        </div>
                        <div class="col-md-8">
                            {{ $review->submission?->authors?->count() ?? 0 }}
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <strong>
                                Reviewed At
                            </strong>
                        </div>
                        <div class="col-md-8">
                            {{ $review->reviewed_at?->format('d M Y H:i') ?? '—' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
