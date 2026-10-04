@extends('layouts.admin')

@section('title', 'Submissions Management')

@section('header')
    <div class="row">
        <div class="col-sm-6">
            <h1 class="mb-0 fs-3">
                Submissions Management
            </h1>
            <p class="text-muted mb-0">
                Review, manage, and monitor conference paper submissions.
            </p>
        </div>
    </div>
@endsection

@section('content')

    <div class="card rounded-0 overflow-hidden">

        <div class="card-header rounded-0">

            <h3 class="card-title">
                <i class="bi bi-file-earmark-text me-2"></i>
                Submissions List
            </h3>

            <div class="float-end d-flex gap-1">

                <a href="{{ route('admin.submissions.export') }}" class="btn btn-dark rounded-0">

                    <i class="bi bi-file-earmark-excel me-1"></i>
                    Export Excel

                </a>

                <a href="{{ route('admin.submissions.create') }}" class="btn btn-success rounded-0">

                    <i class="bi bi-plus-circle me-1"></i>
                    Add Submission

                </a>

            </div>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive rounded-0">

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

                                {{-- Status --}}
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
                                    @elseif ($submission->status === 'published')
                                        <span class="badge text-bg-dark rounded-0">
                                            Published
                                        </span>
                                    @else
                                        <span class="badge text-bg-secondary rounded-0">
                                            {{ ucfirst(str_replace('_', ' ', $submission->status)) }}
                                        </span>
                                    @endif

                                </td>

                                {{-- Action --}}
                                <td class="align-top">

                                    <div class="btn-group gap-1">

                                        <a href="{{ route('admin.submissions.show', $submission) }}"
                                            class="btn btn-info rounded-0" title="View Submission">

                                            <i class="bi bi-eye"></i>

                                        </a>

                                        @if (!in_array($submission->status, ['published']))
                                            <a href="{{ route('admin.submissions.edit', $submission) }}"
                                                class="btn btn-warning rounded-0" title="Edit Submission">

                                                <i class="bi bi-pencil"></i>

                                            </a>
                                        @endif

                                        @if (!in_array($submission->status, ['published']))
                                            <form action="{{ route('admin.submissions.destroy', $submission) }}"
                                                method="POST" class="d-inline delete-form">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="btn btn-danger rounded-0"
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

                                <td colspan="6" class="text-center py-5">

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
            <div class="card-footer rounded-0">

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
