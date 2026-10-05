@extends('layouts.admin')

@section('title', 'Generate Certificate')

@section('header')
    <div class="row align-items-top">
        <div class="col-sm-6">
            <div class="d-flex gap-2">
                <a href="{{ route('admin.certificates.index') }}" class="btn btn-secondary rounded-0">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h1 class="mb-0 fs-3">
                    Generate Certificate
                </h1>
            </div>
            <p class="text-muted mb-0">
                Generate a certificate for a confirmed participant.
            </p>
        </div>
    </div>
@endsection

@section('content')

    <form action="{{ route('admin.certificates.store') }}" method="POST" id="certificate-form">
        @csrf

        <div class="card rounded-0 overflow-hidden mb-3">

            <div class="card-header rounded-0">

                <h3 class="card-title">

                    <i class="bi bi-award me-2"></i>

                    Certificate Information

                </h3>

            </div>


            <div class="card-body">

                {{-- ====================================================
                    PARTICIPANT & TYPE
                ===================================================== --}}

                <div class="row">

                    {{-- Participant --}}
                    <div class="col-lg-8 mb-2">

                        <label for="participant_id" class="form-label">
                            Participant
                            <span class="text-danger">*</span>
                        </label>

                        <select name="participant_id" id="participant_id"
                            class="form-select @error('participant_id') is-invalid @enderror rounded-0">

                            <option value="">
                                Select Participant
                            </option>

                            @foreach ($participants as $participant)
                                <option value="{{ $participant->id }}" data-submissions='@json(
                                    $participant->submissions->filter(function ($submission) {
                                            return $submission->status === 'published' ||
                                                ($submission->submission_stage === 'full_paper' &&
                                                    $submission->status === 'accepted' &&
                                                    !empty($submission->video_url) &&
                                                    !empty($submission->video_submitted_at));
                                        })->values())'
                                    @selected(old('participant_id') == $participant->id)>

                                    {{ $participant->full_name }}

                                    -
                                    {{ $participant->registration_number }}

                                    -
                                    {{ $participant->conference?->short_name ?? 'Conference' }}

                                </option>
                            @endforeach

                        </select>

                        <div class="form-text">
                            Only confirmed participants from conferences
                            with certificate generation enabled are listed.
                        </div>

                        @error('participant_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Certificate Type --}}
                    <div class="col-lg-4 mb-2">

                        <label for="type" class="form-label">
                            Certificate Type
                            <span class="text-danger">*</span>
                        </label>

                        <select name="type" id="type"
                            class="form-select @error('type') is-invalid @enderror rounded-0">

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

                        <div class="form-text">
                            Presenter certificates require an eligible submission.
                        </div>

                        @error('type')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>

            </div>

            {{-- ========================================================
                SUBMISSION
            ========================================================= --}}

            <div class="card-body border-top">

                <div class="row">

                    <div class="col-12 mb-2">

                        <label for="submission_id" class="form-label">
                            Eligible Submission
                        </label>

                        <select name="submission_id" id="submission_id"
                            class="form-select @error('submission_id') is-invalid @enderror rounded-0">

                            <option value="">
                                No submission
                            </option>

                        </select>


                        <div id="submission-help" class="form-text">
                            Select Presenter certificate to choose an
                            eligible submission.
                        </div>


                        @error('submission_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>

            </div>

            {{-- ========================================================
                INFO
            ========================================================= --}}

            <div class="card-body border-top">

                <div class="alert alert-info rounded-0 mb-0">

                    <div class="d-flex">

                        <i class="bi bi-info-circle me-2 mt-1"></i>

                        <div>

                            <strong>
                                Certificate generation
                            </strong>

                            <div class="mt-1">

                                The certificate PDF will be generated
                                automatically after submission. For presenter
                                certificates, the selected submission must be an
                                eligible full paper with an accepted status and
                                a submitted presentation video. Legacy published
                                submissions remain supported.

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="text-end">

            <button type="submit" class="btn btn-success rounded-0" id="generate-certificate-button">
                <i class="bi bi-award me-1"></i>
                Generate Certificate
            </button>

        </div>

    </form>

@endsection

@push('scripts')
    <script>
        document.addEventListener(
            'DOMContentLoaded',
            function() {

                const participantSelect =
                    document.getElementById(
                        'participant_id'
                    );

                const typeSelect =
                    document.getElementById(
                        'type'
                    );

                const submissionSelect =
                    document.getElementById(
                        'submission_id'
                    );

                const submissionHelp =
                    document.getElementById(
                        'submission-help'
                    );

                const certificateForm =
                    document.getElementById(
                        'certificate-form'
                    );


                const oldSubmissionId =
                    @json(old('submission_id'));


                /*
                |--------------------------------------------------------------------------
                | Refresh Submission Options
                |--------------------------------------------------------------------------
                */

                function refreshSubmissions() {

                    submissionSelect.innerHTML = `
            <option value="">
                No submission
            </option>
        `;


                    const isPresenter =
                        typeSelect.value === 'presenter';


                    /*
                    |--------------------------------------------------------------------------
                    | Submission is only relevant for Presenter
                    |--------------------------------------------------------------------------
                    */

                    if (!isPresenter) {

                        submissionSelect.disabled =
                            true;

                        submissionSelect.value =
                            '';

                        submissionHelp.textContent =
                            'Submission selection is only required for Presenter certificates.';

                        return;

                    }


                    submissionSelect.disabled =
                        false;


                    submissionHelp.textContent =
                        'Only eligible submissions belonging to the selected participant are available.';


                    const selectedOption =
                        participantSelect.options[
                            participantSelect.selectedIndex
                        ];


                    if (
                        !selectedOption ||
                        !selectedOption.value
                    ) {

                        submissionHelp.textContent =
                            'Select a participant first to load eligible submissions.';

                        return;

                    }


                    let submissions = [];


                    try {

                        submissions =
                            JSON.parse(
                                selectedOption.dataset.submissions ||
                                '[]'
                            );

                    } catch (error) {

                        submissions = [];

                    }


                    if (!submissions.length) {

                        submissionHelp.textContent =
                            'No eligible submissions are available for this participant.';

                        return;

                    }


                    submissions.forEach(
                        function(submission) {

                            const option =
                                document.createElement(
                                    'option'
                                );


                            option.value =
                                submission.id;


                            option.textContent =
                                `${submission.submission_code} - ${submission.title}`;


                            if (
                                String(
                                    oldSubmissionId
                                ) === String(
                                    submission.id
                                )
                            ) {

                                option.selected =
                                    true;

                            }


                            submissionSelect.appendChild(
                                option
                            );

                        }
                    );

                }


                /*
                |--------------------------------------------------------------------------
                | Participant Change
                |--------------------------------------------------------------------------
                */

                participantSelect.addEventListener(
                    'change',
                    function() {

                        refreshSubmissions();

                    }
                );


                /*
                |--------------------------------------------------------------------------
                | Certificate Type Change
                |--------------------------------------------------------------------------
                */

                typeSelect.addEventListener(
                    'change',
                    function() {

                        refreshSubmissions();

                    }
                );


                /*
                |--------------------------------------------------------------------------
                | Form Confirmation
                |--------------------------------------------------------------------------
                */

                certificateForm.addEventListener(
                    'submit',
                    function(event) {

                        event.preventDefault();


                        const type =
                            typeSelect.value;


                        const labels = {

                            participant: 'Participant',

                            presenter: 'Presenter',

                            speaker: 'Speaker',

                            committee: 'Committee',

                            reviewer: 'Reviewer',

                        };


                        let message =
                            'A ' +
                            (
                                labels[type] ||
                                'certificate'
                            ) +
                            ' certificate will be generated.';


                        if (type === 'presenter') {

                            if (
                                !submissionSelect.value
                            ) {

                                Swal.fire({

                                    title: 'Submission Required',

                                    text: 'A Presenter certificate requires an eligible submission.',

                                    icon: 'warning',

                                    confirmButtonText: 'OK',

                                    confirmButtonColor: '#198754',

                                });

                                return;

                            }


                            message +=
                                ' The selected eligible submission will be linked to the certificate.';

                        }


                        Swal.fire({

                            title: 'Generate Certificate?',

                            text: message,

                            icon: 'question',

                            showCancelButton: true,

                            confirmButtonText: 'Yes, generate',

                            cancelButtonText: 'Cancel',

                            confirmButtonColor: '#198754',

                            cancelButtonColor: '#6c757d',

                        }).then(
                            function(result) {

                                if (
                                    result.isConfirmed
                                ) {

                                    certificateForm.submit();

                                }

                            }
                        );

                    }
                );


                /*
                |--------------------------------------------------------------------------
                | Initial State
                |--------------------------------------------------------------------------
                */

                refreshSubmissions();

            }
        );
    </script>
@endpush
