@extends('layouts.participant')

@section('title', 'Certificate Detail')

@section('header')
    <div class="row align-items-center">

        <div class="col-sm-6">

            <div class="d-flex align-items-center gap-2">

                <a href="{{ route('participant.certificates.index') }}" class="btn btn-secondary btn-sm rounded-0">

                    <i class="bi bi-arrow-left"></i>

                </a>

                <h1 class="mb-0 fs-3">
                    Certificate Detail
                </h1>

            </div>

            <p class="text-muted mb-0 mt-1">
                View your certificate information.
            </p>

        </div>

        <div class="col-sm-6">

            <ol class="breadcrumb float-sm-end mb-0">

                <li class="breadcrumb-item">

                    <a href="{{ route('participant.dashboard') }}">
                        Dashboard
                    </a>

                </li>

                <li class="breadcrumb-item">

                    <a href="{{ route('participant.certificates.index') }}">
                        Certificates
                    </a>

                </li>

                <li class="breadcrumb-item active">
                    Detail
                </li>

            </ol>

        </div>

    </div>
@endsection

@section('content')
    <div class="row">

        <div class="col-lg-8">

            <div class="card rounded-0">

                <div class="card-header">

                    <h3 class="card-title">

                        <i class="bi bi-award me-2"></i>

                        Certificate Information

                    </h3>

                    <div class="card-tools">

                        @if ($certificate->file_path)
                            <a href="{{ route('participant.certificates.download', $certificate) }}"
                                class="btn btn-success btn-sm">

                                <i class="bi bi-download me-1"></i>

                                Download PDF

                            </a>
                        @endif

                    </div>

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

                        <table class="table table-borderless">

                            <tbody>

                                <tr>

                                    <th width="220">
                                        Certificate Number
                                    </th>

                                    <td>

                                        <strong>

                                            {{ $certificate->certificate_number }}

                                        </strong>

                                    </td>

                                </tr>


                                <tr>

                                    <th>
                                        Participant
                                    </th>

                                    <td>

                                        {{ $certificate->participant?->full_name ?? '—' }}

                                    </td>

                                </tr>


                                <tr>

                                    <th>
                                        Conference
                                    </th>

                                    <td>

                                        {{ $certificate->conference?->name ?? '—' }}

                                    </td>

                                </tr>


                                <tr>

                                    <th>
                                        Certificate Type
                                    </th>

                                    <td>

                                        <span class="badge text-bg-primary rounded-0">

                                            {{ ucfirst($certificate->type) }}

                                        </span>

                                    </td>

                                </tr>


                                <tr>

                                    <th>
                                        Issued Date
                                    </th>

                                    <td>

                                        {{ $certificate->issued_at?->format('d F Y') ?? '—' }}

                                    </td>

                                </tr>

                            </tbody>

                        </table>

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

                    <a href="{{ route('participant.certificates.index') }}" class="btn btn-secondary">

                        Back

                    </a>

                    @if ($certificate->file_path)
                        <a href="{{ route('participant.certificates.download', $certificate) }}" class="btn btn-success">

                            <i class="bi bi-download me-1"></i>

                            Download Certificate

                        </a>
                    @endif

                </div>

            </div>

        </div>


        <div class="col-lg-4">

            <div class="card rounded-0">

                <div class="card-header">

                    <h3 class="card-title">

                        <i class="bi bi-info-circle me-2"></i>

                        Status

                    </h3>

                </div>

                <div class="card-body text-center">

                    @if ($certificate->file_path)
                        <span class="badge text-bg-success rounded-0 fs-6">

                            Ready

                        </span>

                        <p class="text-muted mt-3 mb-0">

                            Your certificate is ready to download.

                        </p>
                    @else
                        <span class="badge text-bg-warning rounded-0 fs-6">

                            Processing

                        </span>

                        <p class="text-muted mt-3 mb-0">

                            Your certificate is currently being prepared.

                        </p>
                    @endif

                </div>

            </div>

        </div>

    </div>
@endsection
