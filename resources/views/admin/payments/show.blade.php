@extends('layouts.admin')

@section('title', 'Payment Detail')

@section('header')
    <div class="row align-items-center">
        <div class="col-sm-6">
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary btn-sm rounded-0">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h1 class="mb-0 fs-3">
                    Payment Detail
                </h1>
            </div>
            <p class="text-muted mb-0 mt-1">
                Review and verify participant payment.
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
                    <a href="{{ route('admin.payments.index') }}">
                        Payments
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
                        <i class="bi bi-credit-card me-2"></i>
                        {{ $payment->payment_code }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <div class="border rounded-0 p-3 text-center h-100">
                                <small class="text-muted d-block">
                                    Payment Amount
                                </small>
                                <div class="fs-3 fw-bold mt-2">
                                    Rp
                                    {{ number_format($payment->amount, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="border rounded-0 p-3 text-center h-100">
                                <small class="text-muted d-block">
                                    Status
                                </small>
                                <div class="mt-3">
                                    @if ($payment->status === 'verified')
                                        <span class="badge text-bg-success rounded-0 fs-6">
                                            Verified
                                        </span>
                                    @elseif ($payment->status === 'rejected')
                                        <span class="badge text-bg-danger rounded-0 fs-6">
                                            Rejected
                                        </span>
                                    @else
                                        <span class="badge text-bg-warning rounded-0 fs-6">
                                            Pending
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="border rounded-0 p-3 text-center h-100">
                                <small class="text-muted d-block">
                                    Payment Method
                                </small>
                                <div class="fw-semibold mt-3">
                                    {{ ucwords(str_replace('_', ' ', $payment->payment_method)) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body border-top">
                    <h5 class="fw-semibold mb-4">
                        Payment Information
                    </h5>
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <strong>
                                Participant
                            </strong>
                        </div>
                        <div class="col-md-8">
                            @if ($payment->participant)
                                <strong>
                                    {{ $payment->participant->full_name }}
                                </strong>
                                <small class="text-muted d-block">
                                    {{ $payment->participant->registration_number }}
                                </small>
                            @else
                                —
                            @endif
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <strong>
                                Conference
                            </strong>
                        </div>
                        <div class="col-md-8">
                            {{ $payment->participant?->conference?->name ?? '—' }}
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <strong>
                                Paid At
                            </strong>
                        </div>
                        <div class="col-md-8">
                            {{ $payment->paid_at?->format('d F Y H:i') ?? '—' }}
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <strong>
                                Verified At
                            </strong>
                        </div>
                        <div class="col-md-8">
                            {{ $payment->verified_at?->format('d F Y H:i') ?? '—' }}
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <strong>
                                Verified By
                            </strong>
                        </div>
                        <div class="col-md-8">
                            {{ $payment->verifier?->name ?? '—' }}
                        </div>
                    </div>
                </div>
                @if ($payment->notes)
                    <div class="card-body border-top">
                        <h5 class="fw-semibold mb-2">
                            Notes
                        </h5>
                        <div>
                            {!! nl2br(e($payment->notes)) !!}
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card rounded-0">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-file-earmark-check me-2"></i>
                        Payment Proof
                    </h3>
                </div>
                <div class="card-body">
                    @if ($payment->proof_file)
                        <a href="{{ route('admin.payments.proof.download', $payment) }}"
                            class="btn btn-outline-danger rounded-0 w-100">
                            <i class="bi bi-file-earmark-pdf me-1"></i>
                            Open Payment Proof
                        </a>
                    @else
                        <div class="text-muted text-center py-4">
                            No payment proof available.
                        </div>
                    @endif
                </div>
                <div class="card-body border-top">
                    @if ($payment->status === 'pending')
                        <form action="{{ route('admin.payments.verify', $payment) }}" method="POST"
                            class="verify-payment-form">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success rounded-0 w-100">
                                <i class="bi bi-check-circle me-1"></i>
                                Verify Payment
                            </button>
                        </form>
                        <form action="{{ route('admin.payments.reject', $payment) }}" method="POST"
                            class="reject-payment-form mt-2">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-danger rounded-0 w-100">
                                <i class="bi bi-x-circle me-1"></i>
                                Reject Payment
                            </button>
                        </form>
                    @elseif ($payment->status === 'verified')
                        <div class="alert alert-success rounded-0 mb-0">
                            <i class="bi bi-check-circle me-2"></i>
                            Payment has been verified.
                        </div>
                    @else
                        <div class="alert alert-danger rounded-0 mb-0">
                            <i class="bi bi-x-circle me-2"></i>
                            Payment has been rejected.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        document.querySelectorAll('.verify-payment-form').forEach(function(form) {
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Verify Payment?',
                    text: 'This will confirm the participant registration.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, verify',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#198754',
                    cancelButtonColor: '#6c757d'
                }).then(function(result) {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        document.querySelectorAll('.reject-payment-form').forEach(function(form) {
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Reject Payment?',
                    text: 'The payment will be marked as rejected.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, reject',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#dc3545',
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
