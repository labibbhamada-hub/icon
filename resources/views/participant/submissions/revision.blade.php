@extends('layouts.participant')

@section('title', $submission->submission_stage === 'abstract' ? 'Revise Abstract' : 'Submit Full Paper Revision')

@section('header')
    <div class="row align-items-top">
        <div class="col-sm-6">
            <div class="d-flex gap-2">
                <a href="{{ route('participant.submissions.show', $submission) }}" class="btn btn-secondary rounded-0">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h1 class="mb-0 fs-3">
                    {{ $submission->submission_stage === 'abstract' ? 'Revise Abstract' : 'Submit Full Paper Revision' }}
                </h1>
            </div>
            <p class="text-muted mb-0">
                {{ $submission->submission_stage === 'abstract'
                    ? 'Review the feedback and update your abstract information.'
                    : 'Review the feedback and upload your revised full paper.' }}
            </p>
        </div>
    </div>
@endsection

@section('content')

    {{-- Reviewer Feedback --}}
    @if (isset($revisionReviews) && $revisionReviews->isNotEmpty())
        <div class="card rounded-0 overflow-hidden mb-3">
            <div class="card-header rounded-0">
                <h3 class="card-title">
                    <i class="bi bi-chat-square-text me-2"></i>
                    Reviewer Feedback
                </h3>
            </div>

            <div class="card-body">
                @foreach ($revisionReviews as $revisionReview)
                    <div class="border rounded-0 p-3 mb-3">
                        <div class="d-flex justify-content-between align-items-start gap-2 flex-wrap">
                            <strong>
                                {{ $revisionReview->reviewer?->user?->name ?? 'Reviewer' }}
                            </strong>

                            <span class="badge text-bg-warning rounded-0">
                                {{ ucwords(str_replace('_', ' ', $revisionReview->recommendation ?? 'Review')) }}
                            </span>
                        </div>

                        @if ($revisionReview->score !== null)
                            <div class="small text-muted mt-2">
                                Score: {{ $revisionReview->score }}
                            </div>
                        @endif

                        <div class="mt-3">
                            <strong>Comments</strong>

                            <div class="mt-1" style="white-space: pre-line;">
                                {{ $revisionReview->comment ?: 'No written comments were provided for this review.' }}
                            </div>
                        </div>

                        @if ($revisionReview->reviewed_at)
                            <div class="small text-muted mt-2">
                                Reviewed:
                                {{ $revisionReview->reviewed_at->format('d M Y H:i') }}
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="alert alert-warning rounded-0">
            <i class="bi bi-exclamation-triangle me-2"></i>
            No completed reviewer feedback is available for this revision yet.
        </div>
    @endif

    <form id="revision-form" action="{{ route('participant.submissions.revision.upload', $submission) }}" method="POST"
        enctype="multipart/form-data">
        @csrf

        {{-- Revision Submission --}}
        <div class="card rounded-0 overflow-hidden mb-3">
            <div class="card-header rounded-0">
                <h3 class="card-title">
                    <i class="bi bi-arrow-repeat me-2"></i>
                    {{ $submission->submission_stage === 'abstract' ? 'Abstract Revision' : 'Full Paper Revision' }}
                </h3>
            </div>

            <div class="card-body">
                <div class="alert alert-warning rounded-0">
                    <i class="bi bi-exclamation-triangle me-2"></i>

                    @if ($submission->submission_stage === 'abstract')
                        Please revise the abstract according to the reviewer feedback below.
                    @else
                        Please upload the revised full paper according to the reviewer feedback below.
                    @endif
                </div>

                @if ($revisionDeadline)
                    <div class="alert alert-info rounded-0">
                        <div class="d-flex align-items-start gap-2">
                            <i class="bi bi-calendar-event fs-5"></i>

                            <div>
                                <strong>{{ $revisionDeadline->title }}</strong>

                                <div class="mt-1">
                                    @if ($revisionDeadline->end_date)
                                        {{ $revisionDeadline->date->format('d M Y') }}
                                        –
                                        {{ $revisionDeadline->end_date->format('d M Y') }}
                                    @else
                                        Deadline:
                                        {{ $revisionDeadline->date->format('d M Y') }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="border rounded-0 p-3 bg-light">
                    <small class="text-muted d-block">Submission</small>
                    <strong>{{ $submission->submission_code }}</strong>
                    <div>{{ $submission->title }}</div>
                    <div class="small text-muted mt-1">
                        Stage:
                        {{ $submission->submission_stage === 'abstract' ? 'Abstract' : 'Full Paper' }}
                    </div>
                </div>
            </div>

            @if ($submission->submission_stage === 'abstract')
                @php
                    $formAuthors = old('authors');

                    if (!$formAuthors) {
                        $formAuthors = $submission->authors
                            ->map(function ($author) {
                                return [
                                    'id' => $author->id,
                                    'title_prefix' => $author->title_prefix,
                                    'name' => $author->name,
                                    'title_suffix' => $author->title_suffix,
                                    'orcid' => $author->orcid,
                                    'email' => $author->email,
                                    'institution' => $author->institution,
                                    'department' => $author->department,
                                    'is_corresponding' => $author->is_corresponding ? 1 : 0,
                                    'sort_order' => $author->sort_order,
                                ];
                            })
                            ->all();
                    }
                @endphp

                {{-- Abstract Information --}}
                <div class="card-body border-top">
                    <h5 class="mb-3">Abstract Information</h5>

                    <div class="mb-3">
                        <label for="revision-title" class="form-label">
                            Paper Title <span class="text-danger">*</span>
                        </label>

                        <input id="revision-title" type="text" name="title"
                            value="{{ old('title', $submission->title) }}"
                            class="form-control rounded-0 @error('title') is-invalid @enderror" maxlength="255" required>

                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="revision-abstract" class="form-label">
                            Abstract <span class="text-danger">*</span>
                        </label>

                        <textarea id="revision-abstract" name="abstract" rows="8"
                            class="form-control rounded-0 @error('abstract') is-invalid @enderror" required>{{ old('abstract', $submission->abstract) }}</textarea>

                        <div class="form-text d-flex justify-content-between gap-2">
                            <span>Maximum 300 words.</span>
                            <span id="revision-word-count" aria-live="polite">0 / 300 words</span>
                        </div>

                        <div id="revision-word-limit-error" class="small text-danger mt-1 d-none" role="alert">
                            The abstract may not exceed 300 words.
                        </div>

                        @error('abstract')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="revision-keywords" class="form-label">
                            Keywords <span class="text-danger">*</span>
                        </label>

                        <input id="revision-keywords" type="text" name="keywords"
                            value="{{ old('keywords', $submission->keywords) }}"
                            class="form-control rounded-0 @error('keywords') is-invalid @enderror" required>

                        <div class="form-text">
                            Separate keywords using semicolons (;).
                        </div>

                        @error('keywords')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Authors --}}
                <div class="card-body border-top">
                    <div class="d-flex justify-content-between align-items-center gap-2 mb-3">
                        <h5 class="mb-0">Authors</h5>

                        <button type="button" id="revision-add-author" class="btn btn-success btn-sm rounded-0">
                            <i class="bi bi-plus-circle me-1"></i>
                            Add Author
                        </button>
                    </div>

                    <div class="alert alert-info rounded-0">
                        Exactly one author must be marked as the corresponding author.
                    </div>

                    <div id="revision-authors-container">
                        @foreach ($formAuthors as $index => $author)
                            <div class="revision-author-item border rounded-0 p-3 mb-2">
                                <input type="hidden" name="authors[{{ $index }}][id]"
                                    value="{{ $author['id'] ?? '' }}">

                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <strong class="revision-author-label">
                                        Author {{ $index + 1 }}
                                    </strong>

                                    @if ($index > 0)
                                        <button type="button"
                                            class="btn btn-outline-danger btn-sm rounded-0 revision-remove-author">
                                            <i class="bi bi-trash"></i>
                                            Remove
                                        </button>
                                    @endif
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label">Name <span class="text-danger">*</span></label>

                                        <input type="text" name="authors[{{ $index }}][name]"
                                            value="{{ $author['name'] ?? '' }}" class="form-control rounded-0" required>
                                    </div>

                                    <div class="col-md-6 mb-2">
                                        <label class="form-label">Email</label>

                                        <input type="email" name="authors[{{ $index }}][email]"
                                            value="{{ $author['email'] ?? '' }}" class="form-control rounded-0">
                                    </div>

                                    <div class="col-md-6 mb-2">
                                        <label class="form-label">Institution</label>

                                        <input type="text" name="authors[{{ $index }}][institution]"
                                            value="{{ $author['institution'] ?? '' }}" class="form-control rounded-0">
                                    </div>

                                    <div class="col-md-6 mb-2">
                                        <label class="form-label">Department</label>

                                        <input type="text" name="authors[{{ $index }}][department]"
                                            value="{{ $author['department'] ?? '' }}" class="form-control rounded-0">
                                    </div>

                                    <div class="col-md-6 mb-2">
                                        <input type="hidden" name="authors[{{ $index }}][is_corresponding]"
                                            value="0">

                                        <div class="form-check form-switch mt-2">
                                            <input type="checkbox" name="authors[{{ $index }}][is_corresponding]"
                                                value="1"
                                                class="form-check-input rounded-0 revision-corresponding-author"
                                                @checked(!empty($author['is_corresponding']))>

                                            <label class="form-check-label">
                                                Corresponding Author
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-2">
                                        <label class="form-label">Order</label>

                                        <input type="number" name="authors[{{ $index }}][sort_order]"
                                            value="{{ $author['sort_order'] ?? $index + 1 }}" min="1"
                                            class="form-control rounded-0">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @error('authors')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror
                </div>
            @else
                {{-- Full Paper Revision --}}
                <div class="card-body border-top">
                    @if ($submission->revised_file)
                        <div class="alert alert-info rounded-0">
                            A previous revised file exists. Uploading a new file will replace it.
                        </div>
                    @endif

                    <div class="mb-2">
                        <label for="revised-file" class="form-label">
                            Revised Paper <span class="text-danger">*</span>
                        </label>

                        <input id="revised-file" type="file" name="revised_file" accept="application/pdf"
                            class="form-control rounded-0 @error('revised_file') is-invalid @enderror" required>

                        <div class="form-text">PDF only. Maximum 10 MB.</div>

                        @error('revised_file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            @endif

        </div>

        <div class="text-end">
            <button type="submit" id="revision-submit-button" class="btn btn-success rounded-0">

                <span id="revision-submit-text">
                    @if ($submission->submission_stage === 'abstract')
                        <i class="bi bi-send me-1"></i>
                        Submit Abstract Revision
                    @else
                        <i class="bi bi-upload me-1"></i>
                        Upload Revision
                    @endif
                </span>

                <span id="revision-submit-loading" class="d-none">
                    <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                    Processing...
                </span>
            </button>
        </div>

    </form>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('revision-form');
            const submitButton = document.getElementById('revision-submit-button');
            const submitText = document.getElementById('revision-submit-text');
            const submitLoading = document.getElementById('revision-submit-loading');

            const abstractField = document.getElementById('revision-abstract');
            const wordCountElement = document.getElementById('revision-word-count');
            const wordLimitError = document.getElementById('revision-word-limit-error');

            const MAX_WORDS = 300;

            function countWords(value) {
                const trimmed = value.trim();

                return trimmed === '' ?
                    0 :
                    trimmed.split(/[\s\p{Z}]+/u).length;
            }

            function updateWordCount() {
                if (!abstractField) {
                    return 0;
                }

                const count = countWords(abstractField.value);
                const exceedsLimit = count > MAX_WORDS;

                if (wordCountElement) {
                    wordCountElement.textContent = `${count} / ${MAX_WORDS} words`;
                    wordCountElement.classList.toggle('text-danger', exceedsLimit);
                    wordCountElement.classList.toggle('text-body-secondary', !exceedsLimit);
                }

                if (wordLimitError) {
                    wordLimitError.classList.toggle('d-none', !exceedsLimit);
                }

                abstractField.classList.toggle('border-danger', exceedsLimit);

                return count;
            }

            if (abstractField) {
                abstractField.addEventListener('input', updateWordCount);
                updateWordCount();
            }

            if (form) {
                form.addEventListener('submit', function(event) {
                    if (abstractField && updateWordCount() > MAX_WORDS) {
                        event.preventDefault();
                        abstractField.focus();
                        return;
                    }

                    if (submitButton) {
                        submitButton.disabled = true;
                    }

                    if (submitText) {
                        submitText.classList.add('d-none');
                    }

                    if (submitLoading) {
                        submitLoading.classList.remove('d-none');
                    }
                });
            }

            const authorsContainer = document.getElementById('revision-authors-container');
            const addAuthorButton = document.getElementById('revision-add-author');

            if (!authorsContainer || !addAuthorButton) {
                return;
            }

            let authorIndex = authorsContainer.querySelectorAll('.revision-author-item').length;

            function refreshAuthorLabels() {
                authorsContainer.querySelectorAll('.revision-author-item').forEach(function(item, index) {
                    const label = item.querySelector('.revision-author-label');

                    if (label) {
                        label.textContent = `Author ${index + 1}`;
                    }

                    const removeButton = item.querySelector('.revision-remove-author');

                    if (removeButton) {
                        removeButton.disabled =
                            authorsContainer.querySelectorAll('.revision-author-item').length <= 1;
                    }
                });
            }

            authorsContainer.addEventListener('change', function(event) {
                if (!(event.target instanceof Element)) {
                    return;
                }

                const checkbox = event.target.closest('.revision-corresponding-author');

                if (!checkbox) {
                    return;
                }

                if (checkbox.checked) {
                    authorsContainer
                        .querySelectorAll('.revision-corresponding-author')
                        .forEach(function(other) {
                            if (other !== checkbox) {
                                other.checked = false;
                            }
                        });
                } else if (!authorsContainer.querySelector('.revision-corresponding-author:checked')) {
                    checkbox.checked = true;
                }
            });

            addAuthorButton.addEventListener('click', function() {
                const index = authorIndex++;
                const item = document.createElement('div');

                item.className = 'revision-author-item border rounded-0 p-3 mb-2';

                item.innerHTML = `
                    <input type="hidden" name="authors[${index}][id]" value="">

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <strong class="revision-author-label">Author ${index + 1}</strong>
                        <button type="button" class="btn btn-outline-danger btn-sm rounded-0 revision-remove-author">
                            <i class="bi bi-trash"></i> Remove
                        </button>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" name="authors[${index}][name]" class="form-control rounded-0" required>
                        </div>

                        <div class="col-md-6 mb-2">
                            <label class="form-label">Email</label>
                            <input type="email" name="authors[${index}][email]" class="form-control rounded-0">
                        </div>

                        <div class="col-md-6 mb-2">
                            <label class="form-label">Institution</label>
                            <input type="text" name="authors[${index}][institution]" class="form-control rounded-0">
                        </div>

                        <div class="col-md-6 mb-2">
                            <label class="form-label">Department</label>
                            <input type="text" name="authors[${index}][department]" class="form-control rounded-0">
                        </div>

                        <div class="col-md-6 mb-2">
                            <input type="hidden" name="authors[${index}][is_corresponding]" value="0">
                            <div class="form-check form-switch mt-2">
                                <input type="checkbox"
                                    name="authors[${index}][is_corresponding]"
                                    value="1"
                                    class="form-check-input rounded-0 revision-corresponding-author">
                                <label class="form-check-label">Corresponding Author</label>
                            </div>
                        </div>

                        <div class="col-md-6 mb-2">
                            <label class="form-label">Order</label>
                            <input type="number"
                                name="authors[${index}][sort_order]"
                                value="${index + 1}"
                                min="1"
                                class="form-control rounded-0">
                        </div>
                    </div>
                `;

                authorsContainer.appendChild(item);
                refreshAuthorLabels();
            });

            authorsContainer.addEventListener('click', function(event) {
                if (!(event.target instanceof Element)) {
                    return;
                }

                const removeButton = event.target.closest('.revision-remove-author');

                if (!removeButton) {
                    return;
                }

                const items = authorsContainer.querySelectorAll('.revision-author-item');

                if (items.length <= 1) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Author Required',
                            text: 'At least one author is required.',
                            icon: 'warning',
                            confirmButtonText: 'OK'
                        });
                    }

                    return;
                }

                const item = removeButton.closest('.revision-author-item');

                if (item) {
                    item.remove();
                    refreshAuthorLabels();
                }
            });

            refreshAuthorLabels();
        });
    </script>
@endpush
