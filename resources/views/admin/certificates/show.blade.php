@extends('layouts.admin')

@section('title', 'Certificate Detail')

@section('header')

    <div class="row">

        <div class="col-sm-6">

            <div class="d-flex align-items-center gap-2">

                <a href="{{ route('admin.certificates.index') }}" class="btn btn-secondary btn-sm rounded-0" title="Back">
                    <i class="bi bi-arrow-left"></i>
                </a>

                <h1 class="mb-0 fs-3">
                    Certificate Detail
                </h1>

            </div>

        </div>


        <div class="col-sm-6">

            <nav aria-label="breadcrumb">

                <ol class="breadcrumb float-sm-end mb-0">

                    <li class="breadcrumb-item">

                        <a href="{{ route('admin.dashboard') }}">
                            Dashboard
                        </a>

                    </li>

                    <li class="breadcrumb-item">

                        <a href="{{ route('admin.certificates.index') }}">
                            Certificates
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

    @php

        $typeLabels = [
            'participant' => 'Participant',
            'presenter' => 'Presenter',
            'speaker' => 'Speaker',
            'committee' => 'Committee',
            'reviewer' => 'Reviewer',
        ];

        $typeLabel = $typeLabels[$certificate->type] ?? ucfirst(str_replace('_', ' ', $certificate->type));

    @endphp


    {{-- ============================================================
        CERTIFICATE
    ============================================================= --}}

    <div class="card rounded-0 mb-3">

        <div class="card-header">

            <h3 class="card-title">

                <i class="bi bi-award me-2"></i>

                {{ $certificate->certificate_number }}

            </h3>


            <div class="float-end d-flex gap-1">

                @if ($certificate->file_path)
                    <a href="{{ route('admin.certificates.download', $certificate) }}"
                        class="btn btn-success btn-sm rounded-0">
                        <i class="bi bi-download me-1"></i>
                        Download PDF
                    </a>
                @endif


                <form action="{{ route('admin.certificates.regenerate', $certificate) }}" method="POST"
                    class="d-inline regenerate-certificate-form">

                    @csrf

                    <button type="submit" class="btn btn-warning btn-sm rounded-0">
                        <i class="bi bi-arrow-repeat me-1"></i>
                        Regenerate PDF
                    </button>

                </form>

            </div>

        </div>


        <div class="card-body">

            <div class="row g-3">

                {{-- Certificate Number --}}
                <div class="col-md-6">

                    <div class="border rounded-0 p-3 h-100">

                        <div class="text-muted small mb-1">
                            Certificate Number
                        </div>

                        <div class="fw-bold">

                            {{ $certificate->certificate_number }}

                        </div>

                    </div>

                </div>


                {{-- Type --}}
                <div class="col-md-3">

                    <div class="border rounded-0 p-3 h-100">

                        <div class="text-muted small mb-1">
                            Certificate Type
                        </div>

                        <span class="badge text-bg-primary rounded-0">
                            {{ $typeLabel }}
                        </span>

                    </div>

                </div>


                {{-- PDF Status --}}
                <div class="col-md-3">

                    <div class="border rounded-0 p-3 h-100">

                        <div class="text-muted small mb-1">
                            PDF Status
                        </div>

                        @if ($certificate->file_path)
                            <span class="badge text-bg-success rounded-0">
                                <i class="bi bi-check-circle me-1"></i>
                                Available
                            </span>
                        @else
                            <span class="badge text-bg-danger rounded-0">
                                <i class="bi bi-x-circle me-1"></i>
                                Missing
                            </span>
                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
        PARTICIPANT & CONFERENCE
    ============================================================= --}}

    <div class="card rounded-0 mb-3">

        <div class="card-header">

            <h3 class="card-title">

                <i class="bi bi-person-badge me-2"></i>

                Certificate Information

            </h3>

        </div>


        <div class="card-body">

            <div class="row">

                {{-- Participant --}}
                <div class="col-md-6 mb-3">

                    <div class="text-muted small mb-1">
                        Participant
                    </div>

                    @if ($certificate->participant)

                        <div class="fw-semibold">

                            {{ $certificate->participant->full_name }}

                        </div>

                        @if ($certificate->participant->registration_number)
                            <small class="text-muted">

                                {{ $certificate->participant->registration_number }}

                            </small>
                        @endif
                    @else
                        <span class="text-muted">
                            -
                        </span>

                    @endif

                </div>


                {{-- Conference --}}
                <div class="col-md-6 mb-3">

                    <div class="text-muted small mb-1">
                        Conference
                    </div>

                    @if ($certificate->conference)
                        <div class="fw-semibold">

                            {{ $certificate->conference->name }}

                        </div>

                        <small class="text-muted">

                            {{ $certificate->conference->short_name }}
                            ({{ $certificate->conference->year }})

                        </small>
                    @else
                        <span class="text-muted">
                            -
                        </span>
                    @endif

                </div>


                {{-- Issued At --}}
                <div class="col-md-6">

                    <div class="text-muted small mb-1">
                        Issued At
                    </div>

                    <div>

                        {{ $certificate->issued_at?->format('d F Y H:i') ?? '-' }}

                    </div>

                </div>


                {{-- Certificate Type --}}
                <div class="col-md-6">

                    <div class="text-muted small mb-1">
                        Certificate Type
                    </div>

                    <div>

                        <span class="badge text-bg-secondary rounded-0">
                            {{ $typeLabel }}
                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
        RELATED SUBMISSION
    ============================================================= --}}

    @if ($certificate->submission)
        <div class="card rounded-0 mb-3">

            <div class="card-header">

                <h3 class="card-title">

                    <i class="bi bi-file-earmark-text me-2"></i>

                    Related Submission

                </h3>

                <div class="float-end">

                    <a href="{{ route('admin.submissions.show', $certificate->submission) }}"
                        class="btn btn-info btn-sm rounded-0">
                        <i class="bi bi-eye me-1"></i>
                        View Submission
                    </a>

                </div>

            </div>


            <div class="card-body">

                <div class="row">

                    <div class="col-lg-8">

                        <div class="text-muted small mb-1">
                            Submission
                        </div>

                        <div class="fw-semibold">

                            {{ $certificate->submission->submission_code }}

                        </div>

                        <div class="mt-1">

                            {{ $certificate->submission->title }}

                        </div>

                    </div>


                    <div class="col-lg-4 mt-3 mt-lg-0">

                        <div class="text-muted small mb-1">
                            Submission Status
                        </div>

                        @php

                            $submissionStatusClasses = [
                                'published' => 'dark',

                                'accepted' => 'success',

                                'camera_ready' => 'info',

                                'rejected' => 'danger',

                                'revision' => 'warning',

                                'under_review' => 'warning',

                                'submitted' => 'primary',

                                'draft' => 'secondary',
                            ];

                            $submissionStatusClass =
                                $submissionStatusClasses[$certificate->submission->status] ?? 'secondary';

                        @endphp


                        <span class="badge text-bg-{{ $submissionStatusClass }} rounded-0">
                            {{ ucfirst(str_replace('_', ' ', $certificate->submission->status)) }}
                        </span>

                    </div>

                </div>

            </div>

        </div>
    @endif


    {{-- ============================================================
        PDF INFORMATION
    ============================================================= --}}

    <div class="card rounded-0">

        <div class="card-header">

            <h3 class="card-title">

                <i class="bi bi-file-earmark-pdf me-2"></i>

                Certificate File

            </h3>

        </div>


        <div class="card-body">

            @if ($certificate->file_path)
                <div class="d-flex align-items-center">

                    <div class="border rounded-0 d-flex align-items-center justify-content-center me-3"
                        style="width: 64px; height: 64px;">

                        <i class="bi bi-file-earmark-pdf text-danger fs-2"></i>

                    </div>


                    <div>

                        <div class="fw-semibold">
                            Certificate PDF
                        </div>

                        <small class="text-muted d-block">
                            {{ basename($certificate->file_path) }}
                        </small>

                    </div>

                </div>


                <div class="mt-3">

                    <a href="{{ route('admin.certificates.download', $certificate) }}"
                        class="btn btn-outline-danger btn-sm rounded-0">
                        <i class="bi bi-download me-1"></i>
                        Download Certificate PDF
                    </a>

                </div>
            @else
                <div class="text-center py-4">

                    <i class="bi bi-file-earmark-x display-5 text-muted"></i>

                    <h5 class="mt-2 mb-1">
                        Certificate PDF Not Available
                    </h5>

                    <p class="text-muted mb-3">
                        The certificate record exists, but its PDF file is missing.
                    </p>

                    <form action="{{ route('admin.certificates.regenerate', $certificate) }}" method="POST"
                        class="d-inline regenerate-certificate-form">

                        @csrf

                        <button type="submit" class="btn btn-warning btn-sm rounded-0">
                            <i class="bi bi-arrow-repeat me-1"></i>
                            Generate PDF
                        </button>

                    </form>

                </div>
            @endif

        </div>

    </div>

@endsection


@push('scripts')
    <script>
        document
            .querySelectorAll('.regenerate-certificate-form')
            .forEach(function(form) {

                form.addEventListener(
                    'submit',
                    function(event) {

                        event.preventDefault();

                        Swal.fire({

                            title: 'Regenerate Certificate?',

                            text: 'The existing PDF will be replaced with a new version.',

                            icon: 'question',

                            showCancelButton: true,

                            confirmButtonText: 'Yes, regenerate',

                            cancelButtonText: 'Cancel',

                            confirmButtonColor: '#f0ad4e',

                            cancelButtonColor: '#6c757d',

                        }).then(function(result) {

                            if (result.isConfirmed) {

                                form.submit();

                            }

                        });

                    }
                );

            });
    </script>
@endpush
