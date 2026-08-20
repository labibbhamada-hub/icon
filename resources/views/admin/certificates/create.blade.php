@extends('layouts.admin')

@section('title', 'Generate Certificate')

@section('header')
    <div class="row align-items-center">
        <div class="col-sm-6">
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('admin.certificates.index') }}" class="btn btn-secondary btn-sm rounded-0">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h1 class="mb-0 fs-3">
                    Generate Certificate
                </h1>
            </div>
            <p class="text-muted mb-0">
                Generate a certificate for a registered participant.
            </p>
        </div>
        <div class="col-sm-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.certificates.index') }}">Certificate</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Create</li>
                </ol>
            </nav>
        </div>
    </div>
@endsection

@section('content')
    <form action="{{ route('admin.certificates.store') }}" method="POST">
        @csrf
        <div class="card rounded-0">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="bi bi-award me-2"></i>
                    Certificate Information
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8 mb-2">
                        <label class="form-label">
                            Participant
                            <span class="text-danger">*</span>
                        </label>
                        <select name="participant_id" id="participant_id"
                            class="form-select @error('participant_id') is-invalid @enderror rounded-0">
                            <option value="">
                                Select Participant
                            </option>
                            @foreach ($participants as $participant)
                                <option value="{{ $participant->id }}" data-submissions='@json($participant->submissions->where('status', 'published')->values())'
                                    @selected(old('participant_id') == $participant->id)>
                                    {{ $participant->full_name }}
                                    —
                                    {{ $participant->registration_number }}
                                    —
                                    {{ $participant->conference?->short_name }}
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
                            Certificate Type
                            <span class="text-danger">*</span>
                        </label>
                        <select name="type" class="form-select @error('type') is-invalid @enderror rounded-0">
                            <option value="participant" @selected(old('type', 'participant') === 'participant')>
                                Participant
                            </option>
                            <option value="presenter" @selected(old('type') === 'presenter')>
                                Presenter
                            </option>
                            <option value="speaker" @selected(old('type') === 'speaker')>
                                Speaker
                            </option>
                            <option value="committee" @selected(old('type') === 'committee')>
                                Committee
                            </option>
                            <option value="reviewer" @selected(old('type') === 'reviewer')>
                                Reviewer
                            </option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label">
                            Submission
                        </label>
                        <select name="submission_id" id="submission_id"
                            class="form-select @error('submission_id') is-invalid @enderror rounded-0">
                            <option value="">
                                No submission / Participant certificate
                            </option>
                        </select>
                        <div class="form-text">
                            Only published submissions belonging to the selected participant are available.
                        </div>
                        @error('submission_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="card-footer text-end">
                <button type="submit" class="btn btn-success btn-sm rounded-0">
                    <i class="bi bi-award me-1"></i>
                    Generate Certificate
                </button>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const participantSelect =
                document.getElementById('participant_id');
            const submissionSelect =
                document.getElementById('submission_id');

            function refreshSubmissions() {
                submissionSelect.innerHTML = `
            <option value="">
                No submission / Participant certificate
            </option>
        `;
                const selected =
                    participantSelect.options[
                        participantSelect.selectedIndex
                    ];
                if (!selected || !selected.value) {
                    return;
                }
                let submissions = [];
                try {
                    submissions =
                        JSON.parse(
                            selected.dataset.submissions || '[]'
                        );
                } catch (error) {
                    submissions = [];
                }
                submissions.forEach(function(submission) {
                    const option =
                        document.createElement('option');
                    option.value =
                        submission.id;
                    option.textContent =
                        `${submission.submission_code} — ${submission.title}`;
                    submissionSelect.appendChild(option);
                });
            }
            participantSelect.addEventListener(
                'change',
                refreshSubmissions
            );
            refreshSubmissions();
        });
    </script>
@endpush
