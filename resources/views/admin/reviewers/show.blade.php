@extends('layouts.admin')

@section('title', 'Reviewer Detail')

@section('header')
    <div class="row">
        <div class="col-sm-8">
            <div class="d-flex gap-2">
                <a href="{{ route('admin.reviewers.index') }}" class="btn btn-secondary rounded-0">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h1 class="mb-0 fs-3">
                    Reviewer Detail
                </h1>
            </div>
            <p class="text-muted mb-0">
                View the details and information of this conference reviewer.
            </p>
        </div>
    </div>
@endsection

@section('content')
    @php
        $totalReviews = $reviewer->reviews->count();
        $completedReviews = $reviewer->reviews->filter(fn($review) => !is_null($review->reviewed_at))->count();
        $pendingReviews = $totalReviews - $completedReviews;
    @endphp

    <div class="card rounded-0 overflow-hidden mb-3">
        <div class="card-header">
            <h3 class="card-title">
                <i class="bi bi-person-check me-2"></i>
                Reviewer Information
            </h3>
        </div>
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-md-4">
                    <strong>
                        Reviewer User
                    </strong>
                </div>
                <div class="col-md-8">
                    @if ($reviewer->user)
                        {{ $reviewer->user->name }}
                    @else
                        -
                    @endif
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-4">
                    <strong>
                        Reviewer Email
                    </strong>
                </div>
                <div class="col-md-8">
                    @if ($reviewer->user)
                        {{ $reviewer->user->email }}
                    @else
                        -
                    @endif
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-4">
                    <strong>
                        Biography
                    </strong>
                </div>
                <div class="col-md-8">
                    @if ($reviewer->bio)
                        {!! nl2br(e($reviewer->bio)) !!}
                    @else
                        -
                    @endif
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-4">
                    <strong>
                        Conference
                    </strong>
                </div>
                <div class="col-md-8">
                    @if ($reviewer->conference)
                        <div class="fw-semibold">
                            {{ $reviewer->conference->name }}
                        </div>
                        <small class="text-muted">
                            {{ $reviewer->conference->short_name }}
                            ({{ $reviewer->conference->year }})
                        </small>
                    @else
                        <span class="text-muted">
                            -
                        </span>
                    @endif
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-4">
                    <strong>
                        Institution
                    </strong>
                </div>
                <div class="col-md-8">
                    {{ $reviewer->institution ?: '-' }}
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-4">
                    <strong>
                        Expertise
                    </strong>
                </div>
                <div class="col-md-8">
                    @if ($reviewer->expertise)
                        <div>
                            {!! nl2br(e($reviewer->expertise)) !!}
                        </div>
                    @else
                        <span class="text-muted">
                            -
                        </span>
                    @endif
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-4">
                    <strong>
                        Account Role
                    </strong>
                </div>
                <div class="col-md-8">
                    <span class="badge text-bg-primary rounded-0">
                        Reviewer
                    </span>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-4">
                    <strong>
                        Status
                    </strong>
                </div>
                <div class="col-md-8">
                    @if ($reviewer->is_active)
                        <span class="badge text-bg-success rounded-0">
                            Active
                        </span>
                    @else
                        <span class="badge text-bg-secondary rounded-0">
                            Inactive
                        </span>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-footer">
            <a href="{{ route('admin.reviewers.edit', $reviewer) }}" class="btn btn-warning rounded-0">
                <i class="bi bi-pencil me-1"></i>
                Edit Reviewer
            </a>
        </div>
    </div>

    <div class="row mb-0">
        <div class="col-md-4 mb-2 mb-md-0">
            <div class="info-box rounded-0">
                <span class="info-box-icon text-bg-primary shadow-sm rounded-0">
                    <i class="bi bi-clipboard-data"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Reviews</span>
                    <span class="info-box-number">
                        {{ $totalReviews }}
                    </span>
                </div>
                <!-- /.info-box-content -->
            </div>
        </div>
        <div class="col-md-4 mb-2 mb-md-0">
            <div class="info-box rounded-0">
                <span class="info-box-icon text-bg-success shadow-sm rounded-0">
                    <i class="bi bi-check-circle"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Completed Reviews</span>
                    <span class="info-box-number">
                        {{ $completedReviews }}
                    </span>
                </div>
                <!-- /.info-box-content -->
            </div>
        </div>
        <div class="col-md-4 mb-2 mb-md-0">
            <div class="info-box rounded-0">
                <span class="info-box-icon text-bg-warning shadow-sm rounded-0">
                    <i class="bi bi-hourglass-split"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Pending Reviews</span>
                    <span class="info-box-number">
                        {{ $pendingReviews }}
                    </span>
                </div>
                <!-- /.info-box-content -->
            </div>
        </div>
    </div>

    <div class="card rounded-0 overflow-hidden">
        <div class="card-header rounded-0">
            <h3 class="card-title">
                <i class="bi bi-clipboard-check me-2"></i>
                Review History
            </h3>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive rounded-0">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th width="50">
                                No
                            </th>
                            <th>
                                Submission
                            </th>
                            <th width="90">
                                Round
                            </th>
                            <th width="120">
                                Status
                            </th>
                            <th width="90">
                                Score
                            </th>
                            <th width="160">
                                Recommendation
                            </th>
                            <th width="160">
                                Reviewed At
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reviewer->reviews as $review)
                            <tr>
                                <td class="align-top">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="align-top">
                                    @if ($review->submission)
                                        <div class="fw-semibold">
                                            {{ $review->submission->submission_code }}
                                        </div>
                                        <small class="text-muted d-block">
                                            {{ \Illuminate\Support\Str::limit($review->submission->title, 65) }}
                                        </small>
                                    @else
                                        <span class="text-muted">
                                            -
                                        </span>
                                    @endif
                                </td>
                                <td class="align-top">
                                    <span class="badge text-bg-secondary rounded-0">
                                        Round {{ $review->review_round }}
                                    </span>
                                </td>
                                <td class="align-top">
                                    @if ($review->reviewed_at)
                                        <span class="badge text-bg-success rounded-0">
                                            <i class="bi bi-check-circle me-1"></i>
                                            Completed
                                        </span>
                                    @else
                                        <span class="badge text-bg-warning rounded-0">
                                            Pending
                                        </span>
                                    @endif
                                </td>
                                <td class="align-top">
                                    @if (!is_null($review->score))
                                        <strong>
                                            {{ number_format((float) $review->score, 2) }}
                                        </strong>
                                    @else
                                        <span class="text-muted">
                                            -
                                        </span>
                                    @endif
                                </td>
                                <td class="align-top">
                                    @if ($review->recommendation)
                                        {{ ucwords(str_replace('_', ' ', $review->recommendation)) }}
                                    @else
                                        <span class="text-muted">
                                            -
                                        </span>
                                    @endif
                                </td>
                                <td class="align-top">
                                    @if ($review->reviewed_at)
                                        {{ $review->reviewed_at->format('d M Y H:i') }}
                                    @else
                                        <span class="text-muted">
                                            -
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="mb-2">
                                        <i class="bi bi-clipboard-x display-6 text-muted"></i>
                                    </div>
                                    <h5 class="mb-1">
                                        No Reviews Found
                                    </h5>
                                    <p class="text-muted mb-0">
                                        This reviewer has not been assigned any reviews yet.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
