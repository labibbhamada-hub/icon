@extends('layouts.participant')

@section('title', 'My Certificates')

@section('header')
    <div class="row align-items-center">
        <div class="col-sm-6">
            <h3 class="mb-0">
                My Certificates
            </h3>
            <p class="text-muted mb-0 mt-1">
                Download your conference certificates.
            </p>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('participant.dashboard') }}">
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
                <i class="bi bi-award me-2"></i>
                Certificate List
            </h3>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>
                                Certificate Number
                            </th>
                            <th>
                                Conference
                            </th>
                            <th>Type</th>
                            <th>Issued</th>
                            <th width="120">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($certificates as $certificate)
                            <tr>
                                <td>
                                    <strong>
                                        {{ $certificate->certificate_number }}
                                    </strong>
                                </td>
                                <td>
                                    {{ $certificate->conference?->name ?? '—' }}
                                </td>
                                <td>
                                    <span class="badge text-bg-primary rounded-0">
                                        {{ ucfirst($certificate->type) }}
                                    </span>
                                </td>
                                <td>
                                    {{ $certificate->issued_at?->format('d M Y') ?? '—' }}
                                </td>
                                <td>
                                    @if ($certificate->file_path)
                                        <a href="{{ route('participant.certificates.show', $certificate) }}"
                                            class="btn btn-info btn-sm rounded-0" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('participant.certificates.download', $certificate) }}"
                                            class="btn btn-success btn-sm rounded-0" title="Download">
                                            <i class="bi bi-download"></i>
                                        </a>
                                    @else
                                        <span class="badge text-bg-warning rounded-0">
                                            Processing
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <i class="bi bi-award display-5 text-muted"></i>
                                    <h5 class="mt-3">
                                        No Certificates Yet
                                    </h5>
                                    <p class="text-muted mb-0">
                                        Your certificates will appear
                                        here once they have been issued.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
