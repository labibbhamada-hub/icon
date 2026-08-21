@extends('layouts.admin')

@section('title', 'Conference Configuration')

@section('header')
    <div class="row align-items-top">
        <div class="col-sm-6">
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('admin.conferences.show', $conference) }}" class="btn btn-secondary btn-sm rounded-0"
                    title="Back">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h1 class="mb-0 fs-3">
                    Conference Configuration
                </h1>
            </div>
            <p class="text-muted mb-0">
                Manage branding, payment, and certificate configuration.
            </p>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.dashboard') }}">
                        Dashboard
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.conferences.index') }}">
                        Conferences
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.conferences.show', $conference) }}">
                        Detail
                    </a>
                </li>
                <li class="breadcrumb-item active">
                    Configuration
                </li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show rounded-0">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <strong>
                Please correct the following:
            </strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>
                        {{ $error }}
                    </li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert">
            </button>
        </div>
    @endif

    <form action="{{ route('admin.conferences.configuration.update', $conference) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="card rounded-0 mb-3">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="bi bi-calendar-event me-2"></i>
                    Conference Information
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-8">
                        <small class="text-muted d-block">
                            Conference Name
                        </small>
                        <strong>
                            {{ $conference->name }}
                        </strong>
                    </div>
                    <div class="col-md-2">
                        <small class="text-muted d-block">
                            Short Name
                        </small>
                        <strong>
                            {{ $conference->short_name }}
                        </strong>
                    </div>
                    <div class="col-md-2">
                        <small class="text-muted d-block">
                            Year
                        </small>
                        <strong>
                            {{ $conference->year }}
                        </strong>
                    </div>
                </div>
            </div>
        </div>
        <div class="card rounded-0 mb-3">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="bi bi-palette me-2"></i>
                    Branding
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label">
                            Conference Logo
                        </label>
                        <input type="file" name="logo" accept=".jpg,.jpeg,.png,.webp"
                            class="form-control @error('logo') is-invalid @enderror rounded-0">
                        <div class="form-text">
                            JPG, JPEG, PNG, or WebP. Maximum 2 MB.
                        </div>
                        @error('logo')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                        @if ($configuration?->logo)
                            <div class="border rounded-0 p-3 mt-3">
                                <small class="text-muted d-block mb-2">
                                    Current Logo
                                </small>
                                <img src="{{ asset('storage/' . $configuration->logo) }}" alt="Conference Logo"
                                    style="max-width: 220px; max-height: 120px; object-fit: contain;">
                            </div>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">
                            Certificate Signature
                        </label>
                        <input type="file" name="signature_file" accept=".jpg,.jpeg,.png,.webp"
                            class="form-control @error('signature_file') is-invalid @enderror rounded-0">
                        <div class="form-text">
                            Transparent PNG is recommended. Maximum 2 MB.
                        </div>
                        @error('signature_file')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                        @if ($configuration?->signature_file)
                            <div class="border rounded-0 p-3 mt-3">
                                <small class="text-muted d-block mb-2">
                                    Current Signature
                                </small>
                                <img src="{{ asset('storage/' . $configuration->signature_file) }}"
                                    alt="Certificate Signature"
                                    style="max-width: 260px; max-height: 120px; object-fit: contain;">
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="card rounded-0 mb-3">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="bi bi-credit-card me-2"></i>
                    Payment Configuration
                </h3>
            </div>
            <div class="card-body">
                <div class="alert alert-info rounded-0">
                    <i class="bi bi-info-circle me-2"></i>
                    These details will be shown to participants
                    before they upload their bank transfer proof.
                </div>
                <div class="row g-4">
                    <div class="col-md-4">
                        <label class="form-label">
                            Bank Name
                        </label>
                        <input type="text" name="bank_name" value="{{ old('bank_name', $configuration?->bank_name) }}"
                            class="form-control @error('bank_name') is-invalid @enderror rounded-0" placeholder="BRI">
                        @error('bank_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">
                            Account Number
                        </label>
                        <input type="text" name="account_number"
                            value="{{ old('account_number', $configuration?->account_number) }}"
                            class="form-control @error('account_number') is-invalid @enderror rounded-0"
                            placeholder="1234567890">
                        @error('account_number')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">
                            Account Name
                        </label>
                        <input type="text" name="account_name"
                            value="{{ old('account_name', $configuration?->account_name) }}"
                            class="form-control @error('account_name') is-invalid @enderror rounded-0"
                            placeholder="Universitas Bhamada Slawi">
                        @error('account_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">
                            Regular Registration Fee
                        </label>
                        <div class="input-group">
                            <span class="input-group-text rounded-0">
                                Rp
                            </span>
                            <input type="number" name="regular_fee" min="0" step="0.01"
                                value="{{ old('regular_fee', $configuration?->regular_fee ?? 0) }}"
                                class="form-control @error('regular_fee') is-invalid @enderror rounded-0">
                        </div>
                        @error('regular_fee')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">
                            Student Registration Fee
                        </label>
                        <div class="input-group">
                            <span class="input-group-text rounded-0">
                                Rp
                            </span>
                            <input type="number" name="student_fee" min="0" step="0.01"
                                value="{{ old('student_fee', $configuration?->student_fee ?? 0) }}"
                                class="form-control @error('student_fee') is-invalid @enderror rounded-0">
                        </div>
                        @error('student_fee')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="card rounded-0 mb-3">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="bi bi-award me-2"></i>
                    Certificate Configuration
                </h3>
            </div>
            <div class="card-body">
                <div class="alert alert-secondary rounded-0">
                    <i class="bi bi-info-circle me-2"></i>
                    This information will be used on the
                    generated certificate PDF.
                </div>
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label">
                            Chair Name
                        </label>
                        <input type="text" name="chair_name"
                            value="{{ old('chair_name', $configuration?->chair_name) }}"
                            class="form-control @error('chair_name') is-invalid @enderror rounded-0"
                            placeholder="Dr. Budi Santoso, S.Kom., M.T.">
                        @error('chair_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">
                            Chair Title
                        </label>
                        <input type="text" name="chair_title"
                            value="{{ old('chair_title', $configuration?->chair_title) }}"
                            class="form-control @error('chair_title') is-invalid @enderror rounded-0"
                            placeholder="Conference Chair">
                        @error('chair_title')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="text-end mb-3">
            <button type="submit" class="btn btn-success btn-sm rounded-0">
                <i class="bi bi-check-circle me-1"></i>
                Save Configuration
            </button>
        </div>
    </form>
@endsection
