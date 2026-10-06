@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content')

    <div class="login-box py-4">

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

                <h5 class="mb-1">
                    Reset Password
                </h5>

                <small class="text-muted">
                    Create a new password for your account
                </small>

            </div>

            <div class="card-body">

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

                <form action="{{ route('password.update') }}" method="POST" id="form-submit">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="input-group mb-3">

                        <input type="email" name="email" class="form-control rounded-0" placeholder="Email Address"
                            value="{{ old('email', $email) }}" autocomplete="email" readonly>

                        <div class="input-group-text rounded-0">

                            <span class="bi bi-envelope"></span>

                        </div>

                    </div>

                    <div class="input-group mb-3">

                        <input type="password" name="password" class="form-control rounded-0" placeholder="New Password"
                            autocomplete="new-password" required>

                        <div class="input-group-text rounded-0">

                            <span class="bi bi-lock-fill"></span>

                        </div>

                    </div>

                    <div class="input-group mb-3">

                        <input type="password" name="password_confirmation" class="form-control rounded-0"
                            placeholder="Confirm New Password" autocomplete="new-password" required>

                        <div class="input-group-text rounded-0">

                            <span class="bi bi-lock-fill"></span>

                        </div>

                    </div>

                    <div class="d-grid mt-4">

                        <button type="button" class="btn btn-success rounded-0" id="btn-submit" onclick="form_submit()">
                            <span id="btn-submit-text">
                                <i class="bi bi-key me-2"></i>
                                Reset Password
                            </span>
                            <span id="btn-submit-load" class="d-none">
                                <span class="spinner-border spinner-border-sm me-1" role="status"
                                    aria-hidden="true"></span>
                                Memproses...
                            </span>
                        </button>

                    </div>

                </form>

                <div class="text-center mt-4">

                    <a href="{{ route('login') }}" class="text-decoration-none">
                        <i class="bi bi-arrow-left me-1"></i>
                        Back to Sign In
                    </a>

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
