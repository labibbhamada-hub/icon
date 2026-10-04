@extends('layouts.reviewer')

@section('title', 'Review Submission')

@section('header')
    <div class="row align-items-top">
        <div class="col-sm-6">
            <div class="d-flex gap-2">
                <a href="{{ route('reviewer.reviews.index') }}" class="btn btn-secondary rounded-0">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h1 class="mb-0 fs-3">
                    Review Submission
                </h1>
            </div>
            <p class="text-muted mb-0">
                Evaluate the assigned conference submission.
            </p>
        </div>
    </div>
@endsection

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger rounded-0"> <strong>
                Please correct the following: </strong>

            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>
                        {{ $error }}
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('reviewer.reviews.update', $review) }}" method="POST" id="form-submit">
        @csrf
        @method('PUT')

        <div class="card rounded-0 overflow-hidden mb-3">
            <div class="card-header rounded-0">
                <h3 class="card-title">
                    <i class="bi bi-file-earmark-text me-2"></i>
                    Submission Information
                </h3>
            </div>

            <div class="card-body">
                <h4 class="fw-bold mb-2">
                    {{ $review->submission->title }}
                </h4>

                <div>
                    <span class="badge text-bg-primary rounded-0">
                        {{ $review->submission->submission_code }}
                    </span>
                </div>
                <div>
                    <span class="badge text-bg-light border rounded-0">
                        Round {{ $review->review_round }}
                    </span>
                </div>
            </div>

            {{-- Basic Information --}}
            <div class="card-body border-top">

                <div class="row mb-2">

                    <div class="col-md-4">
                        <strong>
                            Conference
                        </strong>
                    </div>

                    <div class="col-md-8">
                        {{ $review->submission->conference?->name ?? '—' }}
                    </div>

                </div>

                <div class="row mb-2">

                    <div class="col-md-4">
                        <strong>
                            Submission Stage
                        </strong>
                    </div>

                    <div class="col-md-8">

                        @if ($review->review_stage === 'abstract')
                            <span class="badge text-bg-secondary rounded-0">
                                Abstract Review
                            </span>
                        @elseif ($review->review_stage === 'full_paper')
                            <span class="badge text-bg-primary rounded-0">
                                Full Paper Review
                            </span>
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
                        {{ $review->submission->topic?->name ?? '—' }}
                    </div>

                </div>

                <div class="row mb-2">

                    <div class="col-md-4">
                        <strong>
                            Submitted At
                        </strong>
                    </div>

                    <div class="col-md-8">
                        {{ $review->submission->submitted_at?->format('d F Y H:i') ?? '—' }}
                    </div>

                </div>

            </div>

            <div class="card-body border-top">
                <h5 class="fw-semibold mb-2">
                    Abstract
                </h5>

                <div>{!! nl2br(e($review->submission->abstract)) !!}</div>
            </div>

            {{-- Keywords --}}
            <div class="card-body border-top">

                <h5 class="fw-semibold mb-2">
                    Keywords
                </h5>

                <p class="mb-2">
                    {{ $review->submission->keywords }}
                </p>

            </div>

            {{-- Authors --}}
            <div class="card-body border-top">

                <h5 class="fw-semibold mb-2">
                    Authors
                </h5>

                <ol class="mb-2">

                    @foreach ($review->submission->authors as $author)
                        <li class="mb-3">

                            <div>
                                <strong>
                                    {{ $author->name }}
                                </strong>

                                @if ($author->is_corresponding)
                                    <span class="badge text-bg-success rounded-0 ms-1">
                                        Corresponding
                                    </span>
                                @endif
                            </div>

                            @if ($author->email)
                                <small class="text-muted d-block">
                                    Email: {{ $author->email }}
                                </small>
                            @endif

                            @if ($author->institution)
                                <small class="text-muted d-block">
                                    Institution: {{ $author->institution }}
                                </small>
                            @endif

                            @if ($author->department)
                                <small class="text-muted d-block">
                                    Department: {{ $author->department }}
                                </small>
                            @endif

                        </li>
                    @endforeach

                </ol>

            </div>

            <div class="card-footer">
                @if ($review->submission?->paper_file)
                    <a href="{{ route('reviewer.reviews.paper.download', $review) }}" target="_blank"
                        class="btn btn-danger rounded-0">
                        <i class="bi bi-file-earmark-pdf me-1"></i>
                        Open Paper
                    </a>
                @endif
            </div>
        </div>

        <div class="card rounded-0 overflow-hidden mb-3">
            <div class="card-header rounded-0">
                <h3 class="card-title">
                    <i class="bi bi-clipboard-check me-2"></i>
                    Evaluation
                </h3>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-2">
                        <label class="form-label">
                            Score
                            <span class="text-danger">*</span>
                        </label>

                        <input type="number" name="score" min="0" max="100" step="0.01"
                            value="{{ old('score', $review->score) }}"
                            class="form-control form-control-lg @error('score') is-invalid @enderror rounded-0"
                            placeholder="0 - 100">

                        <div class="form-text">
                            Enter a score between 0 and 100.
                        </div>

                        @error('score')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-8 mb-2">
                        <label class="form-label">
                            Recommendation
                            <span class="text-danger">*</span>
                        </label>

                        <select name="recommendation"
                            class="form-select form-select-lg @error('recommendation') is-invalid @enderror rounded-0">
                            <option value="">
                                Select Recommendation
                            </option>

                            <option value="accept" @selected(old('recommendation', $review->recommendation) === 'accept')>
                                Accept
                            </option>

                            <option value="minor_revision" @selected(old('recommendation', $review->recommendation) === 'minor_revision')>
                                Minor Revision
                            </option>

                            <option value="major_revision" @selected(old('recommendation', $review->recommendation) === 'major_revision')>
                                Major Revision
                            </option>

                            <option value="reject" @selected(old('recommendation', $review->recommendation) === 'reject')>
                                Reject
                            </option>
                        </select>

                        @error('recommendation')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-12 mb-2">
                        <label class="form-label">
                            Review Comment
                            <span class="text-danger">*</span>
                        </label>

                        <textarea name="comment" rows="10" class="form-control @error('comment') is-invalid @enderror rounded-0"
                            placeholder="Write your evaluation, findings, suggestions, and recommendation...">{{ old('comment', $review->comment) }}</textarea>

                        @error('comment')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="text-end">
            <button type="button" class="btn btn-primary rounded-0" id="btn-submit" onclick="form_submit()">
                <span id="btn-submit-text">
                    <i class="bi bi-check-circle me-1"></i>
                    Submit Review
                </span>
                <span id="btn-submit-load" class="d-none">
                    <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                    Memproses...
                </span>
            </button>
        </div>
    </form>

@endsection

@push('scripts')
    <script>
        function form_submit() {
            const btnSubmit = document.getElementById('btn-submit');
            const btnSubmitText = document.getElementById('btn-submit-text');
            const btnSubmitLoad = document.getElementById('btn-submit-load');
            const formSubmit = document.getElementById('form-submit');

            btnSubmit.disabled = true;

            btnSubmitText.classList.add('d-none');
            btnSubmitLoad.classList.remove('d-none');

            formSubmit.submit();
        }
    </script>
@endpush
