<div class="card-body">
    <div class="row">
        <div class="col-md-4 mb-2">
            <label class="form-label">
                Conference <span class="text-danger">*</span>
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
        <div class="col-md-4 mb-2">
            <label class="form-label">
                Participant / Submitter <span class="text-danger">*</span>
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
                        — {{ $participant->registration_number }}
                    </option>
                @endforeach
            </select>
            @error('participant_id')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="col-md-4 mb-2">
            <label class="form-label">
                Topic <span class="text-danger">*</span>
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
        </div>
    </div>
    <div class="mb-2">
        <label class="form-label">
            Paper Title <span class="text-danger">*</span>
        </label>
        <input type="text" name="title" value="{{ old('title', $submission->title ?? '') }}"
            class="form-control @error('title') is-invalid @enderror rounded-0" placeholder="Enter paper title">
        @error('title')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="mb-2">
        <label class="form-label">
            Abstract <span class="text-danger">*</span>
        </label>
        <textarea name="abstract" rows="7" class="form-control @error('abstract') is-invalid @enderror rounded-0"
            placeholder="Write the paper abstract...">{{ old('abstract', $submission->abstract ?? '') }}</textarea>
        @error('abstract')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="mb-2">
        <label class="form-label">
            Keywords <span class="text-danger">*</span>
        </label>
        <input type="text" name="keywords" value="{{ old('keywords', $submission->keywords ?? '') }}"
            class="form-control @error('keywords') is-invalid @enderror rounded-0"
            placeholder="artificial intelligence, machine learning, education">
        <div class="form-text">
            Separate keywords with commas.
        </div>
        @error('keywords')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="row">
        <div class="col-md-6 mb-2">
            <label class="form-label">
                Paper File
                @if (!isset($submission))
                    <span class="text-danger">*</span>
                @endif
            </label>
            <input type="file" name="paper_file"
                class="form-control @error('paper_file') is-invalid @enderror rounded-0" accept="application/pdf">
            <div class="form-text">
                PDF only. Maximum 10 MB.
            </div>
            @error('paper_file')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
            @if (isset($submission) && $submission->paper_file)
                <div class="mt-2">
                    <a href="{{ asset('storage/' . $submission->paper_file) }}" target="_blank"
                        class="btn btn-sm btn-outline-danger rounded-0">
                        <i class="bi bi-file-earmark-pdf me-1"></i>
                        View Current Paper
                    </a>
                </div>
            @endif
        </div>
        <div class="col-md-3 mb-2">
            <label class="form-label">
                Status <span class="text-danger">*</span>
            </label>
            @php
                $submissionStatuses = [
                    'draft' => 'Draft',
                    'submitted' => 'Submitted',
                    'under_review' => 'Under Review',
                    'revision' => 'Revision',
                    'accepted' => 'Accepted',
                    'rejected' => 'Rejected',
                    'camera_ready' => 'Camera Ready',
                    'published' => 'Published',
                ];
            @endphp
            <select name="status" class="form-select @error('status') is-invalid @enderror rounded-0">
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
        </div>
        <div class="col-md-3 mb-2">
            <label class="form-label">
                Submitted At
            </label>
            <input type="datetime-local" name="submitted_at"
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
<div class="card-body border-top">
    <div class="text-end mb-4">
        <button type="button" id="add-author" class="btn btn-success btn-sm rounded-0">
            <i class="bi bi-plus-circle me-1"></i>
            Add Author
        </button>
    </div>
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
                            Name <span class="text-danger">*</span>
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
                            value="{{ $author['institution'] ?? '' }}" class="form-control rounded-0"
                            placeholder="University / Institution">
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label">
                            Department
                        </label>
                        <input type="text" name="authors[{{ $index }}][department]"
                            value="{{ $author['department'] ?? '' }}" class="form-control rounded-0"
                            placeholder="Department / Faculty">
                    </div>
                    <div class="col-md-6 mb-2">
                        <input type="hidden" name="authors[{{ $index }}][is_corresponding]" value="0">
                        <div class="form-check form-switch mt-2">
                            <input type="checkbox" class="form-check-input"
                                name="authors[{{ $index }}][is_corresponding]" value="1"
                                @checked(!empty($author['is_corresponding']))>
                            <label class="form-check-label">
                                Corresponding Author
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label">
                            Order
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
            const container = document.getElementById('authors-container');
            const addButton = document.getElementById('add-author');
            let authorIndex = {{ count($authors) }};
            const conferenceSelect = document.getElementById('conference_id');
            const participantSelect = document.getElementById('participant_id');
            const topicSelect = document.getElementById('topic_id');

            function filterRelatedOptions() {
                const conferenceId = conferenceSelect.value;
                Array.from(
                    participantSelect.options
                ).forEach(function(option) {
                    if (!option.value) {
                        return;
                    }
                    const belongsToConference = option.dataset.conference === conferenceId;
                    option.hidden = !belongsToConference;
                    if (
                        option.value === participantSelect.value &&
                        !belongsToConference
                    ) {
                        participantSelect.value = '';
                    }
                });

                Array.from(
                    topicSelect.options
                ).forEach(function(option) {
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

            conferenceSelect.addEventListener(
                'change',
                filterRelatedOptions
            );

            filterRelatedOptions();

            addButton.addEventListener('click', function() {
                const authorItem = document.createElement('div');
                authorItem.className = 'author-item border rounded-0 p-3 mb-2';
                authorItem.innerHTML = `
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
                        Name <span class="text-danger">*</span>
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
                        class="form-control rounded-0"
                        placeholder="University / Institution">
                </div>
                <div class="col-md-6 mb-2">
                    <label class="form-label">
                        Department
                    </label>
                    <input
                        type="text"
                        name="authors[${authorIndex}][department]"
                        class="form-control rounded-0"
                        placeholder="Department / Faculty">
                </div>
                <div class="col-md-6 mb-2">
                    <input
                        type="hidden"
                        name="authors[${authorIndex}][is_corresponding]"
                        value="0">
                    <div class="form-check form-switch mt-2">
                        <input
                            type="checkbox"
                            class="form-check-input"
                            name="authors[${authorIndex}][is_corresponding]"
                            value="1">
                        <label class="form-check-label">
                            Corresponding Author
                        </label>
                    </div>
                </div>
                <div class="col-md-6 mb-2">
                    <label class="form-label">
                        Order
                    </label>
                    <input
                        type="number"
                        min="1"
                        name="authors[${authorIndex}][sort_order]"
                        value="${authorIndex + 1}"
                        class="form-control rounded-0">
                </div>
            </div>
        `;

                container.appendChild(authorItem);
                authorIndex++;
            });

            container.addEventListener('click', function(event) {
                const removeButton = event.target.closest('.remove-author');
                if (!removeButton) {
                    return;
                }
                const authorItem = removeButton.closest('.author-item');
                authorItem.remove();
                refreshAuthorLabels();
            });

            function refreshAuthorLabels() {
                const items = container.querySelectorAll('.author-item')
                items.forEach(function(item, index) {
                    const title = item.querySelector('strong');
                    if (title) {
                        title.textContent =
                            `Author ${index + 1}`;
                    }
                });
            }
        });
    </script>
@endpush
