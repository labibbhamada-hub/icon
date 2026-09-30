@extends('layouts.auth')

@section('title', 'Login')

@section('content')

    <div class="login-box">
        <div class="text-center mb-4"> <img src="{{ asset('assets/images/logo/logo-bhamada.png') }}" alt="ICON 2026"
                width="80" class="rounded-2">

            <h3 class="mt-3 fw-bold mb-1">
                ICON 2026
            </h3>

            <p class="text-muted mb-0">
                Conference Management System
            </p>
        </div>

        <div class="card card-outline card-success shadow rounded-3 overflow-hidden">
            <div class="card-header rounded-top-3 text-center">
                <h5 class="mb-0">
                    Sign In
                </h5>
            </div>

            <div class="card-body">
                <p class="login-box-msg">
                    Sign in to start your session
                </p>

                @if ($errors->any())
                    <div class="alert alert-danger rounded-3">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('login.store') }}" method="POST">
                    @csrf

                    <div class="input-group mb-3">
                        <input type="email" name="email" class="form-control rounded-start-2"
                            placeholder="Email Address" value="{{ old('email') }}" required autofocus>

                        <div class="input-group-text rounded-end-2">
                            <span class="bi bi-envelope"></span>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control rounded-start-2" placeholder="Password"
                            required>

                        <div class="input-group-text rounded-end-2">
                            <span class="bi bi-lock-fill"></span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember">

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
                        <button type="submit" class="btn btn-success rounded-2">
                            <i class="bi bi-box-arrow-in-right me-2"></i>
                            Sign In
                        </button>
                    </div>

                    <div class="text-center mt-4">
                        <span class="text-muted">
                            Don't have an account?
                        </span>

                        <a href="{{ route('register') }}" class="text-decoration-none">
                            Create an account
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="text-center mt-3 text-muted">
            © {{ date('Y') }} Universitas Bhamada
        </div>
    </div>

@endsection
