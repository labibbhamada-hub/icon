@extends('layouts.admin')

@section('title', 'Reviewer Detail')

@section('header')
    <div class="row">
        <div class="col-sm-6 d-flex align-items-center gap-2">
            <a href="{{ route('admin.reviewers.index') }}" class="btn btn-secondary btn-sm rounded-0">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h1 class="mb-0 fs-3">
                Reviewer Detail
            </h1>
        </div>
        <div class="col-sm-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.reviewers.index') }}">Reviewer</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Detail</li>
                </ol>
            </nav>
        </div>
    </div>
@endsection

@section('content')
    <div class="card rounded-0">
        <div class="card-header">
            <h3 class="card-title">
                Reviewer Information
            </h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-2">
                    <div class="border rounded-0 bg-light p-4 text-center h-100">
                        <div class="rounded-circle bg-primary-subtle d-flex align-items-center justify-content-center mx-auto mb-2"
                            style="width:100px; height:100px;">
                            <i class="bi bi-person-check display-4 text-primary"></i>
                        </div>
                        <h4 class="fw-bold mb-2">
                            {{ $reviewer->user->name }}
                        </h4>
                        <p class="text-muted mb-2">
                            {{ $reviewer->user->email }}
                        </p>
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
                <div class="col-md-8 mb-2">
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <strong>
                                Conference
                            </strong>
                        </div>
                        <div class="col-md-8">
                            @if ($reviewer->conference)
                                <strong>
                                    {{ $reviewer->conference->name }}
                                </strong>
                                <small class="text-muted d-block">
                                    {{ $reviewer->conference->short_name }}
                                    ({{ $reviewer->conference->year }})
                                </small>
                            @else
                                —
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
                            {{ $reviewer->institution ?: '—' }}
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <strong>
                                Expertise
                            </strong>
                        </div>
                        <div class="col-md-8">
                            {{ $reviewer->expertise ?: '—' }}
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
                </div>
            </div>
        </div>
        <div class="card-body border-top">
            <h5 class="fw-bold mb-2">Biography</h5>
            @if ($reviewer->bio)
                <div>{!! nl2br(e($reviewer->bio)) !!}</div>
            @else
                <p class="text-muted mt-3 mb-0">
                    No biography available.
                </p>
            @endif
        </div>
        <div class="card-body border-top">
            <h5 class="fw-bold mb-2">Review History</h5>
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>Submission</th>
                            <th>Score</th>
                            <th>Recommendation</th>
                            <th>Reviewed At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reviewer->reviews as $review)
                            <tr>
                                <td>
                                    @if ($review->submission)
                                        <strong>
                                            {{ $review->submission->submission_code }}
                                        </strong>
                                        <small class="text-muted d-block">
                                            {{ $review->submission->title }}
                                        </small>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>
                                    {{ $review->score ?? '—' }}
                                </td>
                                <td>
                                    {{ ucwords(str_replace('_', ' ', $review->recommendation)) }}
                                </td>
                                <td>
                                    {{ $review->reviewed_at?->format('d M Y H:i') ?? '—' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    No reviews have been completed yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
