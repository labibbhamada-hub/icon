@extends('layouts.admin')

@section('title', 'Certificates')

@section('header')
    <div class="row align-items-center">
        <div class="col-sm-6">
            <h1 class="mb-0 fs-3">Certificates Management</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.dashboard') }}">
                        Dashboard
                    </a>
                </li>
                <li class="breadcrumb-item active">
                    Certificates
                </li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="card rounded-0">
        <div class="card-header">
            <h3 class="card-title">
                Certificate List
            </h3>
            <div class="float-end">
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
                            <th width="40">No</th>
                            <th>Certificate</th>
                            <th>Participant</th>
                            <th>Conference</th>
                            <th>Type</th>
                            <th>Issued</th>
                            <th width="40">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($certificates as $certificate)
                            <tr>
                                <td>
                                    {{ $certificates->firstItem() + $loop->index }}
                                </td>
                                <td>
                                    <strong>
                                        {{ $certificate->certificate_number }}
                                    </strong>
                                </td>
                                <td>
                                    {{ $certificate->participant?->full_name ?? '—' }}
                                    <small class="text-muted d-block">
                                        {{ $certificate->participant?->registration_number ?? '' }}
                                    </small>
                                </td>
                                <td>
                                    {{ $certificate->conference?->short_name ?? '—' }}
                                </td>
                                <td>
                                    {{ ucfirst($certificate->type) }}
                                </td>
                                <td>
                                    {{ $certificate->issued_at?->format('d M Y') ?? '—' }}
                                </td>
                                <td>
                                    <a href="{{ route('admin.certificates.show', $certificate) }}"
                                        class="btn btn-info btn-sm rounded-0">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="bi bi-award display-5 text-muted"></i>
                                    <h5 class="mt-3">
                                        No Certificates
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
