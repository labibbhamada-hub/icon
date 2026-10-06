@extends('layouts.auth')

@section('title', 'Verify Email')

@section('content')

    <div class="register-box py-4">

        <div class="text-center mb-4">

            <img src="{{ asset('assets/images/logo/logo-bhamada.png') }}" alt="ICON 2026" width="72" class="mb-2 rounded-0">

            <h3 class="fw-bold mb-1">
                ICON 2026
            </h3>

            <p class="text-muted mb-0">
                Conference Management System
            </p>

        </div>

        <div class="card card-outline card-primary shadow-sm rounded-0">

            <div class="card-header text-center py-3 rounded-0">

                <h5 class="mb-1 fw-semibold">
                    Verify Your Email
                </h5>

                <small class="text-muted">
                    Almost there!
                </small>

            </div>

            <div class="card-body p-4">

                @if (session('success'))
                    <div class="alert alert-success rounded-0">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger rounded-0">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="text-center mb-4">

                    <div class="mb-3">
                        <i class="bi bi-envelope-check text-primary" style="font-size: 3rem;"></i>
                    </div>

                    <p class="mb-2">
                        We have sent a verification link to:
                    </p>

                    <strong>
                        {{ Auth::user()->email }}
                    </strong>

                    <p class="text-muted mt-3 mb-0">
                        Please check your inbox and click the
                        verification link to activate your account.
                    </p>

                </div>

                {{-- Resend Verification Email --}}
                <form method="POST" action="{{ route('verification.send') }}" class="mb-2" id="form-verify">
                    @csrf

                    <button type="button" class="btn btn-primary rounded-0 w-100" id="btn-verify" onclick="form_verify()">
                        <span id="btn-verify-text">
                            <i class="bi bi-send me-2"></i>
                            Resend Verification Email
                        </span>
                        <span id="btn-verify-load" class="d-none">
                            <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                            Memproses...
                        </span>
                    </button>

                </form>

                {{-- Refresh Verification Status --}}
                <button type="button" class="btn btn-outline-secondary rounded-0 w-100" id="btn-refresh"
                    onclick="btn_refresh()">
                    <span id="btn-refresh-text">
                        <i class="bi bi-arrow-clockwise me-2"></i>
                        Refresh
                    </span>
                    <span id="btn-refresh-load" class="d-none">
                        <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                        Memproses...
                    </span>
                </button>

                <div class="text-center mt-4">

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <button type="submit" class="btn btn-link text-decoration-none">
                            Sign out
                        </button>

                    </form>

                </div>

            </div>

        </div>

        <div class="text-center mt-3">

            <small class="text-muted">
                © {{ date('Y') }} Universitas Bhamada Slawi
            </small>

        </div>

    </div>

@endsection

@push('scripts')
    <script>
        function form_verify() {
            const btnVerify = document.getElementById('btn-verify');
            const btnVerifyText = document.getElementById('btn-verify-text');
            const btnVerifyLoad = document.getElementById('btn-verify-load');
            const formVerify = document.getElementById('form-verify');

            btnVerify.disabled = true;

            btnVerifyText.classList.add('d-none');
            btnVerifyLoad.classList.remove('d-none');

            formVerify.submit();
        }
    </script>
    <script>
        function btn_refresh() {
            const btnRefresh = document.getElementById('btn-refresh');
            const btnRefreshText = document.getElementById('btn-refresh-text');
            const btnRefreshLoad = document.getElementById('btn-refresh-load');

            btnRefresh.disabled = true;

            btnRefreshText.classList.add('d-none');
            btnRefreshLoad.classList.remove('d-none');

            window.location.reload()
        }
    </script>
@endpush
