@extends('layouts.admin')

@section('title', 'Reviewer Detail')

@section('header')

    <div class="row">

        <div class="col-sm-6">

            <div class="d-flex align-items-center gap-2">

                <a href="{{ route('admin.reviewers.index') }}" class="btn btn-secondary btn-sm rounded-0" title="Back">
                    <i class="bi bi-arrow-left"></i>
                </a>

                <h1 class="mb-0 fs-3">
                    Reviewer Detail
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
                        <a href="{{ route('admin.reviewers.index') }}">
                            Reviewers
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

        $totalReviews = $reviewer->reviews->count();

        $completedReviews = $reviewer->reviews->filter(fn($review) => !is_null($review->reviewed_at))->count();

        $pendingReviews = $totalReviews - $completedReviews;

    @endphp


    {{-- ============================================================
        REVIEWER INFORMATION
    ============================================================= --}}

    <div class="card rounded-0 mb-3">

        <div class="card-header">

            <h3 class="card-title">

                <i class="bi bi-person-check me-2"></i>

                Reviewer Information

            </h3>

            <div class="float-end">

                <a href="{{ route('admin.reviewers.edit', $reviewer) }}" class="btn btn-warning btn-sm rounded-0">
                    <i class="bi bi-pencil me-1"></i>
                    Edit Reviewer
                </a>

            </div>

        </div>


        <div class="card-body">

            <div class="row align-items-start">

                {{-- Identity --}}
                <div class="col-lg-3 col-md-4 mb-3 mb-lg-0">

                    <div class="border rounded-0 p-3 text-center h-100">

                        <div class="rounded-circle bg-primary-subtle d-flex align-items-center justify-content-center mx-auto mb-3"
                            style="width: 80px; height: 80px;">

                            <i class="bi bi-person-check text-primary" style="font-size: 2.5rem;"></i>

                        </div>


                        @if ($reviewer->user)
                            <h5 class="fw-bold mb-1">
                                {{ $reviewer->user->name }}
                            </h5>

                            <div class="text-muted small">
                                {{ $reviewer->user->email }}
                            </div>
                        @else
                            <h5 class="fw-bold mb-1">
                                -
                            </h5>
                        @endif


                        <div class="mt-3">

                            @if ($reviewer->is_active)
                                <span class="badge text-bg-success rounded-0">
                                    <i class="bi bi-check-circle me-1"></i>
                                    Active
                                </span>
                            @else
                                <span class="badge text-bg-secondary rounded-0">
                                    <i class="bi bi-pause-circle me-1"></i>
                                    Inactive
                                </span>
                            @endif

                        </div>

                    </div>

                </div>


                {{-- Details --}}
                <div class="col-lg-9 col-md-8">

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <div class="text-muted small mb-1">
                                Conference
                            </div>

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


                        <div class="col-md-6 mb-3">

                            <div class="text-muted small mb-1">
                                Institution
                            </div>

                            <div class="fw-semibold">

                                {{ $reviewer->institution ?: '-' }}

                            </div>

                        </div>


                        <div class="col-md-6 mb-3">

                            <div class="text-muted small mb-1">
                                Expertise
                            </div>

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


                        <div class="col-md-3 mb-3">

                            <div class="text-muted small mb-1">
                                Account Role
                            </div>

                            <span class="badge text-bg-primary rounded-0">
                                Reviewer
                            </span>

                        </div>


                        <div class="col-md-3 mb-3">

                            <div class="text-muted small mb-1">
                                Status
                            </div>

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

            </div>

        </div>


        {{-- Biography --}}
        @if ($reviewer->bio)
            <div class="card-body border-top">

                <div class="text-muted small mb-1">
                    Biography
                </div>

                <div>
                    {!! nl2br(e($reviewer->bio)) !!}
                </div>

            </div>
        @endif

    </div>


    {{-- ============================================================
        REVIEW SUMMARY
    ============================================================= --}}

    <div class="row mb-3">

        <div class="col-md-4 mb-3 mb-md-0">

            <div class="border rounded-0 p-3 bg-primary text-white">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <div class="small opacity-75">
                            Total Reviews
                        </div>

                        <div class="fs-3 fw-bold">
                            {{ $totalReviews }}
                        </div>

                    </div>

                    <i class="bi bi-clipboard-data fs-2 opacity-75"></i>

                </div>

            </div>

        </div>


        <div class="col-md-4 mb-3 mb-md-0">

            <div class="border rounded-0 p-3 bg-success text-white">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <div class="small opacity-75">
                            Completed Reviews
                        </div>

                        <div class="fs-3 fw-bold">
                            {{ $completedReviews }}
                        </div>

                    </div>

                    <i class="bi bi-check-circle fs-2 opacity-75"></i>

                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="border rounded-0 p-3 bg-warning text-dark">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <div class="small opacity-75">
                            Pending Reviews
                        </div>

                        <div class="fs-3 fw-bold">
                            {{ $pendingReviews }}
                        </div>

                    </div>

                    <i class="bi bi-hourglass-split fs-2 opacity-75"></i>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
        REVIEW HISTORY
    ============================================================= --}}

    <div class="card rounded-0">

        <div class="card-header">

            <h3 class="card-title">

                <i class="bi bi-clipboard-check me-2"></i>

                Review History

            </h3>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

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

                                <td>
                                    {{ $loop->iteration }}
                                </td>


                                <td>

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


                                <td>

                                    <span class="badge text-bg-secondary rounded-0">
                                        Round {{ $review->review_round }}
                                    </span>

                                </td>


                                <td>

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


                                <td>

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


                                <td>

                                    @if ($review->recommendation)
                                        {{ ucwords(str_replace('_', ' ', $review->recommendation)) }}
                                    @else
                                        <span class="text-muted">
                                            -
                                        </span>
                                    @endif

                                </td>


                                <td>

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
