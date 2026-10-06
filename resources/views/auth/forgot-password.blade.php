@extends('layouts.auth')

@section('title', 'Forgot Password')

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

                <h5 class="mb-0">
                    Forgot Password
                </h5>

            </div>

            <div class="card-body">

                <p class="login-box-msg">
                    Enter your email address to receive a password reset link
                </p>

                @if (session('status'))
                    <div class="alert alert-success rounded-0">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger rounded-0">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('password.email') }}" method="POST">
                    @csrf

                    <div class="input-group mb-3">

                        <input type="email" name="email" class="form-control rounded-0" placeholder="Email Address"
                            value="{{ old('email') }}" autocomplete="email" required autofocus>

                        <div class="input-group-text rounded-0">

                            <span class="bi bi-envelope"></span>

                        </div>

                    </div>

                    <div class="d-grid mt-4">

                        <button type="submit" class="btn btn-success rounded-0">
                            <i class="bi bi-envelope-arrow-up me-2"></i>
                            Send Reset Link
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

            Â© {{ date('Y') }} Universitas Bhamada

        </div>

    </div>

@endsection
