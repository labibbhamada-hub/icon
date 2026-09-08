@extends('layouts.admin')

@section('title', 'Certificates Management')

@section('header')

    <div class="row">

        <div class="col-sm-6">

            <h1 class="mb-0 fs-3">
                Certificates Management
            </h1>

        </div>

        <div class="col-sm-6">

            <nav aria-label="breadcrumb">

                <ol class="breadcrumb float-sm-end mb-0">

                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">
                            Dashboard
                        </a>
                    </li>

                    <li class="breadcrumb-item active" aria-current="page">
                        Certificates
                    </li>

                </ol>

            </nav>

        </div>

    </div>

@endsection


@section('content')

    <div class="card rounded-0">

        <div class="card-header">

            <h3 class="card-title">

                <i class="bi bi-award me-2"></i>
                Certificate List

            </h3>

            <div class="float-end d-flex gap-1">

                <a href="{{ route('admin.certificates.export') }}" class="btn btn-dark btn-sm rounded-0">
                    <i class="bi bi-file-earmark-excel me-1"></i>
                    Export Excel
                </a>

                <a href="{{ route('admin.certificates.create') }}" class="btn btn-success btn-sm rounded-0">
                    <i class="bi bi-plus-circle me-1"></i>
                    Generate Certificate
                </a>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead>

                        <tr>

                            <th width="50">
                                No
                            </th>

                            <th>
                                Certificate
                            </th>

                            <th>
                                Participant
                            </th>

                            <th>
                                Conference
                            </th>

                            <th>
                                Submission
                            </th>

                            <th>
                                Type
                            </th>

                            <th>
                                Issued
                            </th>

                            <th width="80">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse ($certificates as $certificate)

                            <tr>

                                {{-- No --}}
                                <td>

                                    {{ $certificates->firstItem() + $loop->index }}

                                </td>


                                {{-- Certificate --}}
                                <td>

                                    <div class="fw-semibold">
                                        {{ $certificate->certificate_number }}
                                    </div>

                                </td>


                                {{-- Participant --}}
                                <td>

                                    @if ($certificate->participant)
                                        <div class="fw-semibold">

                                            {{ $certificate->participant->full_name }}

                                        </div>

                                        @if ($certificate->participant->registration_number)
                                            <small class="text-muted d-block">

                                                {{ $certificate->participant->registration_number }}

                                            </small>
                                        @endif
                                    @else
                                        <span class="text-muted">
                                            -
                                        </span>
                                    @endif

                                </td>


                                {{-- Conference --}}
                                <td>

                                    @if ($certificate->conference)
                                        <strong>

                                            {{ $certificate->conference->short_name }}

                                        </strong>

                                        <small class="text-muted d-block">

                                            {{ $certificate->conference->year }}

                                        </small>
                                    @else
                                        <span class="text-muted">
                                            -
                                        </span>
                                    @endif

                                </td>


                                {{-- Submission --}}
                                <td>

                                    @if ($certificate->submission)
                                        <a href="{{ route('admin.submissions.show', $certificate->submission) }}"
                                            class="text-decoration-none fw-semibold">
                                            {{ $certificate->submission->submission_code }}
                                        </a>

                                        <small class="text-muted d-block">

                                            {{ \Illuminate\Support\Str::limit($certificate->submission->title, 50) }}

                                        </small>
                                    @else
                                        <span class="text-muted">
                                            General Certificate
                                        </span>
                                    @endif

                                </td>


                                {{-- Type --}}
                                <td>

                                    @php

                                        $typeLabels = [
                                            'participant' => 'Participant',

                                            'presenter' => 'Presenter',

                                            'speaker' => 'Speaker',

                                            'committee' => 'Committee',

                                            'reviewer' => 'Reviewer',
                                        ];

                                    @endphp


                                    <span class="badge text-bg-secondary rounded-0">
                                        {{ $typeLabels[$certificate->type] ?? ucfirst(str_replace('_', ' ', $certificate->type)) }}
                                    </span>

                                </td>


                                {{-- Issued --}}
                                <td>

                                    @if ($certificate->issued_at)
                                        <div>

                                            {{ $certificate->issued_at->format('d M Y') }}

                                        </div>

                                        <small class="text-muted">

                                            {{ $certificate->issued_at->format('H:i') }}

                                        </small>
                                    @else
                                        <span class="text-muted">
                                            -
                                        </span>
                                    @endif

                                </td>


                                {{-- Action --}}
                                <td>

                                    <a href="{{ route('admin.certificates.show', $certificate) }}"
                                        class="btn btn-info btn-sm rounded-0" title="View Certificate">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="8" class="text-center py-5">

                                    <div class="mb-2">

                                        <i class="bi bi-award display-5 text-muted"></i>

                                    </div>

                                    <h5 class="mb-1">
                                        No Certificates Found
                                    </h5>

                                    <p class="text-muted mb-3">
                                        No certificates have been generated yet.
                                    </p>

                                    <a href="{{ route('admin.certificates.create') }}" class="btn btn-success rounded-0">
                                        <i class="bi bi-plus-circle me-1"></i>
                                        Generate Certificate
                                    </a>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        @if ($certificates->hasPages())
            <div class="card-footer">

                {{ $certificates->links() }}

            </div>
        @endif

    </div>

@endsection
