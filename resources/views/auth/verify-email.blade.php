@extends('layouts.auth')

@section('title', 'Verify Email')

@section('content')
    <div class="register-box">
        <div class="text-center mb-4">
            <img src="{{ asset('assets/images/logo/logo-bhamada.png') }}" alt="ICON 2026" width="72" class="mb-2">
            <h3 class="fw-bold mb-1">
                ICON 2026
            </h3>
            <p class="text-muted mb-0">
                Conference Management System
            </p>
        </div>
        <div class="card card-outline card-primary shadow-sm rounded-0">
            <div class="card-header text-center py-3">
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
                        <i class="bi bi-envelope-check text-primary" style="font-size: 3rem;">
                        </i>
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
                <form method="POST"
                    action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="btn btn-primary rounded-0 w-100">
                        <i class="bi bi-send me-2"></i>
                        Resend Verification Email
                    </button>
                </form>
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
