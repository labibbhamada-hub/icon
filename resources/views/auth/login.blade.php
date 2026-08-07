@extends('layouts.auth')

@section('title', 'Login')

@section('content')
    <div class="login-box">
        <div class="text-center mb-4">
            <img src="{{ asset('assets/images/logo/logo-bhamada.png') }}" alt="ICON 2026" width="80">
            <h3 class="mt-3 fw-bold mb-1">
                ICON 2026
            </h3>
            <p class="text-muted mb-0">
                Conference Management System
            </p>
        </div>
        <div class="card card-outline card-success shadow rounded-0">
            <div class="card-header text-center">
                <h5 class="mb-0">
                    Sign In
                </h5>
            </div>
            <div class="card-body">
                <p class="login-box-msg">
                    Sign in to start your session
                </p>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        {{ $errors->first() }}
                    </div>
                @endif
                <form action="{{ route('login.store') }}" method="POST">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="email" name="email" class="form-control rounded-0" placeholder="Email Address"
                            value="{{ old('email') }}" required autofocus>
                        <div class="input-group-text rounded-0">
                            <span class="bi bi-envelope"></span>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control rounded-0" placeholder="Password" required>
                        <div class="input-group-text rounded-0">
                            <span class="bi bi-lock-fill"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-check">
                                <input class="form-check-input rounded-0" type="checkbox" name="remember" id="remember">
                                <label class="form-check-label" for="remember">
                                    Remember Me
                                </label>
                            </div>
                        </div>
                        <div class="col-6 text-end">
                            <a href="#">
                                Forgot Password?
                            </a>
                        </div>
                    </div>
                    <div class="d-grid mt-4">
                        <button class="btn btn-success rounded-0">
                            <i class="bi bi-box-arrow-in-right me-2"></i>
                            Sign In
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="text-center mt-3 text-muted">
            © {{ date('Y') }} Universitas Bhamada
        </div>
    </div>
@endsection
