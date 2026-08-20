@extends('layouts.admin')

@section('title', 'Conference Settings')

@section('header')
    <div class="row align-items-center">

        <div class="col-sm-6">

            <div class="d-flex align-items-center gap-2">

                <a href="{{ route('admin.conferences.show', $conference) }}" class="btn btn-secondary btn-sm rounded-0"
                    title="Back">

                    <i class="bi bi-arrow-left"></i>

                </a>

                <h1 class="mb-0 fs-3">
                    Conference Settings
                </h1>

            </div>

            <p class="text-muted mb-0 mt-1">
                Manage conference workflow and feature access.
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
                    Settings
                </li>

            </ol>

        </div>

    </div>
@endsection

@section('content')
    @if ($errors->any())

        <div class="alert alert-danger">

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

        </div>

    @endif


    <form action="{{ route('admin.conferences.settings.update', $conference) }}" method="POST">

        @csrf

        @method('PUT')


        {{-- Conference Info --}}
        <div class="card rounded-0">

            <div class="card-header">

                <h3 class="card-title">

                    <i class="bi bi-calendar-event me-2"></i>

                    Conference

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


                    <div class="col-md-4">

                        <small class="text-muted d-block">
                            Short Name
                        </small>

                        <strong>
                            {{ $conference->short_name }}
                        </strong>

                    </div>

                </div>

            </div>

        </div>


        {{-- General --}}
        <div class="card rounded-0 mt-3">

            <div class="card-header">

                <h3 class="card-title">

                    <i class="bi bi-toggles me-2"></i>

                    General Settings

                </h3>

            </div>


            <div class="card-body">

                <div class="row g-4">

                    {{-- Active --}}
                    <div class="col-md-6">

                        <div class="border rounded-0 p-3 h-100">

                            <div class="form-check form-switch">

                                <input type="hidden" name="is_active" value="0">

                                <input type="checkbox" class="form-check-input" id="is_active" name="is_active"
                                    value="1" @checked(old('is_active', $settings->is_active))>

                                <label class="form-check-label fw-semibold" for="is_active">

                                    Conference Active

                                </label>

                            </div>

                            <div class="form-text">

                                Enable or disable this conference
                                from the system.

                            </div>

                        </div>

                    </div>


                    {{-- Published --}}
                    <div class="col-md-6">

                        <div class="border rounded-0 p-3 h-100">

                            <div class="form-check form-switch">

                                <input type="hidden" name="published" value="0">

                                <input type="checkbox" class="form-check-input" id="published" name="published"
                                    value="1" @checked(old('published', $settings->published))>

                                <label class="form-check-label fw-semibold" for="published">

                                    Published

                                </label>

                            </div>

                            <div class="form-text">

                                Make this conference visible
                                publicly.

                            </div>

                        </div>

                    </div>


                    {{-- Maintenance --}}
                    <div class="col-md-12">

                        <div class="border border-warning bg-warning-subtle rounded-0 p-3">

                            <div class="form-check form-switch">

                                <input type="hidden" name="maintenance_mode" value="0">

                                <input type="checkbox" class="form-check-input" id="maintenance_mode"
                                    name="maintenance_mode" value="1" @checked(old('maintenance_mode', $settings->maintenance_mode))>

                                <label class="form-check-label fw-semibold" for="maintenance_mode">

                                    Maintenance Mode

                                </label>

                            </div>

                            <div class="form-text">

                                Temporarily disable conference
                                access while maintenance is in progress.

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Workflow --}}
        <div class="card rounded-0 mt-3">

            <div class="card-header">

                <h3 class="card-title">

                    <i class="bi bi-diagram-3 me-2"></i>

                    Conference Workflow

                </h3>

            </div>


            <div class="card-body">

                <div class="row g-4">

                    {{-- Registration --}}
                    <div class="col-md-6">

                        <div class="border rounded-0 p-3 h-100">

                            <div class="form-check form-switch">

                                <input type="hidden" name="registration_enabled" value="0">

                                <input type="checkbox" class="form-check-input" id="registration_enabled"
                                    name="registration_enabled" value="1" @checked(old('registration_enabled', $settings->registration_enabled))>

                                <label class="form-check-label fw-semibold" for="registration_enabled">

                                    Registration

                                </label>

                            </div>

                            <div class="form-text">

                                Allow participants to register
                                for this conference.

                            </div>

                        </div>

                    </div>


                    {{-- Submission --}}
                    <div class="col-md-6">

                        <div class="border rounded-0 p-3 h-100">

                            <div class="form-check form-switch">

                                <input type="hidden" name="submission_enabled" value="0">

                                <input type="checkbox" class="form-check-input" id="submission_enabled"
                                    name="submission_enabled" value="1" @checked(old('submission_enabled', $settings->submission_enabled))>

                                <label class="form-check-label fw-semibold" for="submission_enabled">

                                    Submission

                                </label>

                            </div>

                            <div class="form-text">

                                Allow registered participants
                                to submit papers.

                            </div>

                        </div>

                    </div>


                    {{-- Payment --}}
                    <div class="col-md-6">

                        <div class="border rounded-0 p-3 h-100">

                            <div class="form-check form-switch">

                                <input type="hidden" name="payment_enabled" value="0">

                                <input type="checkbox" class="form-check-input" id="payment_enabled"
                                    name="payment_enabled" value="1" @checked(old('payment_enabled', $settings->payment_enabled))>

                                <label class="form-check-label fw-semibold" for="payment_enabled">

                                    Payment

                                </label>

                            </div>

                            <div class="form-text">

                                Allow participants to submit
                                payment proof.

                            </div>

                        </div>

                    </div>


                    {{-- Review --}}
                    <div class="col-md-6">

                        <div class="border rounded-0 p-3 h-100">

                            <div class="form-check form-switch">

                                <input type="hidden" name="review_enabled" value="0">

                                <input type="checkbox" class="form-check-input" id="review_enabled"
                                    name="review_enabled" value="1" @checked(old('review_enabled', $settings->review_enabled))>

                                <label class="form-check-label fw-semibold" for="review_enabled">

                                    Review

                                </label>

                            </div>

                            <div class="form-text">

                                Enable peer review workflow.

                            </div>

                        </div>

                    </div>


                    {{-- Certificate --}}
                    <div class="col-md-6">

                        <div class="border rounded-0 p-3 h-100">

                            <div class="form-check form-switch">

                                <input type="hidden" name="certificate_enabled" value="0">

                                <input type="checkbox" class="form-check-input" id="certificate_enabled"
                                    name="certificate_enabled" value="1" @checked(old('certificate_enabled', $settings->certificate_enabled))>

                                <label class="form-check-label fw-semibold" for="certificate_enabled">

                                    Certificate

                                </label>

                            </div>

                            <div class="form-text">

                                Enable certificate generation
                                and access.

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Actions --}}
        <div class="card rounded-0 mt-3">

            <div class="card-body text-end">

                <a href="{{ route('admin.conferences.show', $conference) }}" class="btn btn-secondary">

                    Cancel

                </a>

                <button type="submit" class="btn btn-success">

                    <i class="bi bi-check-circle me-1"></i>

                    Save Settings

                </button>

            </div>

        </div>

    </form>
@endsection
