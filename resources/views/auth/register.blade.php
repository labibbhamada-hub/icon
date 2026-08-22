@extends('layouts.auth')

@section('title', 'Register')

@section('content')
    <div class="register-box py-5">
        <div class="text-center mb-4">
            <img src="{{ asset('assets/images/logo/logo-bhamada.png') }}" alt="ICON 2026" width="80">
            <h3 class="mt-3 fw-bold mb-1">
                ICON 2026
            </h3>
            <p class="text-muted mb-0">
                Conference Management System
            </p>
        </div>
        <div class="card card-outline card-primary shadow rounded-0">
            <div class="card-header text-center">
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
                <form action="{{ route('register.store') }}" method="POST">
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
                                    <a href="#" class="text-decoration-none">
                                        terms
                                    </a>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary rounded-0">
                            <i class="bi bi-box-arrow-in-right me-2"></i>
                            Register
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
        <div class="text-center mt-3 text-muted">
            © {{ date('Y') }} Universitas Bhamada
        </div>
    </div>
@endsection
