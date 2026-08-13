@extends('layouts.reviewer')

@section('title', 'Dashboard')

@section('header')
    <div class="row">
        <div class="col-sm-6">
            <h1 class="mb-0 fs-3 fw-bold">Dashboard</h1>
            <p class="text-muted mb-0">
                Welcome back,
                <strong>{{ Auth::user()->name ?? 'Administrator' }}</strong>
            </p>
        </div>
        <div class="col-sm-6 text-end">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                </ol>
            </nav>
        </div>
    </div>
@endsection

@section('content')
    @if (!$reviewer)
        <div class="alert alert-warning rounded-0">
            <i class="bi bi-exclamation-triangle me-2"></i>
            Your account has not been registered as an active
            reviewer for any conference yet.
        </div>
    @else
        <div class="row mb-2">
            <div class="col-lg-4 col-md-6 mb-2">
                <div class="small-box text-bg-primary rounded-0 mb-0">
                    <div class="inner">
                        <h3>{{ $reviewCount }}</h3>
                        <p>Total Assignments</p>
                    </div>
                    <div class="small-box-icon">
                        <i class="bi bi-clipboard-check"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-2">
                <div class="small-box text-bg-warning rounded-0 mb-0">
                    <div class="inner">
                        <h3>{{ $pendingCount }}</h3>
                        <p>Pending Reviews</p>
                    </div>
                    <div class="small-box-icon">
                        <i class="bi bi-clock"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-2">
                <div class="small-box text-bg-success rounded-0 mb-0">
                    <div class="inner">
                        <h3>{{ $completedCount }}</h3>
                        <p>Completed Reviews</p>
                    </div>
                    <div class="small-box-icon">
                        <i class="bi bi-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="card rounded-0 mb-3">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="bi bi-person-check me-2"></i>
                    Reviewer Information
                </h3>
            </div>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-6">
                        <strong>Name</strong>
                        <div>{{ $reviewer->user->name }}</div>
                    </div>
                    <div class="col-md-6">
                        <strong>Conference</strong>
                        <div>{{ $reviewer->conference->name }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card rounded-0">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="bi bi-file-earmark-check me-2"></i>
                    Recent Assignments
                </h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Submission</th>
                                <th>Topic</th>
                                <th>Status</th>
                                <th width="40">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentReviews as $review)
                                <tr>
                                    <td class="align-top">
                                        <strong>{{ $review->submission->submission_code }}</strong>
                                        <small class="text-muted d-block">{{ $review->submission->title }}</small>
                                    </td>
                                    <td class="align-top">
                                        {{ $review->submission->topic?->name ?? '—' }}
                                    </td>
                                    <td class="align-top">
                                        @if ($review->reviewed_at)
                                            <span class="badge text-bg-success rounded-0">Completed</span>
                                        @else
                                            <span class="badge text-bg-warning rounded-0">Pending</span>
                                        @endif
                                    </td>
                                    <td class="align-top">
                                        @if (!$review->reviewed_at)
                                            <a href="{{ route('reviewer.reviews.edit', $review) }}"
                                                class="btn btn-primary btn-sm rounded-0">
                                                <i class="bi bi-clipboard-check"></i>
                                            </a>
                                        @else
                                            <a href="{{ route('reviewer.reviews.show', $review) }}"
                                                class="btn btn-info btn-sm rounded-0">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-5">
                                        No review assignments found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
@endsection
