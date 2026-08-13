@extends('layouts.admin')

@section('title', 'Review Detail')

@section('header')
    <div class="row">
        <div class="col-sm-6 d-flex align-items-center gap-2">
            <a href="{{ route('admin.submissions.index') }}" class="btn btn-secondary btn-sm rounded-0">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h1 class="mb-0 fs-3">
                Review Detail
            </h1>
        </div>
        <div class="col-sm-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.submissions.index') }}">Reviews</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Detail</li>
                </ol>
            </nav>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="card rounded-0 mb-3">
                <div class="card-header">
                    <h3 class="card-title">
                        Review Evaluation
                    </h3>
                    <div class="float-end">
                        @if (!$review->reviewed_at)
                            <a href="{{ route('admin.reviews.edit', $review) }}" class="btn btn-primary btn-sm rounded-0">
                                <i class="bi bi-pencil"></i>
                                Submit Review
                            </a>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-4 mb-2">
                            <div class="border rounded-0 text-center p-4 h-100">
                                <small class="text-muted d-block mb-2">Score</small>
                                <div class="display-5 fw-bold">
                                    {{ $review->score ?? '—' }}
                                </div>
                                @if ($review->score !== null)
                                    <small class="text-muted">
                                        out of 100
                                    </small>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-8 mb-2">
                            <div class="border rounded-0 p-4 h-100">
                                <div class="mb-2">
                                    <small class="text-muted d-block">Recommendation</small>
                                    @if ($review->recommendation === 'accept')
                                        <span class="badge text-bg-success rounded-0">
                                            Accept
                                        </span>
                                    @elseif ($review->recommendation === 'minor_revision')
                                        <span class="badge text-bg-warning rounded-0">
                                            Minor Revision
                                        </span>
                                    @elseif ($review->recommendation === 'major_revision')
                                        <span class="badge text-bg-warning rounded-0">
                                            Major Revision
                                        </span>
                                    @elseif ($review->recommendation === 'reject')
                                        <span class="badge text-bg-danger rounded-0">
                                            Reject
                                        </span>
                                    @else
                                        <span class="badge text-bg-secondary rounded-0">
                                            Pending
                                        </span>
                                    @endif
                                </div>
                                <div class="mb-2">
                                    <small class="text-muted d-block">Review Status</small>
                                    @if ($review->reviewed_at)
                                        <span class="badge text-bg-success rounded-0">
                                            Completed
                                        </span>
                                    @else
                                        <span class="badge text-bg-warning rounded-0">
                                            Pending
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body border-top">
                    <h5 class="fw-bold mb-2">Reviewer Comment</h5>
                    @if ($review->comment)
                        <div class="mb-2">
                            {!! nl2br(e($review->comment)) !!}
                        </div>
                    @else
                        <p class="text-muted mb-2">
                            No review comment has been submitted.
                        </p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card rounded-0 mb-3">
                <div class="card-header">
                    <h3 class="card-title">
                        Review Information
                    </h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered mb-0">
                        <tbody>
                            <tr>
                                <th>Reviewer</th>
                                <td>
                                    <strong>
                                        {{ $review->reviewer?->user?->name ?? '—' }}
                                    </strong>
                                    @if ($review->reviewer?->user?->email)
                                        <small class="text-muted d-block">
                                            {{ $review->reviewer->user->email }}
                                        </small>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Institution</th>
                                <td>
                                    {{ $review->reviewer?->institution ?: '—' }}
                                </td>
                            </tr>
                            <tr>
                                <th>Conference</th>
                                <td>
                                    {{ $review->submission?->conference?->short_name ?? '—' }}
                                </td>
                            </tr>
                            <tr>
                                <th>Submission</th>
                                <td>
                                    @if ($review->submission)
                                        <a href="{{ route('admin.submissions.show', $review->submission) }}">
                                            {{ $review->submission->submission_code }}
                                        </a>
                                    @else
                                        —
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Reviewed At</th>
                                <td>
                                    {{ $review->reviewed_at?->format('d M Y H:i') ?? '—' }}
                                </td>
                            </tr>
                            <tr>
                                <th>Created At</th>
                                <td>
                                    {{ $review->created_at->format('d M Y H:i') }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @if ($review->submission)
        <div class="card rounded-0 mb-3">
            <div class="card-header">
                <h3 class="card-title">
                    Submission
                </h3>
                <div class="float-end">
                    <a href="{{ route('admin.submissions.show', $review->submission) }}" class="btn btn-info btn-sm rounded-0">
                        <i class="bi bi-eye me-1"></i>
                        View Submission
                    </a>
                </div>
            </div>
            <div class="card-body">
                <h5 class="fw-bold">
                    {{ $review->submission->title }}
                </h5>
                @if ($review->submission->topic)
                    <span class="badge text-bg-primary rounded-0 mt-2">
                        {{ $review->submission->topic->name }}
                    </span>
                @endif
            </div>
        </div>
    @endif
@endsection
