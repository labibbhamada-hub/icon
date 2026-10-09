@extends('layouts.admin')

@section('title', 'Reviews Management')

@section('header')
    <div class="row">
        <div class="col-sm-8">
            <h1 class="mb-0 fs-3">
                Reviews Management
            </h1>
            <p class="text-muted mb-0">
                Manage conference submission reviews and their evaluation results.
            </p>
        </div>
        <div class="col-sm-4">
            <a href="{{ route('admin.reviews.export') }}" class="btn btn-dark float-sm-end rounded-0">
                <i class="bi bi-file-earmark-excel me-1"></i>
                Export Excel
            </a>
        </div>
    </div>
@endsection

@section('content')
    <div class="card rounded-0 overflow-hidden">
        <div class="card-header rounded-0">
            <h3 class="card-title">
                <i class="bi bi-clipboard-check me-2"></i>
                Reviews List
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
                            <th>
                                Reviewer
                            </th>
                            <th width="90">
                                Round
                            </th>
                            <th width="90">
                                Score
                            </th>
                            <th>
                                Recommendation
                            </th>
                            <th width="120">
                                Status
                            </th>
                            <th width="80">
                                Action
                            </th>
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
                                        <div class="fw-semibold">
                                            {{ $review->submission->submission_code }}
                                        </div>
                                        <small class="text-muted d-block">
                                            {{ \Illuminate\Support\Str::limit($review->submission->title, 70) }}
                                        </small>
                                    @else
                                        <span class="text-muted">
                                            -
                                        </span>
                                    @endif
                                </td>
                                <td class="align-top">
                                    @if ($review->reviewer?->user)
                                        <div class="fw-semibold">
                                            {{ $review->reviewer->user->name }}
                                        </div>
                                        <small class="text-muted d-block">
                                            {{ $review->reviewer->user->email }}
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
                                    @php
                                        $recommendations = [
                                            'accept' => 'Accept',
                                            'minor_revision' => 'Minor Revision',
                                            'major_revision' => 'Major Revision',
                                            'reject' => 'Reject',
                                        ];
                                    @endphp
                                    @if ($review->recommendation)
                                        @php
                                            $recommendationClass = match ($review->recommendation) {
                                                'accept' => 'success',
                                                'minor_revision', 'major_revision' => 'warning',
                                                'reject' => 'danger',
                                                default => 'secondary',
                                            };
                                        @endphp
                                        <span class="badge text-bg-{{ $recommendationClass }} rounded-0">
                                            {{ $recommendations[$review->recommendation] ?? ucfirst(str_replace('_', ' ', $review->recommendation)) }}
                                        </span>
                                    @else
                                        <span class="text-muted">
                                            -
                                        </span>
                                    @endif
                                </td>
                                <td class="align-top">
                                    @if ($review->reviewed_at)
                                        <span class="badge text-bg-success rounded-0">
                                            <i class="bi bi-check-circle me-1"></i>
                                            Completed
                                        </span>
                                        <small class="text-muted d-block mt-1">
                                            {{ $review->reviewed_at->format('d M Y') }}
                                        </small>
                                    @else
                                        <span class="badge text-bg-warning rounded-0">
                                            <i class="bi bi-hourglass-split me-1"></i>
                                            Pending
                                        </span>
                                    @endif
                                </td>
                                <td class="align-top">
                                    <a href="{{ route('admin.reviews.show', $review) }}" class="btn btn-info rounded-0"
                                        title="View Review">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="mb-2">
                                        <i class="bi bi-clipboard-check display-5 text-muted"></i>
                                    </div>
                                    <h5 class="mb-1">
                                        No Reviews Found
                                    </h5>
                                    <p class="text-muted mb-0">
                                        No reviews have been assigned or submitted yet.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($reviews->hasPages())
            <div class="card-footer rounded-0">
                {{ $reviews->links() }}
            </div>
        @endif
    </div>
@endsection
