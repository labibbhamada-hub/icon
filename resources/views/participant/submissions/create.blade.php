@extends('layouts.participant')

@section('title', 'Abstract Submission')

@section('header')
    <div class="row align-items-top">
        <div class="col-sm-6">
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('participant.submissions.index') }}" class="btn btn-secondary btn-sm rounded-0">
                    <i class="bi bi-arrow-left"></i>
                </a>

                <h1 class="mb-0 fs-3">
                    Abstract Submission
                </h1>
            </div>

            <p class="text-muted mb-0">
                Submit your conference abstract.
            </p>
        </div>

        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item">
                    <a href="{{ route('participant.dashboard') }}">
                        Dashboard
                    </a>
                </li>

                <li class="breadcrumb-item">
                    <a href="{{ route('participant.submissions.index') }}">
                        My Submissions
                    </a>
                </li>

                <li class="breadcrumb-item active">
                    Create
                </li>
            </ol>
        </div>
    </div>
@endsection

@section('content')

    @if ($errors->any())
        <div class="alert alert-danger rounded-0">
            <strong>
                Please correct the following:
            </strong>

            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('participant.submissions.store') }}" method="POST" enctype="multipart/form-data">

        @csrf

        {{-- Keep participant_id for backend authorization --}}
        <input type="hidden" name="participant_id" value="{{ $participant->id }}">

        {{-- Conference Registration --}}
        <div class="card rounded-0 mb-3">

            <div class="card-header">
                <h3 class="card-title">
                    <i class="bi bi-calendar-check me-2"></i>
                    Conference Registration
                </h3>
            </div>

            <div class="card-body">

                <div class="border rounded-0 p-3 bg-light">

                    <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">

                        <div>
                            <small class="text-muted d-block">
                                Conference
                            </small>

                            <strong class="fs-5">
                                {{ $participant->conference?->name ?? 'Conference' }}
                            </strong>

                            <small class="text-muted d-block mt-1">
                                {{ $participant->conference?->short_name ?? '—' }}

                                @if ($participant->conference?->year)
                                    ({{ $participant->conference->year }})
                                @endif
                            </small>
                        </div>

                        <div class="text-end">

                            <small class="text-muted d-block">
                                Registration
                            </small>

                            <strong>
                                {{ $participant->registration_number }}
                            </strong>

                        </div>

                    </div>

                    <hr>

                    <div class="row">

                        <div class="col-md-6 mb-2">

                            <small class="text-muted d-block">
                                Registration Type
                            </small>

                            <strong>
                                {{ $participant->registrationType?->name ?? ucfirst($participant->participant_type) }}
                            </strong>

                        </div>

                        <div class="col-md-6 mb-2">

                            <small class="text-muted d-block">
                                Attendance
                            </small>

                            <strong>
                                {{ ucfirst($participant->attendance_type) }}
                            </strong>

                        </div>

                    </div>

                </div>

                <div class="alert alert-info rounded-0 mt-3 mb-0">

                    <div class="d-flex align-items-start gap-2">

                        <i class="bi bi-info-circle fs-5"></i>

                        <div>

                            <strong>
                                Submission Registration
                            </strong>

                            <div class="small mt-1">
                                This abstract will be submitted under the conference registration shown above.
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        {{-- Submission Deadline --}}
        @if ($submissionDeadline)
            <div class="card rounded-0 mb-3">

                <div class="card-body">

                    <div class="d-flex align-items-start gap-3">

                        <div class="text-primary fs-4">
                            <i class="bi bi-calendar-event"></i>
                        </div>

                        <div>

                            <small class="text-muted d-block">
                                Submission Deadline
                            </small>

                            <strong>
                                {{ $submissionDeadline->title }}
                            </strong>

                            <div class="mt-1">

                                {{ $submissionDeadline->date->format('d M Y') }}

                                @if ($submissionDeadline->end_date)
                                    –
                                    {{ $submissionDeadline->end_date->format('d M Y') }}
                                @endif

                            </div>

                        </div>

                    </div>

                </div>

            </div>
        @endif

        <div class="card rounded-0 mb-3">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap">

                    <div class="d-flex align-items-start gap-3">

                        <div class="text-primary fs-4">
                            <i class="bi bi-file-earmark-word"></i>
                        </div>

                        <div>
                            <strong>
                                Abstract Template
                            </strong>

                            <div class="text-muted small mt-1">
                                Download the official abstract template before preparing your submission.
                            </div>
                        </div>

                    </div>

                    <a href="{{ asset('storage/conference-templates/' . $participant->conference_id . '/abstract-template.docx') }}"
                        class="btn btn-outline-primary btn-sm rounded-0" target="_blank" rel="noopener">
                        <i class="bi bi-download me-1"></i>
                        Download Template
                    </a>

                </div>

            </div>

        </div>

        {{-- Abstract Information --}}
        <div class="card rounded-0 mb-3">

            <div class="card-header">

                <h3 class="card-title">
                    <i class="bi bi-file-earmark-text me-2"></i>
                    Abstract Information
                </h3>

            </div>

            <div class="card-body">

                <div class="mb-3">

                    <label class="form-label">
                        Topic
                        <span class="text-danger">*</span>
                    </label>

                    <select name="topic_id" class="form-select @error('topic_id') is-invalid @enderror rounded-0">

                        <option value="">
                            Select Topic
                        </option>

                        @foreach ($topics as $topic)
                            <option value="{{ $topic->id }}" @selected(old('topic_id') == $topic->id)>
                                {{ $topic->name }}
                            </option>
                        @endforeach

                    </select>

                    @error('topic_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Paper Title
                        <span class="text-danger">*</span>
                    </label>

                    <input type="text" name="title" value="{{ old('title') }}"
                        class="form-control @error('title') is-invalid @enderror rounded-0"
                        placeholder="Enter the title of your research paper">

                    @error('title')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Abstract
                        <span class="text-danger">*</span>
                    </label>

                    <textarea name="abstract" rows="8" class="form-control @error('abstract') is-invalid @enderror rounded-0"
                        placeholder="Write your abstract (150–250 words)...">{{ old('abstract') }}</textarea>
                    <div class="form-text">
                        Write one paragraph containing 150–250 words.
                    </div>

                    @error('abstract')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Keywords
                        <span class="text-danger">*</span>
                    </label>

                    <input type="text" name="keywords" value="{{ old('keywords') }}"
                        class="form-control @error('keywords') is-invalid @enderror rounded-0"
                        placeholder="artificial intelligence; machine learning; smart campus">

                    <div class="form-text">
                        Separate keywords using semicolons (;). Enter 3–5 keywords.
                    </div>

                    @error('keywords')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

            </div>

        </div>

        {{-- Authors --}}
        <div class="card rounded-0 mb-3">

            <div class="card-header">

                <h3 class="card-title">
                    <i class="bi bi-people me-2"></i>
                    Authors
                </h3>

                <div class="float-end">

                    <button type="button" id="add-author" class="btn btn-success btn-sm rounded-0">

                        <i class="bi bi-plus-circle me-1"></i>
                        Add Author

                    </button>

                </div>

            </div>

            <div class="card-body">

                <div class="alert alert-info rounded-0">

                    <div class="d-flex align-items-start gap-2">

                        <i class="bi bi-info-circle fs-5"></i>

                        <div>
                            <strong>
                                Author Information
                            </strong>
                            <div class="small mt-1">
                                Add all authors who contributed to this paper.
                                Exactly one author must be marked as the corresponding author.
                            </div>
                        </div>
                    </div>
                </div>
                <div id="authors-container">
                    @php
                        $formAuthors = old('authors');
                        if (!$formAuthors) {
                            $formAuthors = [
                                [
                                    'name' => auth()->user()->name,
                                    'email' => auth()->user()->email,
                                    'institution' => '',
                                    'department' => '',
                                    'is_corresponding' => 1,
                                    'sort_order' => 1,
                                ],
                            ];
                        }
                    @endphp
                    @foreach ($formAuthors as $index => $author)
                        <div class="author-item border rounded-0 p-3 mb-2">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <strong>
                                    Author {{ $index + 1 }}
                                </strong>
                                @if ($index > 0)
                                    <button type="button" class="btn btn-outline-danger btn-sm rounded-0 remove-author">
                                        <i class="bi bi-trash"></i>
                                        Remove
                                    </button>
                                @endif
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">
                                        Name
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="authors[{{ $index }}][name]"
                                        value="{{ $author['name'] ?? '' }}" class="form-control rounded-0"
                                        placeholder="Author name">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">
                                        Email
                                    </label>
                                    <input type="email" name="authors[{{ $index }}][email]"
                                        value="{{ $author['email'] ?? '' }}" class="form-control rounded-0"
                                        placeholder="author@example.com">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">
                                        Institution
                                    </label>
                                    <input type="text" name="authors[{{ $index }}][institution]"
                                        value="{{ $author['institution'] ?? '' }}" class="form-control rounded-0">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">
                                        Department
                                    </label>
                                    <input type="text" name="authors[{{ $index }}][department]"
                                        value="{{ $author['department'] ?? '' }}" class="form-control rounded-0">
                                </div>
                                <div class="col-md-4 mb-2">
                                    <input type="hidden" name="authors[{{ $index }}][is_corresponding]"
                                        value="0">
                                    <div class="form-check form-switch mt-2">
                                        <input type="checkbox" name="authors[{{ $index }}][is_corresponding]"
                                            value="1" class="form-check-input" @checked(!empty($author['is_corresponding']))>
                                        <label class="form-check-label">
                                            Corresponding Author
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-2 mb-2">
                                    <label class="form-label">
                                        Order
                                    </label>
                                    <input type="number" name="authors[{{ $index }}][sort_order]"
                                        value="{{ $author['sort_order'] ?? $index + 1 }}" min="1"
                                        class="form-control rounded-0">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>

        </div>

        {{-- Submit --}}
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">

            <div class="text-muted small">

                <i class="bi bi-shield-check me-1"></i>

                Please review your abstract information before submitting.

            </div>

            <button type="submit" class="btn btn-success rounded-0">

                <i class="bi bi-send me-1"></i>
                Submit Abstract

            </button>

        </div>

    </form>
@endsection

@push('styles')
    <style>
        .author-item {
            background: var(--bs-body-bg);
        }

        .author-item:hover {
            border-color: var(--bs-primary) !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container =
                document.getElementById('authors-container');
            const addButton =
                document.getElementById('add-author');
            let authorIndex =
                container.querySelectorAll('.author-item').length;
            addButton.addEventListener('click', function() {
                const wrapper =
                    document.createElement('div');
                wrapper.className =
                    'author-item border rounded-0 p-3 mb-2';
                wrapper.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <strong>
                            Author ${authorIndex + 1}
                        </strong>
                        <button
                            type="button"
                            class="btn btn-outline-danger btn-sm rounded-0 remove-author">
                            <i class="bi bi-trash"></i>
                            Remove
                        </button>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="form-label">
                                Name
                                <span class="text-danger">*</span>
                            </label>
                            <input
                                type="text"
                                name="authors[${authorIndex}][name]"
                                class="form-control rounded-0"
                                placeholder="Author name">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">
                                Email
                            </label>
                            <input
                                type="email"
                                name="authors[${authorIndex}][email]"
                                class="form-control rounded-0"
                                placeholder="author@example.com">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">
                                Institution
                            </label>

                            <input
                                type="text"
                                name="authors[${authorIndex}][institution]"
                                class="form-control rounded-0">
                        </div>

                        <div class="col-md-6 mb-2">
                            <label class="form-label">
                                Department
                            </label>

                            <input
                                type="text"
                                name="authors[${authorIndex}][department]"
                                class="form-control rounded-0">
                        </div>

                        <div class="col-md-4 mb-2">

                            <input
                                type="hidden"
                                name="authors[${authorIndex}][is_corresponding]"
                                value="0">

                            <div class="form-check form-switch mb-2">

                                <input
                                    type="checkbox"
                                    name="authors[${authorIndex}][is_corresponding]"
                                    value="1"
                                    class="form-check-input">

                                <label class="form-check-label">
                                    Corresponding Author
                                </label>

                            </div>

                        </div>

                        <div class="col-md-2 mb-2">

                            <label class="form-label">
                                Order
                            </label>

                            <input
                                type="number"
                                name="authors[${authorIndex}][sort_order]"
                                value="${authorIndex + 1}"
                                min="1"
                                class="form-control rounded-0">

                        </div>

                    </div>
                `;

                container.appendChild(wrapper);

                authorIndex++;

            });

            container.addEventListener(
                'click',
                function(event) {

                    const button =
                        event.target.closest('.remove-author');

                    if (!button) {
                        return;
                    }

                    button.closest('.author-item').remove();

                    refreshLabels();
                }
            );

            function refreshLabels() {

                container
                    .querySelectorAll('.author-item')
                    .forEach(function(item, index) {

                        const title =
                            item.querySelector('strong');

                        if (title) {
                            title.textContent =
                                `Author ${index + 1}`;
                        }

                    });

            }

        });
    </script>
@endpush
