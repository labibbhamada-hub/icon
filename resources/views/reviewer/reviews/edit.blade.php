@extends('layouts.reviewer')

@section('title', 'Review Paper')

@section('header')
    <div class="row align-items-center">
        <div class="col-sm-6">
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('reviewer.reviews.index') }}" class="btn btn-secondary btn-sm rounded-0">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h1 class="mb-0 fs-3">
                    Review Paper
                </h1>
            </div>
            <p class="text-muted mb-0 mt-1">
                Evaluate the assigned conference paper.
            </p>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('reviewer.dashboard') }}">
                        Dashboard
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('reviewer.reviews.index') }}">
                        My Reviews
                    </a>
                </li>
                <li class="breadcrumb-item active">
                    Review
                </li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>
                Please correct the following:
            </strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>
                        {{ $error }}
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('reviewer.reviews.update', $review) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card rounded-0 mb-3">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="bi bi-file-earmark-text me-2"></i>
                    Paper Information
                </h3>
                <div class="float-end">
                    @if ($review->submission?->paper_file)
                        <a href="{{ asset('storage/' . $review->submission->paper_file) }}" target="_blank"
                            class="btn btn-danger btn-sm rounded-0">
                            <i class="bi bi-file-earmark-pdf me-1"></i>
                            Open Paper
                        </a>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <h4 class="fw-bold mb-2">
                    {{ $review->submission->title }}
                </h4>
                <div>
                    <span class="badge text-bg-primary rounded-0">
                        {{ $review->submission->submission_code }}
                    </span>
                    @if ($review->submission->topic)
                        <span class="badge text-bg-secondary rounded-0">
                            {{ $review->submission->topic->name }}
                        </span>
                    @endif
                </div>
            </div>
            <div class="card-body border-top">
                <h5 class="fw-semibold mb-2">
                    Abstract
                </h5>
                <div>{!! nl2br(e($review->submission->abstract)) !!}</div>
            </div>
            <div class="card-body border-top">
                <h5 class="fw-semibold mb-2">
                    Authors
                </h5>
                <ol>
                    @foreach ($review->submission->authors as $author)
                        <li class="mb-2">
                            <strong>
                                {{ $author->name }}
                            </strong>
                            @if ($author->is_corresponding)
                                <span class="badge text-bg-success rounded-0 ms-1">
                                    Corresponding
                                </span>
                            @endif
                            @if ($author->institution)
                                <small class="text-muted d-block">
                                    {{ $author->institution }}
                                </small>
                            @endif
                        </li>
                    @endforeach
                </ol>
            </div>
        </div>
        <div class="card rounded-0 mb-3">
            <div class="card-header">
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
            <div class="card-footer text-end">
                <button type="submit" class="btn btn-primary rounded-0">
                    <i class="bi bi-check-circle me-1"></i>
                    Submit Review
                </button>
            </div>
        </div>
    </form>
@endsection
