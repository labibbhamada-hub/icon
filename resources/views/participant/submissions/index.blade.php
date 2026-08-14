@extends('layouts.participant')

@section('title', 'My Submissions')

@section('header')
    <div class="row align-items-top">
        <div class="col-sm-6">
            <h1 class="mb-0 fs-3">
                My Submissions
            </h1>
            <p class="text-muted mb-0">
                Manage your conference paper submissions.
            </p>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item">
                    <a href="{{ route('participant.dashboard') }}">
                        Dashboard
                    </a>
                </li>
                <li class="breadcrumb-item active">
                    My Submissions
                </li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="card rounded-0">
        <div class="card-header">
            <h3 class="card-title">
                <i class="bi bi-file-earmark-text me-2"></i>
                Submission List
            </h3>
            <div class="float-end">
                <a href="{{ route('participant.submissions.create') }}" class="btn btn-success btn-sm rounded-0">
                    <i class="bi bi-plus-circle me-1"></i>
                    New Submission
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th width="40">No</th>
                            <th>Submission</th>
                            <th>Conference</th>
                            <th>Topic</th>
                            <th>Status</th>
                            <th>Submitted</th>
                            <th width="40">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($submissions as $submission)
                            <tr>
                                <td class="align-top">
                                    {{ $submissions->firstItem() + $loop->index }}
                                </td>
                                <td class="align-top">
                                    <strong>
                                        {{ $submission->submission_code }}
                                    </strong>
                                    <small class="text-muted d-block">
                                        {{ \Illuminate\Support\Str::limit($submission->title, 70) }}
                                    </small>
                                </td>
                                <td class="align-top">
                                    {{ $submission->conference?->short_name ?? '—' }}
                                    <small class="text-muted d-block">
                                        {{ $submission->conference?->year ?? '' }}
                                    </small>
                                </td>
                                <td class="align-top">
                                    {{ $submission->topic?->name ?? '—' }}
                                </td>
                                <td class="align-top">
                                    @if ($submission->status === 'draft')
                                        <span class="badge text-bg-secondary rounded-0">
                                            Draft
                                        </span>
                                    @elseif ($submission->status === 'submitted')
                                        <span class="badge text-bg-primary rounded-0">
                                            Submitted
                                        </span>
                                    @elseif ($submission->status === 'under_review')
                                        <span class="badge text-bg-warning rounded-0">
                                            Under Review
                                        </span>
                                    @elseif ($submission->status === 'revision')
                                        <span class="badge text-bg-warning rounded-0">
                                            Revision
                                        </span>
                                    @elseif ($submission->status === 'accepted')
                                        <span class="badge text-bg-success rounded-0">
                                            Accepted
                                        </span>
                                    @elseif ($submission->status === 'rejected')
                                        <span class="badge text-bg-danger rounded-0">
                                            Rejected
                                        </span>
                                    @elseif ($submission->status === 'camera_ready')
                                        <span class="badge text-bg-info rounded-0">
                                            Camera Ready
                                        </span>
                                    @elseif ($submission->status === 'published')
                                        <span class="badge text-bg-dark rounded-0">
                                            Published
                                        </span>
                                    @else
                                        <span class="badge text-bg-secondary rounded-0">
                                            Unknown
                                        </span>
                                    @endif
                                </td>
                                <td class="align-top">
                                    {{ $submission->submitted_at?->format('d M Y H:i') ?? '—' }}
                                </td>
                                <td class="align-top">
                                    <a href="{{ route('participant.submissions.show', $submission) }}"
                                        class="btn btn-info btn-sm rounded-0" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="bi bi-file-earmark-x display-5 text-muted"></i>
                                    <h5 class="mt-3">
                                        No Submissions Found
                                    </h5>
                                    <p class="text-muted mb-3">
                                        You have not submitted a paper yet.
                                    </p>
                                    <a href="{{ route('participant.submissions.create') }}"
                                        class="btn btn-success rounded-0">
                                        <i class="bi bi-plus-circle me-1"></i>
                                        New Submission
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($submissions->hasPages())
            <div class="card-footer">
                {{ $submissions->links() }}
            </div>
        @endif
    </div>
@endsection
