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

    <div class="card rounded-0">

        <div class="card-header">

            <h3 class="card-title">
                <i class="bi bi-file-earmark-text me-2"></i>
                Submissions List
            </h3>

            <div class="float-end d-flex gap-1">

                <a href="{{ route('admin.submissions.export') }}" class="btn btn-dark btn-sm rounded-0">
                    <i class="bi bi-file-earmark-excel me-1"></i>
                    Export Excel
                </a>

                <a href="{{ route('admin.submissions.create') }}" class="btn btn-success btn-sm rounded-0">
                    <i class="bi bi-plus-circle me-1"></i>
                    Add Submission
                </a>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

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
                                Presentation
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


                                {{-- Presentation --}}
                                <td class="align-top">

                                    @if ($submission->presentation_type)
                                        <div class="fw-semibold">

                                            {{ ucfirst($submission->presentation_type) }}

                                        </div>
                                    @else
                                        <div class="text-muted">
                                            Not set
                                        </div>
                                    @endif

                                    @if ($submission->presentation_mode)
                                        <small class="text-muted d-block">

                                            {{ ucfirst($submission->presentation_mode) }}

                                        </small>
                                    @endif

                                </td>


                                {{-- Status --}}
                                <td class="align-top">

                                    @switch($submission->status)
                                        @case('draft')
                                            <span class="badge text-bg-secondary rounded-0">
                                                Draft
                                            </span>
                                        @break

                                        @case('submitted')
                                            <span class="badge text-bg-primary rounded-0">
                                                Submitted
                                            </span>
                                        @break

                                        @case('under_review')
                                            <span class="badge text-bg-warning rounded-0">
                                                Under Review
                                            </span>
                                        @break

                                        @case('revision')
                                            <span class="badge text-bg-warning rounded-0">
                                                Revision Required
                                            </span>
                                        @break

                                        @case('accepted')
                                            <span class="badge text-bg-success rounded-0">
                                                Accepted
                                            </span>
                                        @break

                                        @case('rejected')
                                            <span class="badge text-bg-danger rounded-0">
                                                Rejected
                                            </span>
                                        @break

                                        @case('camera_ready')
                                            <span class="badge text-bg-info rounded-0">
                                                Camera Ready
                                            </span>
                                        @break

                                        @case('published')
                                            <span class="badge text-bg-dark rounded-0">
                                                Published
                                            </span>
                                        @break

                                        @default
                                            <span class="badge text-bg-secondary rounded-0">
                                                {{ ucfirst(str_replace('_', ' ', $submission->status)) }}
                                            </span>
                                    @endswitch

                                </td>


                                {{-- Action --}}
                                <td class="align-top">

                                    <div class="btn-group gap-1">

                                        <a href="{{ route('admin.submissions.show', $submission) }}"
                                            class="btn btn-info btn-sm rounded-0" title="View Submission">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        @if (!in_array($submission->status, ['published']))
                                            <a href="{{ route('admin.submissions.edit', $submission) }}"
                                                class="btn btn-warning btn-sm rounded-0" title="Edit Submission">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        @endif

                                        @if (!in_array($submission->status, ['published']))
                                            <form action="{{ route('admin.submissions.destroy', $submission) }}"
                                                method="POST" class="d-inline delete-form">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="btn btn-danger btn-sm rounded-0"
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

                                        <a href="{{ route('admin.submissions.create') }}" class="btn btn-success rounded-0">
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
                <div class="card-footer">

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
