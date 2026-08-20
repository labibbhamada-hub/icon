@extends('layouts.admin')

@section('title', 'Certificate Detail')

@section('header')
    <div class="row align-items-center">
        <div class="col-sm-6">
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('admin.certificates.index') }}" class="btn btn-secondary btn-sm rounded-0">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h1 class="mb-0 fs-3">
                    Certificate Detail
                </h1>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card rounded-0">
        <div class="card-header">
            <h3 class="card-title">
                <i class="bi bi-award me-2"></i>
                {{ $certificate->certificate_number }}
            </h3>
            <div class="float-end">
                @if ($certificate->file_path)
                    <a href="{{ route('admin.certificates.download', $certificate) }}" class="btn btn-success btn-sm rounded-0">
                        <i class="bi bi-download me-1"></i>
                        Download PDF
                    </a>
                @endif
                <form action="{{ route('admin.certificates.regenerate', $certificate) }}"
                    method="POST" class="d-inline regenerate-certificate-form">
                    @csrf
                    <button type="submit" class="btn btn-warning btn-sm rounded-0">
                        <i class="bi bi-arrow-repeat me-1"></i>
                        Regenerate PDF
                    </button>
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <th width="180">
                                    Participant
                                </th>
                                <td>
                                    {{ $certificate->participant?->full_name }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Registration
                                </th>
                                <td>
                                    {{ $certificate->participant?->registration_number }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Conference
                                </th>
                                <td>
                                    {{ $certificate->conference?->name }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Type
                                </th>
                                <td>
                                    {{ ucfirst($certificate->type) }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Issued
                                </th>
                                <td>
                                    {{ $certificate->issued_at?->format('d M Y') ?? '—' }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    <div class="border rounded-0 p-4">
                        <h5 class="fw-bold">
                            Certificate
                        </h5>
                        <p class="text-muted">
                            Certificate number:
                        </p>
                        <strong>
                            {{ $certificate->certificate_number }}
                        </strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('.regenerate-certificate-form')
            .forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    event.preventDefault();
                    Swal.fire({
                        title: 'Regenerate Certificate?',
                        text: 'The existing PDF will be replaced with a new version.',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, regenerate',
                        cancelButtonText: 'Cancel',
                        confirmButtonColor: '#f0ad4e',
                        cancelButtonColor: '#6c757d'
                    }).then(function(result) {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
    </script>
@endpush
