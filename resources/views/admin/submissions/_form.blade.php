{{-- ============================================================
    BASIC SUBMISSION INFORMATION
============================================================= --}}

<div class="card-body">

    <div class="row g-3">

        {{-- Conference --}}
        <div class="col-md-4">

            <label for="conference_id" class="form-label">
                Conference
                <span class="text-danger">*</span>
            </label>

            <select name="conference_id" id="conference_id"
                class="form-select @error('conference_id') is-invalid @enderror rounded-0">

                <option value="">
                    Select Conference
                </option>

                @foreach ($conferences as $conference)
                    <option value="{{ $conference->id }}" @selected(old('conference_id', $submission->conference_id ?? '') == $conference->id)>
                        {{ $conference->short_name }}
                        ({{ $conference->year }})
                    </option>
                @endforeach

            </select>

            @error('conference_id')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>


        {{-- Participant --}}
        <div class="col-md-4">

            <label for="participant_id" class="form-label">
                Participant / Submitter
                <span class="text-danger">*</span>
            </label>

            <select name="participant_id" id="participant_id"
                class="form-select @error('participant_id') is-invalid @enderror rounded-0">

                <option value="">
                    Select Participant
                </option>

                @foreach ($participants as $participant)
                    <option value="{{ $participant->id }}" data-conference="{{ $participant->conference_id }}"
                        @selected(old('participant_id', $submission->participant_id ?? '') == $participant->id)>
                        {{ $participant->full_name }}
                        - {{ $participant->registration_number }}
                    </option>
                @endforeach

            </select>

            @error('participant_id')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

            <div class="form-text">
                Only participants from the selected conference can be selected.
            </div>

        </div>


        {{-- Topic --}}
        <div class="col-md-4">

            <label for="topic_id" class="form-label">
                Topic
                <span class="text-danger">*</span>
            </label>

            <select name="topic_id" id="topic_id"
                class="form-select @error('topic_id') is-invalid @enderror rounded-0">

                <option value="">
                    Select Topic
                </option>

                @foreach ($topics as $topic)
                    <option value="{{ $topic->id }}" data-conference="{{ $topic->conference_id }}"
                        @selected(old('topic_id', $submission->topic_id ?? '') == $topic->id)>
                        {{ $topic->name }}
                    </option>
                @endforeach

            </select>

            @error('topic_id')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

            <div class="form-text">
                Only topics from the selected conference are available.
            </div>

        </div>

    </div>

</div>


{{-- ============================================================
    MANUSCRIPT INFORMATION
============================================================= --}}

<div class="card-body border-top">

    <div class="mb-3">

        <label for="title" class="form-label">
            Paper Title
            <span class="text-danger">*</span>
        </label>

        <input type="text" name="title" id="title" value="{{ old('title', $submission->title ?? '') }}"
            class="form-control @error('title') is-invalid @enderror rounded-0"
            placeholder="Enter the full paper title">

        @error('title')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    <div class="mb-3">

        <label for="abstract" class="form-label">
            Abstract
            <span class="text-danger">*</span>
        </label>

        <textarea name="abstract" id="abstract" rows="8"
            class="form-control @error('abstract') is-invalid @enderror rounded-0"
            placeholder="Write or paste the paper abstract...">{{ old('abstract', $submission->abstract ?? '') }}</textarea>

        @error('abstract')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    <div class="mb-0">

        <label for="keywords" class="form-label">
            Keywords
            <span class="text-danger">*</span>
        </label>

        <input type="text" name="keywords" id="keywords" value="{{ old('keywords', $submission->keywords ?? '') }}"
            class="form-control @error('keywords') is-invalid @enderror rounded-0"
            placeholder="artificial intelligence, smart campus, technology">

        <div class="form-text">
            Separate keywords with commas.
        </div>

        @error('keywords')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>

</div>


{{-- ============================================================
    PAPER + STATUS
============================================================= --}}

<div class="card-body border-top">

    <div class="row g-3">

        {{-- Paper File --}}
        <div class="col-lg-6">

            <label for="paper_file" class="form-label">
                Paper File

                @if (!isset($submission))
                    <span class="text-danger">*</span>
                @endif

            </label>

            <input type="file" name="paper_file" id="paper_file"
                class="form-control @error('paper_file') is-invalid @enderror rounded-0" accept="application/pdf">

            <div class="form-text">
                PDF only. Maximum file size: 10 MB.
            </div>

            @error('paper_file')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror


            @if (isset($submission) && $submission->paper_file)
                <div class="alert alert-light border rounded-0 mt-3 mb-0">

                    <div class="d-flex align-items-center">

                        <i class="bi bi-file-earmark-pdf text-danger fs-4 me-2"></i>

                        <div>

                            <strong>
                                Current Paper
                            </strong>

                            <small class="text-muted d-block">
                                A paper file is already stored for this submission.
                            </small>

                        </div>

                    </div>

                    <a href="{{ route('admin.submissions.paper.download', $submission) }}"
                        class="btn btn-outline-danger btn-sm rounded-0 mt-3">
                        <i class="bi bi-download me-1"></i>
                        Download Current Paper
                    </a>

                </div>
            @endif

        </div>


        {{-- Status --}}
        <div class="col-md-6">

            <label for="status" class="form-label">
                Submission Status
                <span class="text-danger">*</span>
            </label>

            @php

                $submissionStatuses = [
                    'draft' => 'Draft',

                    'submitted' => 'Submitted',

                    'under_review' => 'Under Review',

                    'revision' => 'Revision Required',

                    'accepted' => 'Accepted',

                    'rejected' => 'Rejected',

                    'camera_ready' => 'Camera Ready',

                    'published' => 'Published',
                ];

            @endphp

            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror rounded-0">

                @foreach ($submissionStatuses as $value => $label)
                    <option value="{{ $value }}" @selected(old('status', $submission->status ?? 'draft') === $value)>
                        {{ $label }}
                    </option>
                @endforeach

            </select>

            @error('status')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

            <div class="form-text">
                Use the current workflow status of this submission.
            </div>

        </div>


        {{-- Submitted At --}}
        <div class="col-md-6">

            <label for="submitted_at" class="form-label">
                Submitted At
            </label>

            <input type="datetime-local" name="submitted_at" id="submitted_at"
                value="{{ old('submitted_at', isset($submission->submitted_at) ? $submission->submitted_at->format('Y-m-d\TH:i') : '') }}"
                class="form-control @error('submitted_at') is-invalid @enderror rounded-0">

            @error('submitted_at')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>

    </div>

</div>


{{-- ============================================================
    AUTHORS
============================================================= --}}

<div class="card-body border-top">

    <div class="d-flex justify-content-between align-items-center mb-3">

        <div>

            <h5 class="mb-1 fw-semibold">
                Authors
            </h5>

            <p class="text-muted mb-0 small">
                Add all authors and select exactly one corresponding author.
            </p>

        </div>

        <button type="button" id="add-author" class="btn btn-success btn-sm rounded-0">
            <i class="bi bi-plus-circle me-1"></i>
            Add Author
        </button>

    </div>


    @error('authors')
        <div class="alert alert-danger rounded-0">
            {{ $message }}
        </div>
    @enderror


    <div id="authors-container">

        @php

            $authors = old(
                'authors',

                isset($submission)
                    ? $submission->authors->toArray()
                    : [
                        [
                            'name' => '',
                            'email' => '',
                            'institution' => '',
                            'department' => '',
                            'is_corresponding' => true,
                            'sort_order' => 1,
                        ],
                    ],
            );

        @endphp


        @foreach ($authors as $index => $author)
            <div class="author-item border rounded-0 p-3 mb-3" data-author-index="{{ $index }}">

                <div class="d-flex justify-content-between align-items-center mb-3">

                    <div class="d-flex align-items-center gap-2">

                        <span class="badge text-bg-secondary rounded-0 author-number">
                            Author {{ $index + 1 }}
                        </span>

                    </div>


                    @if ($index > 0)
                        <button type="button" class="btn btn-outline-danger btn-sm rounded-0 remove-author">
                            <i class="bi bi-trash me-1"></i>
                            Remove
                        </button>
                    @endif

                </div>


                <div class="row g-3">

                    {{-- Name --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Name
                            <span class="text-danger">*</span>
                        </label>

                        <input type="text" name="authors[{{ $index }}][name]"
                            value="{{ $author['name'] ?? '' }}" class="form-control rounded-0"
                            placeholder="Author full name">

                    </div>


                    {{-- Email --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Email
                        </label>

                        <input type="email" name="authors[{{ $index }}][email]"
                            value="{{ $author['email'] ?? '' }}" class="form-control rounded-0"
                            placeholder="author@example.com">

                    </div>


                    {{-- Institution --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Institution
                        </label>

                        <input type="text" name="authors[{{ $index }}][institution]"
                            value="{{ $author['institution'] ?? '' }}" class="form-control rounded-0"
                            placeholder="University / Institution">

                    </div>


                    {{-- Department --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Department
                        </label>

                        <input type="text" name="authors[{{ $index }}][department]"
                            value="{{ $author['department'] ?? '' }}" class="form-control rounded-0"
                            placeholder="Department / Faculty">

                    </div>


                    {{-- Corresponding Author --}}
                    <div class="col-md-6">

                        <div class="border rounded-0 p-3 h-100">

                            <input type="hidden" name="authors[{{ $index }}][is_corresponding]"
                                value="0">

                            <div class="form-check form-switch">

                                <input type="checkbox" class="form-check-input corresponding-author"
                                    id="corresponding_{{ $index }}"
                                    name="authors[{{ $index }}][is_corresponding]" value="1"
                                    @checked(!empty($author['is_corresponding']))>

                                <label class="form-check-label fw-semibold" for="corresponding_{{ $index }}">
                                    Corresponding Author
                                </label>

                            </div>

                            <small class="text-muted d-block mt-1">
                                Exactly one author must be selected.
                            </small>

                        </div>

                    </div>


                    {{-- Order --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Author Order
                        </label>

                        <input type="number" min="1" name="authors[{{ $index }}][sort_order]"
                            value="{{ $author['sort_order'] ?? $index + 1 }}" class="form-control rounded-0">

                    </div>

                </div>

            </div>
        @endforeach

    </div>

</div>


@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const container =
                document.getElementById('authors-container');

            const addButton =
                document.getElementById('add-author');

            const conferenceSelect =
                document.getElementById('conference_id');

            const participantSelect =
                document.getElementById('participant_id');

            const topicSelect =
                document.getElementById('topic_id');


            let authorIndex =
                {{ count($authors) }};


            /*
            |--------------------------------------------------------------------------
            | Filter Participant & Topic by Conference
            |--------------------------------------------------------------------------
            */

            function filterRelatedOptions() {

                const conferenceId =
                    conferenceSelect.value;


                if (participantSelect) {

                    Array
                        .from(participantSelect.options)
                        .forEach(function(option) {

                            if (!option.value) {
                                return;
                            }

                            const belongsToConference =
                                option.dataset.conference === conferenceId;

                            option.hidden = !belongsToConference;


                            if (
                                option.value === participantSelect.value &&
                                !belongsToConference
                            ) {

                                participantSelect.value = '';

                            }

                        });

                }


                if (topicSelect) {

                    Array
                        .from(topicSelect.options)
                        .forEach(function(option) {

                            if (!option.value) {
                                return;
                            }

                            const belongsToConference =
                                option.dataset.conference === conferenceId;

                            option.hidden = !belongsToConference;


                            if (
                                option.value === topicSelect.value &&
                                !belongsToConference
                            ) {

                                topicSelect.value = '';

                            }

                        });

                }

            }


            if (conferenceSelect) {

                conferenceSelect.addEventListener(
                    'change',
                    filterRelatedOptions
                );

                filterRelatedOptions();

            }


            /*
            |--------------------------------------------------------------------------
            | Corresponding Author
            |--------------------------------------------------------------------------
            */

            function bindCorrespondingAuthor() {

                const checkboxes =
                    container.querySelectorAll(
                        '.corresponding-author'
                    );


                checkboxes.forEach(function(checkbox) {

                    checkbox.addEventListener(
                        'change',
                        function() {

                            if (!this.checked) {

                                const checkedCount =
                                    container.querySelectorAll(
                                        '.corresponding-author:checked'
                                    ).length;

                                if (checkedCount === 0) {
                                    this.checked = true;
                                }

                                return;

                            }


                            checkboxes.forEach(function(other) {

                                if (other !== checkbox) {
                                    other.checked = false;
                                }

                            });

                        }
                    );

                });

            }


            bindCorrespondingAuthor();


            /*
            |--------------------------------------------------------------------------
            | Add Author
            |--------------------------------------------------------------------------
            */

            addButton.addEventListener(
                'click',
                function() {

                    const authorItem =
                        document.createElement('div');


                    authorItem.className =
                        'author-item border rounded-0 p-3 mb-3';


                    authorItem.dataset.authorIndex =
                        authorIndex;


                    authorItem.innerHTML = `

                    <div class="d-flex justify-content-between align-items-center mb-3">

                        <div>

                            <span class="badge text-bg-secondary rounded-0 author-number">
                                Author ${authorIndex + 1}
                            </span>

                        </div>

                        <button
                            type="button"
                            class="btn btn-outline-danger btn-sm rounded-0 remove-author"
                        >
                            <i class="bi bi-trash me-1"></i>
                            Remove
                        </button>

                    </div>


                    <div class="row g-3">


                        <div class="col-md-6">

                            <label class="form-label">
                                Name
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                name="authors[${authorIndex}][name]"
                                class="form-control rounded-0"
                                placeholder="Author full name"
                            >

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Email
                            </label>

                            <input
                                type="email"
                                name="authors[${authorIndex}][email]"
                                class="form-control rounded-0"
                                placeholder="author@example.com"
                            >

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Institution
                            </label>

                            <input
                                type="text"
                                name="authors[${authorIndex}][institution]"
                                class="form-control rounded-0"
                                placeholder="University / Institution"
                            >

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Department
                            </label>

                            <input
                                type="text"
                                name="authors[${authorIndex}][department]"
                                class="form-control rounded-0"
                                placeholder="Department / Faculty"
                            >

                        </div>


                        <div class="col-md-6">

                            <div class="border rounded-0 p-3 h-100">

                                <input
                                    type="hidden"
                                    name="authors[${authorIndex}][is_corresponding]"
                                    value="0"
                                >

                                <div class="form-check form-switch">

                                    <input
                                        type="checkbox"
                                        class="form-check-input corresponding-author"
                                        id="corresponding_${authorIndex}"
                                        name="authors[${authorIndex}][is_corresponding]"
                                        value="1"
                                    >

                                    <label
                                        class="form-check-label fw-semibold"
                                        for="corresponding_${authorIndex}"
                                    >
                                        Corresponding Author
                                    </label>

                                </div>

                                <small class="text-muted d-block mt-1">
                                    Exactly one author must be selected.
                                </small>

                            </div>

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Author Order
                            </label>

                            <input
                                type="number"
                                min="1"
                                name="authors[${authorIndex}][sort_order]"
                                value="${authorIndex + 1}"
                                class="form-control rounded-0"
                            >

                        </div>

                    </div>

                `;


                    container.appendChild(
                        authorItem
                    );


                    const newCheckbox =
                        authorItem.querySelector(
                            '.corresponding-author'
                        );


                    newCheckbox.addEventListener(
                        'change',
                        function() {

                            if (!this.checked) {

                                const checkedCount =
                                    container.querySelectorAll(
                                        '.corresponding-author:checked'
                                    ).length;

                                if (checkedCount === 0) {
                                    this.checked = true;
                                }

                                return;

                            }


                            container
                                .querySelectorAll(
                                    '.corresponding-author'
                                )
                                .forEach(function(other) {

                                    if (other !== newCheckbox) {
                                        other.checked = false;
                                    }

                                });

                        }
                    );


                    authorIndex++;


                    refreshAuthorLabels();

                }
            );


            /*
            |--------------------------------------------------------------------------
            | Remove Author
            |--------------------------------------------------------------------------
            */

            container.addEventListener(
                'click',
                function(event) {

                    const removeButton =
                        event.target.closest(
                            '.remove-author'
                        );


                    if (!removeButton) {
                        return;
                    }


                    const authorItems =
                        container.querySelectorAll(
                            '.author-item'
                        );


                    if (authorItems.length <= 1) {

                        Swal.fire({
                            title: 'Author Required',
                            text: 'At least one author is required.',
                            icon: 'warning',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#198754',
                        });

                        return;

                    }


                    const authorItem =
                        removeButton.closest(
                            '.author-item'
                        );


                    const wasCorresponding =
                        authorItem.querySelector(
                            '.corresponding-author'
                        )?.checked;


                    authorItem.remove();


                    refreshAuthorLabels();


                    if (
                        wasCorresponding &&
                        !container.querySelector(
                            '.corresponding-author:checked'
                        )
                    ) {

                        const firstCheckbox =
                            container.querySelector(
                                '.corresponding-author'
                            );

                        if (firstCheckbox) {
                            firstCheckbox.checked = true;
                        }

                    }

                }
            );


            /*
            |--------------------------------------------------------------------------
            | Refresh Author Labels
            |--------------------------------------------------------------------------
            */

            function refreshAuthorLabels() {

                const items =
                    container.querySelectorAll(
                        '.author-item'
                    );


                items.forEach(function(item, index) {

                    const numberBadge =
                        item.querySelector(
                            '.author-number'
                        );


                    if (numberBadge) {

                        numberBadge.textContent =
                            `Author ${index + 1}`;

                    }

                });

            }


            refreshAuthorLabels();

        });
    </script>
@endpush
