@extends('layouts.admin')

@section('title', 'Participant Detail')

@section('header')

    <div class="row">

        <div class="col-sm-6 d-flex align-items-center gap-2">

            <a href="{{ route('admin.participants.index') }}" class="btn btn-secondary btn-sm rounded-0" title="Back">
                <i class="bi bi-arrow-left"></i>
            </a>

            <h1 class="mb-0 fs-3">
                Participant Detail
            </h1>

        </div>

        <div class="col-sm-6">

            <nav aria-label="breadcrumb">

                <ol class="breadcrumb float-sm-end">

                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">
                            Dashboard
                        </a>
                    </li>

                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.participants.index') }}">
                            Participants
                        </a>
                    </li>

                    <li class="breadcrumb-item active" aria-current="page">
                        Detail
                    </li>

                </ol>

            </nav>

        </div>

    </div>

@endsection

@section('content')

    {{-- Participant Summary --}}
    <div class="card rounded-0">

        <div class="card-header">

            <h3 class="card-title">
                <i class="bi bi-person-vcard me-2"></i>
                Participant Information
            </h3>

            <div class="float-end">

                <a href="{{ route('admin.participants.edit', $participant) }}" class="btn btn-warning btn-sm rounded-0">
                    <i class="bi bi-pencil me-1"></i>
                    Edit Participant
                </a>

            </div>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-lg-6">

                    <div class="border rounded-0 p-3 h-100">

                        <h5 class="fw-bold mb-3">
                            Registration
                        </h5>

                        <dl class="row mb-0">

                            <dt class="col-sm-5">
                                Registration Number
                            </dt>

                            <dd class="col-sm-7 fw-semibold">
                                {{ $participant->registration_number }}
                            </dd>

                            <dt class="col-sm-5">
                                Conference
                            </dt>

                            <dd class="col-sm-7">

                                @if ($participant->conference)
                                    <strong>
                                        {{ $participant->conference->name }}
                                    </strong>

                                    <small class="text-muted d-block">
                                        {{ $participant->conference->short_name }}
                                        ({{ $participant->conference->year }})
                                    </small>
                                @else
                                    -
                                @endif

                            </dd>

                            <dt class="col-sm-5">
                                Registration Type
                            </dt>

                            <dd class="col-sm-7">

                                @if ($participant->registrationType)
                                    <strong>
                                        {{ $participant->registrationType->name }}
                                    </strong>

                                    <small class="text-muted d-block">
                                        {{ ucfirst($participant->registrationType->category) }}
                                    </small>
                                @else
                                    -
                                @endif

                            </dd>

                            <dt class="col-sm-5">
                                Attendance
                            </dt>

                            <dd class="col-sm-7">
                                {{ ucfirst($participant->attendance_type) }}
                            </dd>

                            <dt class="col-sm-5">
                                Registration Status
                            </dt>

                            <dd class="col-sm-7">

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

                            </dd>

                            <dt class="col-sm-5">
                                Registered At
                            </dt>

                            <dd class="col-sm-7">
                                {{ $participant->registered_at?->format('d F Y H:i') ?? '-' }}
                            </dd>

                        </dl>

                    </div>

                </div>

                <div class="col-lg-6 mt-3 mt-lg-0">

                    <div class="border rounded-0 p-3 h-100">

                        <h5 class="fw-bold mb-3">
                            Personal Information
                        </h5>

                        <dl class="row mb-0">

                            <dt class="col-sm-4">
                                Full Name
                            </dt>

                            <dd class="col-sm-8">
                                {{ $participant->full_name }}
                            </dd>

                            <dt class="col-sm-4">
                                Email
                            </dt>

                            <dd class="col-sm-8">

                                <a href="mailto:{{ $participant->email }}">
                                    {{ $participant->email }}
                                </a>

                            </dd>

                            <dt class="col-sm-4">
                                Phone
                            </dt>

                            <dd class="col-sm-8">
                                {{ $participant->phone ?: '-' }}
                            </dd>

                            <dt class="col-sm-4">
                                Participant Type
                            </dt>

                            <dd class="col-sm-8">
                                {{ ucfirst($participant->participant_type) }}
                            </dd>

                            <dt class="col-sm-4">
                                Institution
                            </dt>

                            <dd class="col-sm-8">
                                {{ $participant->institution ?: '-' }}
                            </dd>

                            <dt class="col-sm-4">
                                Department
                            </dt>

                            <dd class="col-sm-8">
                                {{ $participant->department ?: '-' }}
                            </dd>

                            <dt class="col-sm-4">
                                Location
                            </dt>

                            <dd class="col-sm-8">

                                {{ $participant->city ?: '-' }},
                                {{ $participant->country }}

                            </dd>

                        </dl>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Related Statistics --}}
    <div class="row">

        <div class="col-md-4">

            <div class="small-box text-bg-primary rounded-0">

                <div class="inner">

                    <h3>
                        {{ $participant->submissions->count() }}
                    </h3>

                    <p>
                        Submissions
                    </p>

                </div>

                <div class="icon">
                    <i class="bi bi-file-earmark-text"></i>
                </div>

            </div>

        </div>

        <div class="col-md-4">

            <div class="small-box text-bg-success rounded-0">

                <div class="inner">

                    <h3>
                        {{ $participant->payments->count() }}
                    </h3>

                    <p>
                        Payments
                    </p>

                </div>

                <div class="icon">
                    <i class="bi bi-credit-card"></i>
                </div>

            </div>

        </div>

        <div class="col-md-4">

            <div class="small-box text-bg-warning rounded-0">

                <div class="inner">

                    <h3>
                        {{ $participant->certificates->count() }}
                    </h3>

                    <p>
                        Certificates
                    </p>

                </div>

                <div class="icon">
                    <i class="bi bi-award"></i>
                </div>

            </div>

        </div>

    </div>


    {{-- Notes --}}
    @if ($participant->notes)
        <div class="card rounded-0">

            <div class="card-header">

                <h3 class="card-title">
                    <i class="bi bi-sticky me-2"></i>
                    Notes
                </h3>

            </div>

            <div class="card-body">
                {!! nl2br(e($participant->notes)) !!}
            </div>

        </div>
    @endif


    <div class="card rounded-0">

        <div class="card-footer">

            <a href="{{ route('admin.participants.index') }}" class="btn btn-secondary btn-sm rounded-0">
                <i class="bi bi-arrow-left me-1"></i>
                Back to Participants
            </a>

        </div>

    </div>

@endsection
