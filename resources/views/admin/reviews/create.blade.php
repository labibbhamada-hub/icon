@extends('layouts.admin')

@section('title', 'Assign Reviewer')

@section('header')

    <div class="row">

        <div class="col-sm-6">

            <div class="d-flex align-items-center gap-2">

                <a href="{{ route('admin.submissions.show', $submission) }}" class="btn btn-secondary btn-sm rounded-0"
                    title="Back">
                    <i class="bi bi-arrow-left"></i>
                </a>

                <div>

                    <h1 class="mb-0 fs-3">
                        Assign Reviewer
                    </h1>

                    <p class="text-muted mb-0">
                        Assign an active reviewer to this submission.
                    </p>

                </div>

            </div>

        </div>


        <div class="col-sm-6">

            <nav aria-label="breadcrumb">

                <ol class="breadcrumb float-sm-end mb-0">

                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">
                            Dashboard
                        </a>
                    </li>

                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.submissions.index') }}">
                            Submissions
                        </a>
                    </li>

                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.submissions.show', $submission) }}">
                            {{ $submission->submission_code }}
                        </a>
                    </li>

                    <li class="breadcrumb-item active" aria-current="page">
                        Assign Reviewer
                    </li>

                </ol>

            </nav>

        </div>

    </div>

@endsection


@section('content')

    <form action="{{ route('admin.submissions.reviews.store', $submission) }}" method="POST" id="assign-reviewer-form">

        @csrf


        <div class="card rounded-0">

            <div class="card-header">

                <h3 class="card-title">

                    <i class="bi bi-person-plus me-2"></i>

                    Reviewer Assignment

                </h3>

            </div>


            <div class="card-body">

                {{-- ====================================================
                    SUBMISSION SUMMARY
                ===================================================== --}}

                <div class="border rounded-0 bg-light p-3 mb-4">

                    <div class="row">

                        <div class="col-lg-8 mb-3 mb-lg-0">

                            <div class="text-muted small mb-1">
                                Submission
                            </div>

                            <div class="fw-bold fs-5">
                                {{ $submission->submission_code }}
                            </div>

                            <div class="mt-1">
                                {{ $submission->title }}
                            </div>

                        </div>


                        <div class="col-lg-4">

                            <div class="text-muted small mb-1">
                                Conference
                            </div>

                            @if ($submission->conference)
                                <div class="fw-semibold">
                                    {{ $submission->conference->name }}
                                </div>

                                <small class="text-muted">
                                    {{ $submission->conference->short_name }}
                                    ({{ $submission->conference->year }})
                                </small>
                            @else
                                <span class="text-muted">
                                    -
                                </span>
                            @endif

                        </div>

                    </div>

                </div>


                {{-- ====================================================
                    REVIEW ROUND
                ===================================================== --}}

                <div class="row mb-3">

                    <div class="col-md-4">

                        <div class="border rounded-0 p-3 h-100">

                            <div class="text-muted small mb-1">
                                Review Round
                            </div>

                            <div>

                                <span class="badge text-bg-secondary rounded-0 fs-6">
                                    Round {{ $currentRound }}
                                </span>

                            </div>

                            <small class="text-muted d-block mt-2">
                                New reviewer assignments will be added
                                to this round.
                            </small>

                        </div>

                    </div>


                    <div class="col-md-8">

                        <div class="border rounded-0 p-3 h-100">

                            <div class="text-muted small mb-1">
                                Available Reviewers
                            </div>

                            <div class="fw-semibold">
                                {{ $reviewers->count() }}
                                active reviewer(s)
                            </div>

                            <small class="text-muted d-block mt-2">
                                Reviewers already assigned in this round
                                are excluded.
                            </small>

                        </div>

                    </div>

                </div>


                {{-- ====================================================
                    REVIEWER SELECT
                ===================================================== --}}

                <div class="mb-0">

                    <label for="reviewer_id" class="form-label">
                        Reviewer
                        <span class="text-danger">*</span>
                    </label>

                    <select name="reviewer_id" id="reviewer_id"
                        class="form-select @error('reviewer_id') is-invalid @enderror rounded-0"
                        @disabled($reviewers->isEmpty())>

                        <option value="">
                            Select Reviewer
                        </option>

                        @foreach ($reviewers as $reviewer)
                            <option value="{{ $reviewer->id }}" @selected(old('reviewer_id') == $reviewer->id)>

                                {{ $reviewer->user?->name ?? 'Unnamed Reviewer' }}

                                @if ($reviewer->institution)
                                    - {{ $reviewer->institution }}
                                @elseif ($reviewer->user?->email)
                                    - {{ $reviewer->user->email }}
                                @endif

                            </option>
                        @endforeach

                    </select>

                    <div class="form-text">
                        Only active reviewers assigned to this conference
                        are available.
                    </div>

                    @error('reviewer_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                @if ($reviewers->isEmpty())
                    <div class="alert alert-warning rounded-0 mt-3 mb-0">

                        <i class="bi bi-exclamation-triangle me-2"></i>

                        <strong>
                            No active reviewers available.
                        </strong>

                        <div class="mt-1">
                            All eligible reviewers may already be assigned
                            in the current round, or there may be no active
                            reviewers for this conference.
                        </div>

                    </div>
                @endif

            </div>


            <div class="card-footer d-flex justify-content-end gap-2">

                <a href="{{ route('admin.submissions.show', $submission) }}" class="btn btn-secondary btn-sm rounded-0">
                    <i class="bi bi-x-circle me-1"></i>
                    Cancel
                </a>

                <button type="submit" class="btn btn-success btn-sm rounded-0" @disabled($reviewers->isEmpty())>
                    <i class="bi bi-person-plus me-1"></i>
                    Assign Reviewer
                </button>

            </div>

        </div>

    </form>

@endsection


@push('scripts')
    <script>
        document
            .getElementById('assign-reviewer-form')
            ?.addEventListener(
                'submit',
                function(event) {

                    event.preventDefault();

                    Swal.fire({

                        title: 'Assign Reviewer?',

                        text: 'The selected reviewer will be assigned to this submission for the current review round.',

                        icon: 'question',

                        showCancelButton: true,

                        confirmButtonText: 'Yes, assign',

                        cancelButtonText: 'Cancel',

                        confirmButtonColor: '#198754',

                        cancelButtonColor: '#6c757d',

                    }).then(function(result) {

                        if (result.isConfirmed) {

                            document
                                .getElementById('assign-reviewer-form')
                                .submit();

                        }

                    });

                }
            );
    </script>
@endpush
