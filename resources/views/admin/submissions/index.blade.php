@extends('layouts.admin')

@section('title', 'Submissions Management')

@section('header')

    <div class="row align-items-center">

        <div class="col-sm-6">

            <h1 class="mb-0 fs-3">
                Submissions Management
            </h1>

        </div>

        <div class="col-sm-6">

            <nav aria-label="breadcrumb">

                <ol class="breadcrumb float-sm-end mb-0">

                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">
                            Dashboard
                        </a>
                    </li>

                    <li class="breadcrumb-item active" aria-current="page">
                        Submissions
                    </li>

                </ol>

            </nav>

        </div>

    </div>

@endsection

@section('content')

    <div class="card rounded-3 overflow-hidden">

        <div class="card-header rounded-top-3">

            <h3 class="card-title">
                <i class="bi bi-file-earmark-text me-2"></i>
                Submissions List
            </h3>

            <div class="float-end d-flex gap-1">

                <a href="{{ route('admin.submissions.export') }}" class="btn btn-dark btn-sm rounded-2">
                    <i class="bi bi-file-earmark-excel me-1"></i>
                    Export Excel
                </a>

                <a href="{{ route('admin.submissions.create') }}" class="btn btn-success btn-sm rounded-2">
                    <i class="bi bi-plus-circle me-1"></i>
                    Add Submission
                </a>

            </div>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive rounded-3">

                <table class="table table-hover align-middle mb-0">

                    <thead>

                        <tr>

                            <th width="50">
                                No
                            </th>

                            <th width="150">
                                Submission
                            </th>

                            <th>
                                Title
                            </th>

                            <th>
                                Participant
                            </th>

                            <th>
                                Conference
                            </th>

                            <th>
                                Topic
                            </th>

                            <th>
                                Presenter / Video
                            </th>

                            <th>
                                Status
                            </th>

                            <th width="80">
                                Action
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse ($submissions as $submission)
                            <tr>

                                {{-- No --}}
                                <td class="align-top">

                                    {{ $submissions->firstItem() + $loop->index }}

                                </td>

                                {{-- Submission --}}
                                <td class="align-top">

                                    <div class="fw-semibold">
                                        {{ $submission->submission_code }}
                                    </div>

                                    @if ($submission->submitted_at)
                                        <small class="text-muted d-block">
                                            {{ $submission->submitted_at->format('d M Y H:i') }}
                                        </small>
                                    @else
                                        <small class="text-muted d-block">
                                            Not submitted
                                        </small>
                                    @endif

                                </td>

                                {{-- Title --}}
                                <td class="align-top">

                                    <div class="fw-semibold">

                                        {{ \Illuminate\Support\Str::limit($submission->title, 80) }}

                                    </div>

                                    @if ($submission->keywords)
                                        <small class="text-muted d-block mt-1">

                                            {{ \Illuminate\Support\Str::limit($submission->keywords, 70) }}

                                        </small>
                                    @endif

                                </td>

                                {{-- Participant --}}
                                <td class="align-top">

                                    @if ($submission->participant)
                                        <div class="fw-semibold">
                                            {{ $submission->participant->full_name }}
                                        </div>

                                        <small class="text-muted d-block">
                                            {{ $submission->participant->registration_number }}
                                        </small>
                                    @else
                                        <span class="text-muted">
                                            -
                                        </span>
                                    @endif

                                </td>

                                {{-- Conference --}}
                                <td class="align-top">

                                    @if ($submission->conference)
                                        <strong>
                                            {{ $submission->conference->short_name }}
                                        </strong>

                                        <small class="text-muted d-block">
                                            {{ $submission->conference->year }}
                                        </small>
                                    @else
                                        <span class="text-muted">
                                            -
                                        </span>
                                    @endif

                                </td>

                                {{-- Topic --}}
                                <td class="align-top">

                                    @if ($submission->topic)
                                        <span>
                                            {{ $submission->topic->name }}
                                        </span>
                                    @else
                                        <span class="text-muted">
                                            -
                                        </span>
                                    @endif

                                </td>

                                {{-- Presenter / Video --}}
                                <td class="align-top">

                                    @if ($submission->presenterAuthor)
                                        <div class="fw-semibold">
                                            {{ $submission->presenterAuthor->name }}
                                        </div>
                                    @else
                                        <div class="text-muted">
                                            Presenter not set
                                        </div>
                                    @endif

                                    @if ($submission->video_url)
                                        <a href="{{ $submission->video_url }}" target="_blank" rel="noopener noreferrer"
                                            class="small">
                                            Open Video
                                        </a>
                                    @else
                                        <small class="text-muted d-block">
                                            Video not submitted
                                        </small>
                                    @endif

                                </td>

                                {{-- Status --}}
                                <td class="align-top">

                                    @switch($submission->status)
                                        @case('draft')
                                            <span class="badge text-bg-secondary rounded-pill">
                                                Draft
                                            </span>
                                        @break

                                        @case('submitted')
                                            <span class="badge text-bg-primary rounded-pill">
                                                Submitted
                                            </span>
                                        @break

                                        @case('under_review')
                                            <span class="badge text-bg-warning rounded-pill">
                                                Under Review
                                            </span>
                                        @break

                                        @case('revision')
                                            <span class="badge text-bg-warning rounded-pill">
                                                Revision Required
                                            </span>
                                        @break

                                        @case('accepted')
                                            <span class="badge text-bg-success rounded-pill">
                                                Accepted
                                            </span>
                                        @break

                                        @case('rejected')
                                            <span class="badge text-bg-danger rounded-pill">
                                                Rejected
                                            </span>
                                        @break

                                        @case('camera_ready')
                                            <span class="badge text-bg-info rounded-pill">
                                                Camera Ready
                                            </span>
                                        @break

                                        @case('published')
                                            <span class="badge text-bg-dark rounded-pill">
                                                Published
                                            </span>
                                        @break

                                        @default
                                            <span class="badge text-bg-secondary rounded-pill">
                                                {{ ucfirst(str_replace('_', ' ', $submission->status)) }}
                                            </span>
                                    @endswitch

                                </td>

                                {{-- Action --}}
                                <td class="align-top">

                                    <div class="btn-group gap-1">

                                        <a href="{{ route('admin.submissions.show', $submission) }}"
                                            class="btn btn-info btn-sm rounded-2" title="View Submission">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        @if (!in_array($submission->status, ['published']))
                                            <a href="{{ route('admin.submissions.edit', $submission) }}"
                                                class="btn btn-warning btn-sm rounded-2" title="Edit Submission">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        @endif

                                        @if (!in_array($submission->status, ['published']))
                                            <form action="{{ route('admin.submissions.destroy', $submission) }}"
                                                method="POST" class="d-inline delete-form">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="btn btn-danger btn-sm rounded-2"
                                                    title="Delete Submission">
                                                    <i class="bi bi-trash"></i>
                                                </button>

                                            </form>
                                        @endif

                                    </div>

                                </td>

                            </tr>

                            @empty

                                <tr>

                                    <td colspan="9" class="text-center py-5">

                                        <div class="mb-2">

                                            <i class="bi bi-file-earmark-text display-5 text-muted"></i>

                                        </div>

                                        <h5 class="mb-1">
                                            No Submissions Found
                                        </h5>

                                        <p class="text-muted mb-3">
                                            There are no submissions available yet.
                                        </p>

                                        <a href="{{ route('admin.submissions.create') }}" class="btn btn-success rounded-2">
                                            <i class="bi bi-plus-circle me-1"></i>
                                            Create First Submission
                                        </a>

                                    </td>

                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

            @if ($submissions->hasPages())
                <div class="card-footer rounded-bottom-3">

                    {{ $submissions->links() }}

                </div>
            @endif

        </div>

    @endsection

    @push('scripts')
        <script>
            document
                .querySelectorAll('.delete-form')
                .forEach(function(form) {

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
                            cancelButtonColor: '#6c757d',
                        }).then(function(result) {

                            if (result.isConfirmed) {
                                form.submit();
                            }

                        });

                    });

                });
        </script>
    @endpush
