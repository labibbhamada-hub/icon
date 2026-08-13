@extends('layouts.admin')

@section('title', 'Reviews Management')

@section('header')
    <div class="row">
        <div class="col-sm-6">
            <h1 class="mb-0 fs-3">Reviews Management</h1>
        </div>
        <div class="col-sm-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Reviews</li>
                </ol>
            </nav>
        </div>
    </div>
@endsection

@section('content')
    <div class="card rounded-0">
        <div class="card-header">
            <h3 class="card-title">
                Reviews List
            </h3>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th width="40">No</th>
                            <th>Submission</th>
                            <th>Reviewer</th>
                            <th>Score</th>
                            <th>Recommendation</th>
                            <th>Status</th>
                            <th width="120">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reviews as $review)
                            <tr>
                                <td>
                                    {{ $reviews->firstItem() + $loop->index }}
                                </td>
                                <td>
                                    @if ($review->submission)
                                        <strong>
                                            {{ $review->submission->submission_code }}
                                        </strong>
                                        <small class="text-muted d-block">
                                            {{ \Illuminate\Support\Str::limit($review->submission->title, 60) }}
                                        </small>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>
                                    @if ($review->reviewer?->user)
                                        <strong>
                                            {{ $review->reviewer->user->name }}
                                        </strong>
                                        <small class="text-muted d-block">
                                            {{ $review->reviewer->user->email }}
                                        </small>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>
                                    {{ $review->score ?? '—' }}
                                </td>
                                <td>
                                    @php
                                        $recommendations = [
                                            'accept' => 'Accept',
                                            'minor_revision' => 'Minor Revision',
                                            'major_revision' => 'Major Revision',
                                            'reject' => 'Reject',
                                        ];
                                    @endphp
                                    <span class="badge text-bg-secondary rounded-0">
                                        {{ $recommendations[$review->recommendation] ?? '—' }}
                                    </span>
                                </td>
                                <td>
                                    @if ($review->reviewed_at)
                                        <span class="badge text-bg-success rounded-0">
                                            Completed
                                        </span>
                                    @else
                                        <span class="badge text-bg-warning rounded-0">
                                            Pending
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.reviews.show', $review) }}"
                                        class="btn btn-info btn-sm rounded-0" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="mb-2">
                                        <i class="bi bi-clipboard-check display-5 text-muted"></i>
                                    </div>
                                    <h5>No Reviews Found</h5>
                                    <p class="text-muted">No review has been submitted yet.</p>
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
