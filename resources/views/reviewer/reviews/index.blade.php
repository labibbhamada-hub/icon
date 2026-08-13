@extends('layouts.reviewer')

@section('title', 'My Reviews')

@section('header')
    <div class="row">
        <div class="col-sm-6">
            <h1 class="mb-0 fs-3">My Reviews</h1>
        </div>
        <div class="col-sm-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item">
                        <a href="{{ route('reviewer.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">My Reviews</li>
                </ol>
            </nav>
        </div>
    </div>
@endsection

@section('content')
    <div class="card rounded-0">
        <div class="card-header">
            <h3 class="card-title">
                <i class="bi bi-clipboard-check me-2"></i>
                Assigned Papers
            </h3>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th width="40">No</th>
                            <th>Submission</th>
                            <th>Topic</th>
                            <th>Conference</th>
                            <th>Status</th>
                            <th width="40" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reviews as $review)
                            <tr>
                                <td class="align-top">
                                    {{ $reviews->firstItem() + $loop->index }}
                                </td>
                                <td class="align-top">
                                    @if ($review->submission)
                                        <strong>
                                            {{ $review->submission->submission_code }}
                                        </strong>
                                        <small class="text-muted d-block">
                                            {{ \Illuminate\Support\Str::limit($review->submission->title, 70) }}
                                        </small>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="align-top">
                                    {{ $review->submission?->topic?->name ?? '—' }}
                                </td>
                                <td class="align-top">
                                    {{ $review->submission?->conference?->short_name ?? '—' }}
                                    <small class="text-muted d-block">
                                        {{ $review->submission?->conference?->year ?? '' }}
                                    </small>
                                </td>
                                <td class="align-top">
                                    @if ($review->reviewed_at)
                                        <span class="badge text-bg-success rounded-0">Completed</span>
                                    @else
                                        <span class="badge text-bg-warning rounded-0">Pending</span>
                                    @endif
                                </td>
                                <td class="align-top">
                                    @if ($review->reviewed_at)
                                        <a href="{{ route('reviewer.reviews.show', $review) }}"
                                            class="btn btn-info btn-sm rounded-0" title="View Review">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    @else
                                        <a href="{{ route('reviewer.reviews.edit', $review) }}"
                                            class="btn btn-primary btn-sm rounded-0" title="Review Paper">
                                            <i class="bi bi-clipboard-check"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="bi bi-clipboard-x display-5 text-muted"></i>
                                    <h5 class="mt-3">
                                        No Review Assignments
                                    </h5>
                                    <p class="text-muted mb-0">
                                        You currently have no papers assigned for review.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($reviews->hasPages())
            <div class="card-footer">
                {{ $reviews->links() }}
            </div>
        @endif
    </div>
@endsection
