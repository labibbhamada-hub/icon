@extends('layouts.admin')

@section('title', 'Assign Reviewer')

@section('header')
    <div class="row align-items-top">
        <div class="col-sm-6">
            <div class="d-flex gap-2">
                <a href="{{ route('admin.submissions.show', $submission) }}" class="btn btn-secondary rounded-0">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h1 class="mb-0 fs-3">
                    Assign Reviewer
                </h1>
            </div>
            <p class="text-muted mb-0">
                Assign an active reviewer to this submission.
            </p>
        </div>
    </div>
@endsection

@section('content')

    <form action="{{ route('admin.submissions.reviews.store', $submission) }}" method="POST" id="assign-reviewer-form">

        @csrf


        <div class="card rounded-0 overflow-hidden">

            <div class="card-header rounded-0">

                <h3 class="card-title">

                    <i class="bi bi-person-plus me-2"></i>

                    Reviewer Assignment

                </h3>

            </div>


            <div class="card-body">

                {{-- ====================================================
                    SUBMISSION SUMMARY
                ===================================================== --}}

                <div class="border rounded-0 bg-light p-3 mb-2">

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

                <div class="row mb-2">

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

            </div>

            <div class="card-body border-top">
                {{-- ====================================================
                    REVIEWER SELECT
                ===================================================== --}}

                <div class="mb-2">

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
                    <div class="alert alert-warning rounded-0 mt-2 mb-0">

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

            <div class="card-footer text-end rounded-0">
                <button type="submit" class="btn btn-success rounded-0" @disabled($reviewers->isEmpty())>
                    <i class="bi bi-person-plus me-1"></i>
                    Assign Reviewer
                </button>

            </div>

        </div>

    </form>

    {{-- Confirm Reviewer Assignment Modal --}}
    <div class="modal fade" id="confirm-assign-reviewer-modal" tabindex="-1"
        aria-labelledby="confirm-assign-reviewer-modal-label" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content rounded-0">

                <div class="modal-header rounded-0">
                    <h5 class="modal-title" id="confirm-assign-reviewer-modal-label">
                        <i class="bi bi-person-check me-2"></i>
                        Confirm Reviewer Assignment
                    </h5>

                    <button type="button" class="btn-close rounded-0" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>

                <div class="modal-body">
                    The selected reviewer will be assigned to this submission
                    for the current review round.
                </div>

                <div class="modal-footer rounded-0">
                    <button type="button" class="btn btn-secondary rounded-0" id="cancel-assign-reviewer"
                        data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="button" class="btn btn-success rounded-0" id="confirm-assign-reviewer">

                        <span id="confirm-assign-reviewer-text">
                            <i class="bi bi-person-plus me-1"></i>
                            Confirm Assignment
                        </span>

                        <span id="confirm-assign-reviewer-loading" class="d-none">
                            <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                            Assigning...
                        </span>

                    </button>
                </div>

            </div>

        </div>

    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('assign-reviewer-form');

            const modalElement = document.getElementById(
                'confirm-assign-reviewer-modal'
            );

            const confirmButton = document.getElementById(
                'confirm-assign-reviewer'
            );

            const confirmButtonText = document.getElementById(
                'confirm-assign-reviewer-text'
            );

            const confirmButtonLoading = document.getElementById(
                'confirm-assign-reviewer-loading'
            );

            const cancelButton = document.getElementById(
                'cancel-assign-reviewer'
            );

            if (
                !form ||
                !modalElement ||
                !confirmButton ||
                !confirmButtonText ||
                !confirmButtonLoading ||
                !cancelButton
            ) {
                return;
            }

            if (
                typeof bootstrap === 'undefined' ||
                !bootstrap.Modal
            ) {
                console.error('Bootstrap Modal is not available.');
                return;
            }

            const modal = bootstrap.Modal.getOrCreateInstance(
                modalElement
            );

            let confirmationApproved = false;

            form.addEventListener('submit', function(event) {
                if (confirmationApproved) {
                    confirmationApproved = false;
                    return;
                }

                event.preventDefault();
                modal.show();
            });

            confirmButton.addEventListener('click', function() {
                if (confirmButton.disabled) {
                    return;
                }

                if (!form.reportValidity()) {
                    confirmationApproved = false;
                    modal.hide();
                    return;
                }

                confirmationApproved = true;

                confirmButton.disabled = true;
                cancelButton.disabled = true;

                confirmButtonText.classList.add('d-none');
                confirmButtonLoading.classList.remove('d-none');

                if (typeof form.requestSubmit === 'function') {
                    form.requestSubmit();
                } else {
                    form.submit();
                }
            });
        });
    </script>
@endpush