@extends('layouts.admin')

@section('title', 'Submissions Management')

@section('header')
    <div class="row">
        <div class="col-sm-6">
            <h1 class="mb-0 fs-3">Submissions Management</h1>
        </div>
        <div class="col-sm-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Submissions</li>
                </ol>
            </nav>
        </div>
    </div>
@endsection

@section('content')
    <div class="card rounded-0">
        <div class="card-header">
            <h3 class="card-title">
                Submissions List
            </h3>
            <div class="float-end">
                <a href="{{ route('admin.submissions.create') }}" class="btn btn-success btn-sm rounded-0">
                    <i class="bi bi-plus-circle"></i>
                    Add Submission
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
                            <th>Title</th>
                            <th>Participant</th>
                            <th>Topic</th>
                            <th>Status</th>
                            <th width="120">Action</th>
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
                                    @if ($submission->submitted_at)
                                        <small class="text-muted d-block">
                                            {{ $submission->submitted_at->format('d M Y H:i') }}
                                        </small>
                                    @endif
                                </td>
                                <td class="align-top">
                                    <div class="fw-semibold">
                                        {{ $submission->title }}
                                    </div>
                                    @if ($submission->keywords)
                                        <small class="text-muted">
                                            {{ \Illuminate\Support\Str::limit($submission->keywords, 60) }}
                                        </small>
                                    @endif
                                </td>
                                <td class="align-top">
                                    @if ($submission->participant)
                                        <strong>
                                            {{ $submission->participant->full_name }}
                                        </strong>
                                        <small class="text-muted d-block">
                                            {{ $submission->participant->registration_number }}
                                        </small>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="align-top">
                                    @if ($submission->topic)
                                        {{ $submission->topic->name }}
                                    @else
                                        —
                                    @endif
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
                                    @else
                                        <span class="badge text-bg-dark rounded-0">
                                            Published
                                        </span>
                                    @endif
                                </td>
                                <td class="align-top">
                                    <div class="btn-group gap-1">
                                        <a href="{{ route('admin.submissions.show', $submission) }}"
                                            class="btn btn-info btn-sm rounded-0" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.submissions.edit', $submission) }}"
                                            class="btn btn-warning btn-sm rounded-0" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.submissions.destroy', $submission) }}" method="POST"
                                            class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm rounded-0" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="bi bi-file-earmark-text display-5 text-muted"></i>
                                    <h5 class="mt-3">
                                        No Submissions Found
                                    </h5>
                                    <p class="text-muted mb-3">
                                        There are no submissions available yet.
                                    </p>
                                    <a href="{{ route('admin.submissions.create') }}" class="btn btn-success rounded-0">
                                        <i class="bi bi-plus-circle me-1"></i>
                                        Add Submission
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

@push('scripts')
    <script>
        document.querySelectorAll('.delete-form').forEach(function(form) {
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Delete Submission?',
                    text: 'This action cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d'
                }).then(function(result) {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
