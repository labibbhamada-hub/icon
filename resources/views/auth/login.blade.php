@extends('layouts.auth')

@section('title', 'Login')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="auth-card">
                    <div class="text-center mb-4">
                        <img src="{{ asset('assets/images/logo/logo-bhamada.png') }}" alt="ICON 2026" class="auth-logo">
                        <h2 class="auth-title mt-4">
                            ICON 2026
                        </h2>
                        <p class="auth-subtitle">
                            Conference Management System
                        </p>
                        <p class="auth-description">
                            Welcome back! Please sign in to continue.
                        </p>
                    </div>
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            {{ $errors->first() }}
                        </div>
                    @endif
                    <form method="POST" action="{{ route('login.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">
                                Email Address
                            </label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required
                                autofocus>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">
                                Password
                            </label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                <label class="form-check-label" for="remember">
                                    Remember Me
                                </label>
                            </div>
                            <a href="#" class="forgot-link">
                                Forgot Password?
                            </a>
                        </div>
                        <button type="submit" class="btn btn-login-auth w-100">
                            <i class="bi bi-box-arrow-in-right me-2"></i>
                            Login
                        </button>
                    </form>
                </div>
                <div class="text-center mt-4 auth-footer">
                    © {{ date('Y') }} Universitas Bhamada
                </div>
            </div>
        </div>
    </div>
@endsection
