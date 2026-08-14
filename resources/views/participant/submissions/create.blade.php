@extends('layouts.participant')

@section('title', 'New Submission')

@section('header')
    <div class="row align-items-top">
        <div class="col-sm-6">
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('participant.submissions.index') }}" class="btn btn-secondary btn-sm rounded-0">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h1 class="mb-0 fs-3">
                    New Submission
                </h1>
            </div>
            <p class="text-muted mb-0">
                Submit your conference paper.
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
        <div class="card rounded-0 mb-3">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="bi bi-calendar-check me-2"></i>
                    Conference Registration
                </h3>
            </div>
            <div class="card-body">
                <label class="form-label">
                    Confirmed Registration
                    <span class="text-danger">*</span>
                </label>
                <select name="participant_id" id="participant_id"
                    class="form-select @error('participant_id') is-invalid @enderror rounded-0">
                    <option value="">Select Registration</option>
                    @foreach ($participants as $participant)
                        <option value="{{ $participant->id }}" data-conference="{{ $participant->conference_id }}"
                            @selected(old('participant_id') == $participant->id)>
                            {{ $participant->conference->name }}
                            —
                            {{ $participant->registration_number }}
                        </option>
                    @endforeach
                </select>
                <div class="form-text">
                    Only confirmed registrations with submissions currently open are available.
                </div>
                @error('participant_id')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
        <div class="card rounded-0 mb-3">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="bi bi-file-earmark-text me-2"></i>
                    Paper Information
                </h3>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <label class="form-label">
                        Topic
                        <span class="text-danger">*</span>
                    </label>
                    <select name="topic_id" id="topic_id"
                        class="form-select @error('topic_id') is-invalid @enderror rounded-0">
                        <option value="">Select Topic</option>
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
                <div class="mb-2">
                    <label class="form-label">
                        Paper Title
                        <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="title" value="{{ old('title') }}"
                        class="form-control @error('title') is-invalid @enderror rounded-0" placeholder="Enter paper title">
                    @error('title')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-2">
                    <label class="form-label">
                        Abstract
                        <span class="text-danger">*</span>
                    </label>
                    <textarea name="abstract" rows="8" class="form-control @error('abstract') is-invalid @enderror rounded-0"
                        placeholder="Write your abstract...">{{ old('abstract') }}</textarea>
                    @error('abstract')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-2">
                    <label class="form-label">
                        Keywords
                        <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="keywords" value="{{ old('keywords') }}"
                        class="form-control @error('keywords') is-invalid @enderror rounded-0"
                        placeholder="artificial intelligence, machine learning, smart campus">
                    <div class="form-text">
                        Separate keywords using commas.
                    </div>
                    @error('keywords')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div>
                    <label class="form-label">
                        Paper File
                        <span class="text-danger">*</span>
                    </label>
                    <input type="file" name="paper_file" accept="application/pdf"
                        class="form-control @error('paper_file') is-invalid @enderror">
                    <div class="form-text">
                        PDF only. Maximum 10 MB.
                    </div>
                    @error('paper_file')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="card rounded-0 mt-3">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="bi bi-people me-2"></i>
                    Authors
                </h3>
                <div class="card-tools">
                    <button type="button" id="add-author" class="btn btn-success btn-sm">
                        <i class="bi bi-plus-circle me-1"></i>
                        Add Author
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div id="authors-container">
                    <div class="author-item border rounded-0 p-3 mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <strong>
                                Author 1
                            </strong>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">
                                    Name
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="authors[0][name]"
                                    value="{{ old('authors.0.name', auth()->user()->name) }}" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">
                                    Email
                                </label>
                                <input type="email" name="authors[0][email]"
                                    value="{{ old('authors.0.email', auth()->user()->email) }}" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">
                                    Institution
                                </label>
                                <input type="text" name="authors[0][institution]"
                                    value="{{ old('authors.0.institution') }}" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">
                                    Department
                                </label>
                                <input type="text" name="authors[0][department]"
                                    value="{{ old('authors.0.department') }}" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <input type="hidden" name="authors[0][is_corresponding]" value="0">
                                <div class="form-check form-switch mt-2">
                                    <input type="checkbox" name="authors[0][is_corresponding]" value="1"
                                        class="form-check-input" checked>
                                    <label class="form-check-label">
                                        Corresponding Author
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">
                                    Order
                                </label>
                                <input type="number" name="authors[0][sort_order]" value="1" min="1"
                                    class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card rounded-0 mt-3">
            <div class="card-body text-end">
                <a href="{{ route('participant.submissions.index') }}" class="btn btn-secondary">
                    Cancel
                </a>
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-send me-1"></i>
                    Submit Paper
                </button>
            </div>
        </div>
    </form>
@endsection


@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const container =
                document.getElementById('authors-container');

            const addButton =
                document.getElementById('add-author');

            let authorIndex = 1;


            addButton.addEventListener('click', function() {

                const wrapper =
                    document.createElement('div');

                wrapper.className =
                    'author-item border rounded-0 p-3 mb-3';


                wrapper.innerHTML = `

                <div class="d-flex justify-content-between align-items-center mb-3">

                    <strong>
                        Author ${authorIndex + 1}
                    </strong>

                    <button
                        type="button"
                        class="btn btn-outline-danger btn-sm remove-author">

                        <i class="bi bi-trash"></i>

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
                            class="form-control"
                            placeholder="Author name">

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Email
                        </label>

                        <input
                            type="email"
                            name="authors[${authorIndex}][email]"
                            class="form-control"
                            placeholder="author@example.com">

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Institution
                        </label>

                        <input
                            type="text"
                            name="authors[${authorIndex}][institution]"
                            class="form-control">

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Department
                        </label>

                        <input
                            type="text"
                            name="authors[${authorIndex}][department]"
                            class="form-control">

                    </div>


                    <div class="col-md-4">

                        <input
                            type="hidden"
                            name="authors[${authorIndex}][is_corresponding]"
                            value="0">

                        <div class="form-check form-switch mt-2">

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


                    <div class="col-md-2">

                        <label class="form-label">
                            Order
                        </label>

                        <input
                            type="number"
                            name="authors[${authorIndex}][sort_order]"
                            value="${authorIndex + 1}"
                            min="1"
                            class="form-control">

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
