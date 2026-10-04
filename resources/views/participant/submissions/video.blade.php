@extends('layouts.participant')

@section('title', 'Submit Presentation Detail')

@section('header')
    <div class="row align-items-top">
        <div class="col-sm-6">
            <div class="d-flex gap-2">
                <a href="{{ route('participant.submissions.show', $submission) }}" class="btn btn-secondary rounded-0">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h1 class="mb-0 fs-3">
                    Submit Presentation Detail
                </h1>
            </div>
            <p class="text-muted mb-0">
                Submit your presentation video using a Google Drive link.
            </p>
        </div>
    </div>
@endsection

@section('content')

    {{-- Accepted Paper --}}
    <div class="card rounded-0 overflow-hidden mb-3">

        <div class="card-header rounded-0">

            <h3 class="card-title">

                <i class="bi bi-file-earmark-check me-2"></i>

                Accepted Paper

            </h3>

        </div>

        <div class="card-body">

            <div class="alert alert-success rounded-0">

                <i class="bi bi-check-circle me-2"></i>

                Your full paper has been accepted.
                Please submit the presentation video using your own Google Drive link.

            </div>

            <div class="border rounded-0 p-3 bg-light">

                <small class="text-muted d-block">
                    Submission
                </small>

                <strong>
                    {{ $submission->submission_code }}
                </strong>

                <div class="mt-1">
                    {{ $submission->title }}
                </div>

            </div>

        </div>

    </div>


    <form action="{{ route('participant.submissions.video.update', $submission) }}" method="POST" id="form-submit">
        @csrf
        @method('PUT')

        {{-- Video Link --}}
        <div class="card rounded-0 overflow-hidden mb-3">

            <div class="card-header rounded-0">

                <h3 class="card-title">
                    <i class="bi bi-camera-video me-2"></i>
                    Presentation Video
                </h3>

            </div>

            <div class="card-body">

                <div class="alert alert-warning rounded-0">

                    <div class="d-flex align-items-start gap-2">

                        <i class="bi bi-exclamation-circle fs-5"></i>

                        <div>

                            <strong>
                                Upload the video to your own Google Drive.
                            </strong>

                            <div class="small mt-1">

                                The conference system does not upload or store
                                the video file. Only the Google Drive link is stored.

                            </div>

                        </div>

                    </div>

                </div>


                <div class="mb-3">

                    <label for="video_url" class="form-label">

                        Google Drive Video Link

                        <span class="text-danger">
                            *
                        </span>

                    </label>

                    <input type="url" id="video_url" name="video_url"
                        value="{{ old('video_url', $submission->video_url) }}"
                        class="form-control @error('video_url') is-invalid @enderror rounded-0"
                        placeholder="https://drive.google.com/..." autocomplete="off">

                    @error('video_url')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                    <div class="form-text">

                        Use a Google Drive link to the video stored in your own Drive.

                    </div>

                </div>


                @if ($submission->video_url)

                    <div class="border rounded-0 p-3 bg-light">

                        <div class="d-flex justify-content-between align-items-center gap-3">

                            <div>

                                <small class="text-muted d-block">
                                    Current Video Link
                                </small>

                                <a href="{{ $submission->video_url }}" target="_blank" rel="noopener noreferrer">

                                    Open Google Drive Video

                                </a>

                                @if ($submission->video_submitted_at)
                                    <small class="text-muted d-block mt-1">

                                        Submitted:
                                        {{ $submission->video_submitted_at->format('d M Y H:i') }}

                                    </small>
                                @endif

                            </div>

                            <span class="badge text-bg-success rounded-0">

                                Submitted

                            </span>

                        </div>

                    </div>

                @endif

            </div>

        </div>

        {{-- Actions --}}
        <div class="text-end">
            <button type="button" class="btn btn-success rounded-0" id="btn-submit" onclick="form_submit()">
                <span id="btn-submit-text">
                    <i class="bi bi-check-circle me-1"></i>
                    Submit Presentation Detail
                </span>
                <span id="btn-submit-load" class="d-none">
                    <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                    Memproses...
                </span>
            </button>
        </div>

    </form>

@endsection

@push('styles')
    <style>
        .video-option {
            cursor: pointer;
            transition: border-color .15s ease, box-shadow .15s ease;
        }

        .video-option:hover {
            border-color: var(--bs-primary) !important;
        }

        .btn-check:checked+.video-option {
            border-color: var(--bs-primary) !important;
            box-shadow: 0 0 0 .15rem rgba(var(--bs-primary-rgb), .15);
        }
    </style>
@endpush

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
