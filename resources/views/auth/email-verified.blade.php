@extends('layouts.auth')

@section('title', 'Email Verified')

@section('content')
    <div class="register-box py-5">
        <div class="text-center mb-4">
            <img src="{{ asset('assets/images/logo/logo-bhamada.png') }}" alt="ICON 2026" width="72" class="mb-2">
            <h3 class="fw-bold mb-1">
                ICON 2026
            </h3>
            <p class="text-muted mb-0">
                Conference Management System
            </p>
        </div>
        <div class="card card-outline card-success shadow-sm rounded-0">
            <div class="card-header text-center py-3">
                <h5 class="mb-1 fw-semibold">
                    Email Verified
                </h5>
                <small class="text-muted">
                    Your account is ready to use
                </small>
            </div>
            <div class="card-body p-4 text-center">
                <div class="mb-4">
                    <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;">
                    </i>
                </div>
                @if ($alreadyVerified)
                    <h4 class="fw-bold">
                        Email Already Verified
                    </h4>
                    <p class="text-muted mb-4">
                        Your email address has already been verified.
                        You can continue to your participant portal.
                    </p>
                @else
                    <h4 class="fw-bold">
                        Verification Successful!
                    </h4>
                    <p class="text-muted mb-4">
                        Your email address has been successfully verified.
                        You can now access the ICON 2026 participant portal.
                    </p>
                @endif
                <div class="d-grid">
                    <a href="{{ route('participant.dashboard') }}" class="btn btn-success rounded-0 py-2">
                        <i class="bi bi-speedometer2 me-2"></i>
                        Go to Participant Dashboard
                    </a>
                </div>
                <div class="mt-3">
                    <small class="text-muted">
                        Welcome to ICON 2026.
                    </small>
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
