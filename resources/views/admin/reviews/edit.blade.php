@extends('layouts.admin')

@section('title', 'Submit Review')

@section('header')
    <div class="row">
        <div class="col-sm-6 d-flex align-items-center gap-2">
            <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary btn-sm rounded-0">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h1 class="mb-0 fs-3">
                Submit Review
            </h1>
        </div>
        <div class="col-sm-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.reviews.index') }}">Reviews</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Review</li>
                </ol>
            </nav>
        </div>
    </div>
@endsection

@section('content')
    <form action="{{ route('admin.reviews.update', $review) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card rounded-0">
            <div class="card-header">
                <h3 class="card-title">
                    Paper Review
                </h3>
            </div>
            <div class="card-body">
                <div class="border rounded-0 bg-light p-3">
                    <div class="row">
                        <div class="col-md-8 mb-2">
                            <small class="text-muted">Submission</small>
                            <h5 class="fw-bold">{{ $review->submission->submission_code }}</h5>
                            <div>{{ $review->submission->title }}</div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <small class="text-muted">Reviewer</small>
                            <div class="fw-semibold">{{ $review->reviewer->user->name }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body border-top">
                <div class="mb-2">
                    <label class="form-label">
                        Score
                        <span class="text-danger">*</span>
                    </label>
                    <input type="number" name="score" min="0" max="100" step="0.01"
                        value="{{ old('score', $review->score) }}"
                        class="form-control @error('score') is-invalid @enderror rounded-0" placeholder="0 - 100">
                    <div class="form-text">
                        Enter a score between 0 and 100.
                    </div>
                    @error('score')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-2">
                    <label class="form-label">
                        Recommendation
                        <span class="text-danger">*</span>
                    </label>
                    <select name="recommendation"
                        class="form-select @error('recommendation') is-invalid @enderror rounded-0">
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
                <div class="mb-2">
                    <label class="form-label">
                        Review Comment
                        <span class="text-danger">*</span>
                    </label>
                    <textarea name="comment" rows="8" class="form-control @error('comment') is-invalid @enderror rounded-0"
                        placeholder="Write your review comments...">{{ old('comment', $review->comment) }}</textarea>
                    @error('comment')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            <div class="card-footer text-end">
                <a href="{{ route('admin.reviews.show', $review) }}" class="btn btn-secondary btn-sm rounded-0">
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary btn-sm rounded-0">
                    <i class="bi bi-check-circle me-1"></i>
                    Submit Review
                </button>
            </div>
        </div>
    </form>
@endsection
