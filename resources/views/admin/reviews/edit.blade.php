@extends('layouts.admin')

@section('title', 'Submit Review')

@section('header')

    <div class="row">

        <div class="col-sm-6">

            <div class="d-flex align-items-center gap-2">

                <a href="{{ route('admin.reviews.show', $review) }}" class="btn btn-secondary btn-sm rounded-0" title="Back">
                    <i class="bi bi-arrow-left"></i>
                </a>

                <div>

                    <h1 class="mb-0 fs-3">
                        Submit Review
                    </h1>

                    <p class="text-muted mb-0">
                        Complete the evaluation for this submission.
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
                        <a href="{{ route('admin.reviews.index') }}">
                            Reviews
                        </a>
                    </li>

                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.reviews.show', $review) }}">
                            Review Detail
                        </a>
                    </li>

                    <li class="breadcrumb-item active" aria-current="page">
                        Submit
                    </li>

                </ol>

            </nav>

        </div>

    </div>

@endsection


@section('content')

    @php

        $recommendations = [
            'accept' => 'Accept',
            'minor_revision' => 'Minor Revision',
            'major_revision' => 'Major Revision',
            'reject' => 'Reject',
        ];

    @endphp


    <form action="{{ route('admin.reviews.update', $review) }}" method="POST" id="submit-review-form">

        @csrf
        @method('PUT')


        {{-- ============================================================
            SUBMISSION CONTEXT
        ============================================================= --}}

        <div class="card rounded-0 mb-3">

            <div class="card-header">

                <h3 class="card-title">

                    <i class="bi bi-file-earmark-text me-2"></i>

                    Submission

                </h3>

                <div class="float-end">

                    @if ($review->submission)
                        <a href="{{ route('admin.submissions.show', $review->submission) }}"
                            class="btn btn-info btn-sm rounded-0">
                            <i class="bi bi-eye me-1"></i>
                            View Submission
                        </a>
                    @endif

                </div>

            </div>


            <div class="card-body">

                @if ($review->submission)

                    <div class="row">

                        <div class="col-lg-8 mb-3 mb-lg-0">

                            <div class="text-muted small mb-1">
                                Submission
                            </div>

                            <div class="fw-bold fs-5">
                                {{ $review->submission->submission_code }}
                            </div>

                            <h4 class="fw-bold mt-2 mb-0">
                                {{ $review->submission->title }}
                            </h4>

                        </div>


                        <div class="col-lg-4">

                            <div class="text-muted small mb-1">
                                Topic
                            </div>

                            @if ($review->submission->topic)
                                <span class="badge text-bg-primary rounded-0">
                                    {{ $review->submission->topic->name }}
                                </span>
                            @else
                                <span class="text-muted">
                                    -
                                </span>
                            @endif

                        </div>

                    </div>
                @else
                    <p class="text-muted mb-0">
                        Submission data is no longer available.
                    </p>

                @endif

            </div>

        </div>


        {{-- ============================================================
            REVIEWER INFORMATION
        ============================================================= --}}

        <div class="card rounded-0 mb-3">

            <div class="card-header">

                <h3 class="card-title">

                    <i class="bi bi-person-check me-2"></i>

                    Review Assignment

                </h3>

            </div>


            <div class="card-body">

                <div class="row">

                    <div class="col-md-5 mb-3 mb-md-0">

                        <div class="text-muted small mb-1">
                            Reviewer
                        </div>

                        @if ($review->reviewer?->user)
                            <div class="fw-semibold">
                                {{ $review->reviewer->user->name }}
                            </div>

                            <small class="text-muted">
                                {{ $review->reviewer->user->email }}
                            </small>
                        @else
                            <span class="text-muted">
                                -
                            </span>
                        @endif

                    </div>


                    <div class="col-md-4 mb-3 mb-md-0">

                        <div class="text-muted small mb-1">
                            Institution
                        </div>

                        <div>
                            {{ $review->reviewer?->institution ?: '-' }}
                        </div>

                    </div>


                    <div class="col-md-3">

                        <div class="text-muted small mb-1">
                            Review Round
                        </div>

                        <span class="badge text-bg-secondary rounded-0">
                            Round {{ $review->review_round }}
                        </span>

                    </div>

                </div>

            </div>

        </div>


        {{-- ============================================================
            REVIEW EVALUATION
        ============================================================= --}}

        <div class="card rounded-0">

            <div class="card-header">

                <h3 class="card-title">

                    <i class="bi bi-clipboard-check me-2"></i>

                    Review Evaluation

                </h3>

            </div>


            <div class="card-body">

                <div class="row g-4">

                    {{-- Score --}}
                    <div class="col-md-4">

                        <label for="score" class="form-label">
                            Score
                            <span class="text-danger">*</span>
                        </label>

                        <div class="input-group">

                            <input type="number" name="score" id="score" min="0" max="100" step="0.01"
                                value="{{ old('score', $review->score) }}"
                                class="form-control @error('score') is-invalid @enderror rounded-0" placeholder="0 - 100">

                            <span class="input-group-text rounded-0">
                                / 100
                            </span>

                        </div>

                        <div class="form-text">
                            Enter a score between 0 and 100.
                        </div>

                        @error('score')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Recommendation --}}
                    <div class="col-md-8">

                        <label for="recommendation" class="form-label">
                            Recommendation
                            <span class="text-danger">*</span>
                        </label>

                        <select name="recommendation" id="recommendation"
                            class="form-select @error('recommendation') is-invalid @enderror rounded-0">

                            <option value="">
                                Select Recommendation
                            </option>

                            @foreach ($recommendations as $value => $label)
                                <option value="{{ $value }}" @selected(old('recommendation', $review->recommendation) === $value)>
                                    {{ $label }}
                                </option>
                            @endforeach

                        </select>

                        <div class="form-text">

                            Accept publishes the submission workflow
                            to the accepted stage. Revision requests
                            require the participant to make changes.

                        </div>

                        @error('recommendation')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Comment --}}
                    <div class="col-12">

                        <label for="comment" class="form-label">
                            Review Comment
                            <span class="text-danger">*</span>
                        </label>

                        <textarea name="comment" id="comment" rows="10"
                            class="form-control @error('comment') is-invalid @enderror rounded-0"
                            placeholder="Write detailed feedback for this submission...">{{ old('comment', $review->comment) }}</textarea>

                        <div class="form-text">
                            Provide clear and constructive feedback
                            that can be used by the author.
                        </div>

                        @error('comment')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>

            </div>


            {{-- ========================================================
                FOOTER
            ========================================================= --}}

            <div class="card-footer d-flex justify-content-end gap-2">

                <a href="{{ route('admin.reviews.show', $review) }}" class="btn btn-secondary btn-sm rounded-0">
                    <i class="bi bi-x-circle me-1"></i>
                    Cancel
                </a>

                <button type="submit" class="btn btn-success btn-sm rounded-0">
                    <i class="bi bi-check-circle me-1"></i>
                    Submit Review
                </button>

            </div>

        </div>

    </form>

@endsection


@push('scripts')
    <script>
        document
            .getElementById('submit-review-form')
            ?.addEventListener(
                'submit',
                function(event) {

                    event.preventDefault();

                    const recommendation =
                        document.getElementById(
                            'recommendation'
                        )?.value;

                    const recommendationLabels = {
                        accept: 'Accept',
                        minor_revision: 'Minor Revision',
                        major_revision: 'Major Revision',
                        reject: 'Reject',
                    };

                    const recommendationLabel =
                        recommendationLabels[
                            recommendation
                        ] || 'the selected recommendation';


                    Swal.fire({

                        title: 'Submit Review?',

                        text: 'The review will be saved with the recommendation "' +
                            recommendationLabel +
                            '". The submission status may also be updated.',

                        icon: 'question',

                        showCancelButton: true,

                        confirmButtonText: 'Yes, submit',

                        cancelButtonText: 'Cancel',

                        confirmButtonColor: '#198754',

                        cancelButtonColor: '#6c757d',

                    }).then(function(result) {

                        if (result.isConfirmed) {

                            document
                                .getElementById('submit-review-form')
                                .submit();

                        }

                    });

                }
            );
    </script>
@endpush
