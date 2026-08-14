@extends('layouts.participant')

@section('title', 'My Profile')

@section('header')
    <div class="row">
        <div class="col-sm-6 d-flex align-items-center">
            <h1 class="mb-0 fs-3">
                My Profile
            </h1>
        </div>
        <div class="col-sm-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item">
                        <a href="{{ route('participant.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">My Profile</li>
                </ol>
            </nav>
        </div>
    </div>
@endsection

@section('content')
    <form action="{{ route('participant.profile.update') }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-lg-4">
                <div class="card rounded-0">
                    <div class="card-body text-center p-4">
                        <div class="rounded-circle bg-primary-subtle d-flex align-items-center justify-content-center mx-auto mb-2"
                            style="width:100px; height:100px;">
                            <i class="bi bi-person display-4 text-primary"></i>
                        </div>
                        <div class="mb-2">
                            <h4 class="fw-bold">{{ $participant->full_name }}</h4>
                            <p class="text-muted">{{ $participant->email }}</p>
                        </div>
                        <div class="mb-2">
                            <span class="badge text-bg-primary rounded-0">
                                {{ $participant->registration_number }}
                            </span>
                        </div>
                        @if ($participant->registration_status === 'confirmed')
                            <span class="badge text-bg-success rounded-0">
                                Confirmed
                            </span>
                        @elseif ($participant->registration_status === 'cancelled')
                            <span class="badge text-bg-danger rounded-0">
                                Cancelled
                            </span>
                        @else
                            <span class="badge text-bg-warning rounded-0">
                                Pending
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card rounded-0">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="bi bi-person-vcard me-2"></i>
                            Personal Information
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label class="form-label">
                                    Full Name
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="full_name"
                                    value="{{ old('full_name', $participant->full_name) }}"
                                    class="form-control @error('full_name') is-invalid @enderror rounded-0">
                                @error('full_name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label">
                                    Email
                                </label>
                                <input type="email" value="{{ $participant->email }}" class="form-control rounded-0"
                                    readonly>
                                <div class="form-text">
                                    Email is managed through your account.
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label">
                                    Phone Number
                                </label>
                                <input type="text" name="phone" value="{{ old('phone', $participant->phone) }}"
                                    class="form-control @error('phone') is-invalid @enderror rounded-0">
                                @error('phone')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-3 mb-2">
                                <label class="form-label">
                                    Country
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="country" value="{{ old('country', $participant->country) }}"
                                    class="form-control @error('country') is-invalid @enderror rounded-0">
                                @error('country')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-3 mb-2">
                                <label class="form-label">
                                    City
                                </label>
                                <input type="text" name="city" value="{{ old('city', $participant->city) }}"
                                    class="form-control @error('city') is-invalid @enderror rounded-0">
                                @error('city')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label">
                                    Institution
                                </label>
                                <input type="text" name="institution"
                                    value="{{ old('institution', $participant->institution) }}"
                                    class="form-control @error('institution') is-invalid @enderror rounded-0">
                                @error('institution')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label">
                                    Department
                                </label>
                                <input type="text" name="department"
                                    value="{{ old('department', $participant->department) }}"
                                    class="form-control @error('department') is-invalid @enderror rounded-0">
                                @error('department')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <button class="btn btn-success btn-sm rounded-0">
                            <i class="bi bi-check-circle"></i>
                            Save Conference
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
