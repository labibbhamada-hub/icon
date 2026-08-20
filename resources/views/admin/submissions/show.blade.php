@extends('layouts.admin')

@section('title', 'Submission Detail')

@section('header')
    <div class="row">
        <div class="col-sm-6 d-flex align-items-center gap-2">
            <a href="{{ route('admin.submissions.index') }}" class="btn btn-secondary btn-sm rounded-0">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h1 class="mb-0 fs-3">
                Submission Detail
            </h1>
        </div>
        <div class="col-sm-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.submissions.index') }}">Submission</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Detail</li>
                </ol>
            </nav>
        </div>
    </div>
@endsection

@section('content')
    <div class="card rounded-0 mb-3">
        <div class="card-header">
            <h3 class="card-title">
                Submission Information
            </h3>
            <div class="float-end">
                <a href="{{ route('admin.submissions.reviews.create', $submission) }}"
                    class="btn btn-success btn-sm rounded-0">
                    <i class="bi bi-person-plus"></i>
                    Assign Reviewer
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="mb-2">
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
                        Revision Required
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
            </div>
            <div class="row">
                <div class="col-lg-8">
                    <div class="mb-4">
                        <h4 class="fw-bold">{{ $submission->title }}</h4>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <strong>
                                Conference
                            </strong>
                        </div>
                        <div class="col-md-8">
                            {{ $submission->conference?->name ?? '—' }}
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <strong>
                                Participant
                            </strong>
                        </div>
                        <div class="col-md-8">
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
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <strong>
                                Topic
                            </strong>
                        </div>
                        <div class="col-md-8">
                            {{ $submission->topic?->name ?? '—' }}
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <strong>
                                Submitted At
                            </strong>
                        </div>
                        <div class="col-md-8">
                            {{ $submission->submitted_at?->format('d F Y H:i') ?? '—' }}
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="border rounded-0 bg-light p-3">
                        <h6 class="fw-bold">
                            Paper File
                        </h6>
                        <p class="text-muted small">
                            Current manuscript file.
                        </p>
                        @if ($submission->paper_file)
                            <a href="{{ asset('storage/' . $submission->paper_file) }}" target="_blank"
                                class="btn btn-danger btn-sm rounded-0">
                                <i class="bi bi-file-earmark-pdf me-1"></i>
                                Open Paper
                            </a>
                        @else
                            <span class="text-muted">
                                No file available.
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body border-top">
            <h5 class="fw-bold mb-2">Abstract</h5>
            <div>{!! nl2br(e($submission->abstract)) !!}</div>
        </div>
        <div class="card-body border-top">
            <h5 class="fw-bold mb-2">Keywords</h5>
            <p>{{ $submission->keywords }}</p>
        </div>
        <div class="card-body border-top">
            <h5 class="fw-bold mb-2">Authors</h5>
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th width="40">No</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Institution</th>
                            <th>Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($submission->authors as $author)
                            <tr>
                                <td class="align-top">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="align-top">
                                    <strong>
                                        {{ $author->name }}
                                    </strong>
                                </td>
                                <td class="align-top">
                                    {{ $author->email ?: '—' }}
                                </td>
                                <td class="align-top">
                                    {{ $author->institution ?: '—' }}
                                    @if ($author->department)
                                        <small class="text-muted d-block">
                                            {{ $author->department }}
                                        </small>
                                    @endif
                                </td>
                                <td class="align-top">
                                    @if ($author->is_corresponding)
                                        <span class="badge text-bg-success rounded-0">
                                            Corresponding Author
                                        </span>
                                    @else
                                        <span class="badge text-bg-secondary rounded-0">
                                            Author
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    No authors available.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-body border-top">
            <h5 class="fw-bold mb-2">Reviewers</h5>
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>Reviewer</th>
                            <th>Status</th>
                            <th>Score</th>
                            <th>Recommendation</th>
                            <th width="80">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($submission->reviews as $review)
                            <tr>
                                <td class="align-top">
                                    @if ($review->reviewer?->user)
                                        <strong>
                                            {{ $review->reviewer->user->name }}
                                        </strong>
                                        <small class="text-muted d-block">
                                            {{ $review->reviewer->institution ?: $review->reviewer->user->email }}
                                        </small>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="align-top">
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
                                <td class="align-top">
                                    {{ $review->score ?? '—' }}
                                </td>
                                <td class="align-top">
                                    @if ($review->recommendation)
                                        {{ ucwords(str_replace('_', ' ', $review->recommendation)) }}
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="align-top">
                                    <div class="btn-group gap-1">
                                        @if ($review->reviewed_at)
                                            <a href="{{ route('admin.reviews.show', $review) }}"
                                                class="btn btn-info btn-sm rounded-0" title="View Review">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        @else
                                            <a href="{{ route('admin.reviews.edit', $review) }}"
                                                class="btn btn-primary btn-sm rounded-0" title="Review">
                                                <i class="bi bi-clipboard-check"></i>
                                            </a>
                                            <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST"
                                                class="d-inline delete-review-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm rounded-0"
                                                    title="Remove Reviewer">
                                                    <i class="bi bi-person-dash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    No reviewers assigned yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="card rounded-0 mb-3">
        <div class="card-header">
            <h3 class="card-title">
                <i class="bi bi-files me-2"></i>
                Submission Files
            </h3>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="border rounded-0 p-3 h-100">
                        <div class="mb-2">
                            <small class="text-muted d-block">Original Paper</small>
                            <strong class="d-block">Manuscript</strong>
                        </div>
                        @if ($submission->paper_file)
                            <a href="{{ asset('storage/' . $submission->paper_file) }}" target="_blank"
                                class="btn btn-outline-danger btn-sm rounded-0">
                                <i class="bi bi-file-earmark-pdf me-1"></i>
                                Open File
                            </a>
                        @else
                            <span class="text-muted d-block">
                                No file
                            </span>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="border rounded-0 p-3 h-100">
                        <div class="mb-2">
                            <small class="text-muted d-block">Revised Paper</small>
                            <strong class="d-block">Revision</strong>
                        </div>
                        @if ($submission->revised_file)
                            <a href="{{ asset('storage/' . $submission->revised_file) }}" target="_blank"
                                class="btn btn-outline-warning btn-sm rounded-0">
                                <i class="bi bi-file-earmark-pdf me-1"></i>
                                Open File
                            </a>
                        @else
                            <span class="text-muted d-block">
                                No revised file
                            </span>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="border rounded-0 p-3 h-100">
                        <div class="mb-2">
                            <small class="text-muted d-block">Camera Ready</small>
                            <strong class="d-block">Final Paper</strong>
                        </div>
                        @if ($submission->camera_ready_file)
                            <a href="{{ asset('storage/' . $submission->camera_ready_file) }}" target="_blank"
                                class="btn btn-outline-success btn-sm rounded-0">
                                <i class="bi bi-file-earmark-check me-1"></i>
                                Open File
                            </a>
                        @else
                            <span class="text-muted d-block">
                                No camera-ready file
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if ($submission->status === 'camera_ready')
        <div class="card rounded-0 mb-3">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="bi bi-file-earmark-check me-2"></i>
                    Camera-Ready Approval
                </h3>
            </div>
            <div class="card-body">
                <div class="alert alert-info rounded-0">
                    <i class="bi bi-info-circle me-2"></i>
                    The participant has uploaded the final camera-ready paper.
                    Please review the file before publishing the submission.
                </div>
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="border rounded-0 p-4 h-100">
                            <small class="text-muted d-block">
                                Camera-Ready File
                            </small>
                            <strong class="d-block mt-1">
                                Final Manuscript
                            </strong>
                            @if ($submission->camera_ready_file)
                                <a href="{{ asset('storage/' . $submission->camera_ready_file) }}" target="_blank"
                                    class="btn btn-outline-danger btn-sm mt-3">
                                    <i class="bi bi-file-earmark-pdf me-1"></i>
                                    Open Camera-Ready PDF
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border rounded-0 p-4 h-100">
                            <small class="text-muted d-block">
                                Approval
                            </small>
                            <div class="mt-3 d-flex flex-wrap gap-2">
                                <form action="{{ route('admin.submissions.camera-ready.approve', $submission) }}"
                                    method="POST" class="approve-camera-ready-form">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-success">
                                        <i class="bi bi-check-circle me-1"></i>
                                        Approve & Publish
                                    </button>
                                </form>
                                <form action="{{ route('admin.submissions.camera-ready.correction', $submission) }}"
                                    method="POST" class="correction-camera-ready-form">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-warning">
                                        <i class="bi bi-arrow-repeat me-1"></i>
                                        Request Correction
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('.delete-review-form').forEach(function(form) {
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Remove Reviewer?',
                    text: 'This will remove the reviewer assignment from this submission.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, remove',
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
    <script>
        document.querySelectorAll('.approve-camera-ready-form')
            .forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    event.preventDefault();
                    Swal.fire({
                        title: 'Approve Camera-Ready Paper?',
                        text: 'The submission will be marked as Published.',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, approve',
                        cancelButtonText: 'Cancel',
                        confirmButtonColor: '#198754',
                        cancelButtonColor: '#6c757d'
                    }).then(function(result) {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });

        document.querySelectorAll('.correction-camera-ready-form')
            .forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    event.preventDefault();
                    Swal.fire({
                        title: 'Request Correction?',
                        text: 'The participant will need to upload the camera-ready file again.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, request correction',
                        cancelButtonText: 'Cancel',
                        confirmButtonColor: '#f0ad4e',
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
