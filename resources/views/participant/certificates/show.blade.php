@extends('layouts.participant')

@section('title', 'Certificate Detail')

@section('header')
    <div class="row align-items-top">

        <div class="col-sm-6">
            <div class="d-flex gap-2">
                <a href="{{ route('participant.certificates.index') }}" class="btn btn-secondary rounded-0">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h1 class="mb-0 fs-3">
                    Certificate Detail
                </h1>
            </div>
            <p class="text-muted mb-0">
                View your certificate information.
            </p>
        </div>
    </div>
@endsection

@section('content')
    <div class="card rounded-0">
        <div class="card-header">
            <h3 class="card-title">
                <i class="bi bi-award me-2"></i>
                Certificate Information
            </h3>
        </div>
        <div class="card-body">
            <div class="border rounded-0 bg-light p-4 text-center">
                <i class="bi bi-award display-3 text-primary"></i>
                <h3 class="fw-bold mt-3">
                    {{ ucfirst($certificate->type) }}
                    Certificate
                </h3>
                <p class="text-muted mb-0">
                    {{ $certificate->conference?->name ?? '—' }}
                </p>
            </div>
            <div class="mt-4">
                <div class="row mb-2">
                    <div class="col-md-4">
                        Certificate Number
                    </div>
                    <div class="col-md-6">
                        <strong>{{ $certificate->certificate_number }}</strong>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4">
                        Participant
                    </div>
                    <div class="col-md-6">
                        {{ $certificate->participant?->full_name ?? '—' }}
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4">
                        Conference
                    </div>
                    <div class="col-md-6">
                        {{ $certificate->conference?->name ?? '—' }}
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4">
                        Certificate Type
                    </div>
                    <div class="col-md-6">
                        <span class="badge text-bg-primary rounded-0">
                            {{ ucfirst($certificate->type) }}
                        </span>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4">
                        Issued Date
                    </div>
                    <div class="col-md-6">
                        {{ $certificate->issued_at?->format('d F Y') ?? '—' }}
                    </div>
                </div>
            </div>
            @if ($certificate->submission)
                <div class="mt-4">
                    <h5 class="fw-semibold border-bottom pb-2">
                        <i class="bi bi-file-earmark-text me-2"></i>
                        Related Submission
                    </h5>
                    <div class="mt-3">
                        <div class="fw-semibold">
                            {{ $certificate->submission->submission_code }}
                        </div>
                        <div class="mt-1">
                            {{ $certificate->submission->title }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <div class="card-footer text-end">
            <a href="{{ route('certificates.verify', [
                'certificate_number' => $certificate->certificate_number,
            ]) }}"
                target="_blank" class="btn btn-outline-primary rounded-0">
                <i class="bi bi-patch-check me-1"></i>
                Verify
            </a>
            @if ($certificate->file_path)
                <a href="{{ route('participant.certificates.download', $certificate) }}" class="btn btn-success rounded-0">
                    <i class="bi bi-download me-1"></i>
                    Download Certificate
                </a>
            @endif
        </div>
    </div>
@endsection
