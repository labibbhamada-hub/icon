@extends('layouts.auth')

@section('title', 'Register')

@section('content')

    <div class="register-box py-4">
        <div class="text-center mb-4">
            <img src="{{ asset('assets/images/logo/logo-bhamada.png') }}" alt="ICON 2026" width="80" class="rounded-0">

            <h3 class="mt-3 fw-bold mb-1">
                ICON 2026
            </h3>

            <p class="text-muted mb-0">
                Conference Management System
            </p>
        </div>

        <div class="card card-outline card-success shadow rounded-0 overflow-hidden">
            <div class="card-header rounded-0 text-center">
                <h5 class="mb-0">
                    Register
                </h5>
            </div>

            <div class="card-body">
                <p class="register-box-msg">
                    Create your participant account
                </p>

                @if ($errors->any())
                    <div class="alert alert-danger rounded-0">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>
                                    {{ $error }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger rounded-0">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('register.store') }}" method="POST" id="form-submit">
                    @csrf

                    <label class="visually-hidden" for="registerName">
                        Full Name
                    </label>

                    <div class="input-group mb-3">
                        <input id="registerName" type="text" name="name" value="{{ old('name') }}"
                            class="form-control @error('name') is-invalid @enderror rounded-0" placeholder="Full Name"
                            autocomplete="name" required autofocus>

                        <div class="input-group-text rounded-0">
                            <span class="bi bi-person"></span>
                        </div>

                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <label class="visually-hidden" for="registerEmail">
                        Email
                    </label>

                    <div class="input-group mb-3">
                        <input id="registerEmail" type="email" name="email" value="{{ old('email') }}"
                            class="form-control @error('email') is-invalid @enderror rounded-0" placeholder="Email"
                            autocomplete="email" required>

                        <div class="input-group-text rounded-0">
                            <span class="bi bi-envelope"></span>
                        </div>

                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <label class="visually-hidden" for="registerPassword">
                        Password
                    </label>

                    <div class="input-group mb-3">
                        <input id="registerPassword" type="password" name="password"
                            class="form-control @error('password') is-invalid @enderror rounded-0" placeholder="Password"
                            autocomplete="new-password" required>

                        <div class="input-group-text rounded-0">
                            <span class="bi bi-lock-fill"></span>
                        </div>

                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <label class="visually-hidden" for="registerPasswordConfirmation">
                        Confirm Password
                    </label>

                    <div class="input-group mb-3">
                        <input id="registerPasswordConfirmation" type="password" name="password_confirmation"
                            class="form-control rounded-0" placeholder="Confirm Password" autocomplete="new-password"
                            required>

                        <div class="input-group-text rounded-0">
                            <span class="bi bi-lock-fill"></span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-8">
                            <div class="form-check">
                                <input class="form-check-input rounded-0" type="checkbox" value="1" id="agreeTerms"
                                    name="terms" required>

                                <label class="form-check-label" for="agreeTerms">
                                    I agree to the
                                    <a href="#termsModal" class="text-decoration-none" data-bs-toggle="modal"
                                        data-bs-target="#termsModal">
                                        terms
                                    </a>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="button" class="btn btn-success rounded-0" id="btn-submit" onclick="form_submit()">
                            <span id="btn-submit-text">
                                <i class="bi bi-box-arrow-in-right me-2"></i>
                                Register
                            </span>
                            <span id="btn-submit-load" class="d-none">
                                <span class="spinner-border spinner-border-sm me-1" role="status"
                                    aria-hidden="true"></span>
                                Memproses...
                            </span>
                        </button>
                    </div>

                    <div class="text-center mt-4">
                        <span class="text-muted">
                            Already have an account?
                        </span>

                        <a href="{{ route('login') }}" class="text-decoration-none">
                            Sign in
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Terms & Conditions Modal -->
        <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content rounded-0">

                    <div class="modal-header rounded-0">
                        <h5 class="modal-title fw-bold" id="termsModalLabel">
                            Terms & Conditions
                        </h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">

                        <p class="mb-4">
                            By creating an account and registering through the ICON 2026 Conference Management System,
                            you acknowledge and agree to the following terms and conditions.
                        </p>

                        <h6 class="fw-bold">
                            1. Account Registration
                        </h6>
                        <p>
                            You are responsible for providing accurate, complete, and up-to-date information when
                            creating your account. The account is personal and must not be used by another individual.
                            You are responsible for maintaining the confidentiality of your login credentials.
                        </p>

                        <h6 class="fw-bold">
                            2. Registration Information
                        </h6>
                        <p>
                            All information submitted during registration must be correct and correspond to your actual
                            identity and participation in ICON 2026. Any incorrect or misleading information may affect
                            your registration and participation status.
                        </p>

                        <h6 class="fw-bold">
                            3. Conference Participation
                        </h6>
                        <p>
                            Registration through this system constitutes your intention to participate in ICON 2026.
                            Participation status and access to conference activities are subject to completion of the
                            applicable registration requirements and verification by the organizing committee.
                        </p>

                        <h6 class="fw-bold">
                            4. Submission & Review
                        </h6>
                        <p>
                            For participants submitting academic work, all submitted materials must be original,
                            relevant to the selected conference topic, and prepared according to the applicable
                            submission guidelines. Submitted papers may be reviewed by the appointed reviewers and the
                            final decision is determined by the conference committee.
                        </p>

                        <h6 class="fw-bold">
                            5. Payment
                        </h6>
                        <p>
                            Where a registration fee applies, payment must be made according to the official
                            instructions provided by the conference committee. Payment proof submitted through the
                            system must be valid and clearly readable. Registration is considered confirmed only after
                            the payment has been verified by the authorized administrator.
                        </p>

                        <h6 class="fw-bold">
                            6. Academic Integrity
                        </h6>
                        <p>
                            Participants are expected to maintain academic integrity and professional conduct.
                            Plagiarism, fabrication, falsification, unauthorized use of another person's work, or other
                            forms of academic misconduct may result in rejection of the submission or other appropriate
                            action by the conference committee.
                        </p>

                        <h6 class="fw-bold">
                            7. Presentation & Attendance
                        </h6>
                        <p>
                            Participants registered as presenters are responsible for completing the required
                            presentation and attendance procedures. Presentation materials and attendance requirements
                            must follow the instructions issued by the conference committee.
                        </p>

                        <h6 class="fw-bold">
                            8. Certificate
                        </h6>
                        <p>
                            Certificates are issued based on the applicable participation, attendance, presentation,
                            submission, and verification requirements of the conference. Certificate eligibility may
                            differ depending on the participant's registration category.
                        </p>

                        <h6 class="fw-bold">
                            9. Publication Recommendation
                        </h6>
                        <p>
                            Eligible accepted papers may be considered for publication recommendation to designated
                            journal partners according to the conference requirements and selection process.
                            Publication recommendation does not guarantee acceptance or publication by the receiving
                            journal.
                        </p>

                        <h6 class="fw-bold">
                            10. Communication & Updates
                        </h6>
                        <p>
                            Participants are responsible for regularly checking the email address and communication
                            channels associated with their registration. Important information regarding schedules,
                            submission deadlines, payment verification, presentations, and other conference activities
                            may be communicated through these channels.
                        </p>

                        <h6 class="fw-bold">
                            11. Changes to Conference Information
                        </h6>
                        <p>
                            The conference committee may update schedules, procedures, technical requirements, or other
                            conference information when necessary. Participants are expected to follow the latest
                            official information published or communicated by the conference committee.
                        </p>

                        <h6 class="fw-bold">
                            12. Agreement
                        </h6>
                        <p class="mb-0">
                            By checking the <strong>"I agree to the terms"</strong> checkbox, you confirm that you have
                            read, understood, and agreed to comply with these terms and the applicable ICON 2026
                            conference requirements.
                        </p>

                    </div>

                    <div class="modal-footer rounded-0">
                        <button type="button" class="btn btn-secondary rounded-0" data-bs-dismiss="modal">
                            Close
                        </button>
                    </div>

                </div>
            </div>
        </div>

        <div class="text-center mt-3 text-muted">
            © {{ date('Y') }} Universitas Bhamada
        </div>
    </div>

@endsection

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
