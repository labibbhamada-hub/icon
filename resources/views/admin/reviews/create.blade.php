@extends('layouts.admin')

@section('title', 'Assign Reviewer')

@section('header')
    <div class="row">
        <div class="col-sm-6 d-flex align-items-center gap-2">
            <a href="{{ route('admin.reviewers.index') }}" class="btn btn-secondary btn-sm rounded-0">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h1 class="mb-0 fs-3">
                Assign Reviewer
            </h1>
        </div>
        <div class="col-sm-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.reviewers.index') }}">Reviewer</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Create</li>
                </ol>
            </nav>
        </div>
    </div>
@endsection

@section('content')
    <form action="{{ route('admin.submissions.reviews.store', $submission) }}" method="POST">
        @csrf
        <div class="card rounded-0">
            <div class="card-header">
                <h3 class="card-title">
                    Reviewer Assignment
                </h3>
            </div>
            <div class="card-body">
                <div class="border rounded-0 bg-light p-3 mb-2">
                    <div class="row">
                        <div class="col-md-8 mb-2">
                            <small class="text-muted">Submission</small>
                            <h5 class="fw-bold">{{ $submission->submission_code }}</h5>
                            <div>{{ $submission->title }}</div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <small class="text-muted">Conference</small>
                            <div class="fw-semibold">{{ $submission->conference->name }}</div>
                        </div>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-8 mb-2">
                        <label class="form-label">
                            Reviewer
                            <span class="text-danger">*</span>
                        </label>
                        <select name="reviewer_id" class="form-select @error('reviewer_id') is-invalid @enderror rounded-0">
                            <option value="">
                                Select Reviewer
                            </option>
                            @foreach ($reviewers as $reviewer)
                                <option value="{{ $reviewer->id }}" @selected(old('reviewer_id') == $reviewer->id)>
                                    {{ $reviewer->user->name }}
                                    —
                                    {{ $reviewer->institution ?: $reviewer->user->email }}
                                </option>
                            @endforeach
                        </select>
                        @error('reviewer_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                @if ($reviewers->isEmpty())
                    <div class="alert alert-warning rounded-0">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        No active reviewers are available for this
                        conference.
                    </div>
                @endif
            </div>
            <div class="card-footer text-end">
                <a href="{{ route('admin.submissions.show', $submission) }}" class="btn btn-secondary btn-sm rounded-0">
                    Cancel
                </a>
                <button type="submit" class="btn btn-success btn-sm rounded-0" @disabled($reviewers->isEmpty())>
                    <i class="bi bi-person-plus"></i>
                    Assign Reviewer
                </button>
            </div>
        </div>
    </form>
@endsection
