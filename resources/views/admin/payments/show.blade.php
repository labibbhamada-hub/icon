@extends('layouts.admin')

@section('title', 'Payment Detail')

@section('header')
    <div class="row align-items-top">

        <div class="col-sm-6">
            <div class="d-flex gap-2">
                <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary rounded-0">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h1 class="mb-0 fs-3">
                    Payment Detail
                </h1>
            </div>
            <p class="text-muted mb-0">
                Review and verify participant payment.
            </p>
        </div>

    </div>
@endsection

@section('content')

    <div class="row">

        {{-- ============================================================
            MAIN PAYMENT INFORMATION
        ============================================================= --}}
        <div class="col-lg-8">

            <div class="card rounded-0 overflow-hidden">

                <div class="card-header rounded-0">

                    <h3 class="card-title">
                        <i class="bi bi-credit-card me-2"></i>
                        {{ $payment->payment_code }}
                    </h3>

                </div>

                {{-- Summary --}}
                <div class="card-body">

                    <div class="row g-3">

                        {{-- Amount --}}
                        <div class="col-md-6">

                            <div class="border rounded-0 p-3 h-100">
                                <small class="text-muted d-block">
                                    Payment Amount
                                </small>
                                <div class="fs-5 fw-bold mt-2">
                                    {{ $payment->participant?->registrationType?->currency ?? 'IDR' }}
                                    {{ number_format($payment->amount, 0, ',', '.') }}
                                </div>
                                <div class="mt-2">

                                    <small class="text-muted d-block mb-1">
                                        Payment Status
                                    </small>

                                    @if ($payment->status === 'verified')
                                        <span class="badge text-bg-success rounded-0">
                                            <i class="bi bi-check-circle me-1"></i>
                                            Verified
                                        </span>
                                    @elseif ($payment->status === 'rejected')
                                        <span class="badge text-bg-danger rounded-0">
                                            <i class="bi bi-x-circle me-1"></i>
                                            Rejected
                                        </span>
                                    @else
                                        <span class="badge text-bg-warning rounded-0">
                                            <i class="bi bi-clock me-1"></i>
                                            Pending
                                        </span>
                                    @endif
                                </div>
                            </div>

                        </div>

                        {{-- Payment Method --}}
                        <div class="col-md-6">

                            <div class="border rounded-0 p-3 h-100">
                                <small class="text-muted d-block">
                                    Payment Method
                                </small>
                                <div class="fw-semibold mt-3">
                                    {{ $payment->paymentMethod?->name ?? '-' }}
                                    @if ($payment->paymentMethod?->type)
                                        <small class="text-muted d-block mt-1">
                                            {{ ucwords(str_replace('_', ' ', $payment->paymentMethod->type)) }}
                                        </small>
                                    @endif
                                </div>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <div class="card rounded-0 overflow-hidden mt-3">

                <div class="card-header rounded-0">
                    <h3 class="card-title">
                        <i class="bi bi-credit-card me-2"></i>
                        Payment Information
                    </h3>
                </div>

                {{-- Payment Information --}}
                <div class="card-body">

                    <div class="row mb-2">

                        <div class="col-md-4 text-muted">
                            Payment Code
                        </div>

                        <div class="col-md-8 fw-semibold">
                            {{ $payment->payment_code }}
                        </div>

                    </div>

                    <div class="row mb-2">

                        <div class="col-md-4 text-muted">
                            Participant
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
                                -
                            @endif

                        </div>

                    </div>

                    <div class="row mb-2">

                        <div class="col-md-4 text-muted">
                            Conference
                        </div>

                        <div class="col-md-8">

                            @if ($payment->participant?->conference)
                                <strong>
                                    {{ $payment->participant->conference->name }}
                                </strong>

                                <small class="text-muted d-block">
                                    {{ $payment->participant->conference->short_name }}
                                    ({{ $payment->participant->conference->year }})
                                </small>
                            @else
                                -
                            @endif

                        </div>

                    </div>

                    <div class="row mb-2">

                        <div class="col-md-4 text-muted">
                            Registration Type
                        </div>

                        <div class="col-md-8">

                            @if ($payment->participant?->registrationType)
                                <strong>
                                    {{ $payment->participant->registrationType->name }}
                                </strong>

                                <small class="text-muted d-block">
                                    {{ ucfirst($payment->participant->registrationType->category) }}
                                </small>
                            @else
                                -
                            @endif

                        </div>

                    </div>

                    <div class="row mb-2">

                        <div class="col-md-4 text-muted">
                            Paid At
                        </div>

                        <div class="col-md-8">
                            {{ $payment->paid_at?->format('d F Y H:i') ?? '-' }}
                        </div>

                    </div>

                    <div class="row mb-2">

                        <div class="col-md-4 text-muted">
                            Verified At
                        </div>

                        <div class="col-md-8">
                            {{ $payment->verified_at?->format('d F Y H:i') ?? '-' }}
                        </div>

                    </div>

                    <div class="row mb-2">

                        <div class="col-md-4 text-muted">
                            Verified By
                        </div>

                        <div class="col-md-8">
                            {{ $payment->verifier?->name ?? '-' }}
                        </div>

                    </div>

                </div>

            </div>


            {{-- Participant --}}
            @if ($payment->participant)
                <div class="card rounded-0 overflow-hidden mt-3">

                    <div class="card-header rounded-0">
                        <h3 class="card-title">
                            <i class="bi bi-credit-card me-2"></i>
                            Participant Information
                        </h3>
                    </div>

                    <div class="card-body">

                        <div class="row mb-2">

                            <div class="col-md-4 text-muted">
                                Full Name
                            </div>

                            <div class="col-md-8">
                                {{ $payment->participant->full_name }}
                            </div>

                        </div>

                        <div class="row mb-2">

                            <div class="col-md-4 text-muted">
                                Email
                            </div>

                            <div class="col-md-8">
                                {{ $payment->participant->email }}
                            </div>

                        </div>

                        <div class="row mb-2">

                            <div class="col-md-4 text-muted">
                                Phone
                            </div>

                            <div class="col-md-8">
                                {{ $payment->participant->phone ?: '-' }}
                            </div>

                        </div>

                        <div class="row mb-2">

                            <div class="col-md-4 text-muted">
                                Institution
                            </div>

                            <div class="col-md-8">
                                {{ $payment->participant->institution ?: '-' }}
                            </div>

                        </div>

                        <div class="row mb-2">

                            <div class="col-md-4 text-muted">
                                Registration Status
                            </div>

                            <div class="col-md-8">

                                @if ($payment->participant->registration_status === 'confirmed')
                                    <span class="badge text-bg-success rounded-0">
                                        Confirmed
                                    </span>
                                @elseif ($payment->participant->registration_status === 'cancelled')
                                    <span class="badge text-bg-danger rounded-0">
                                        Cancelled
                                    </span>
                                @else
                                    <span class="badge text-bg-warning rounded-0">
                                        Pending
                                    </span>
                                @endif

                            </div>

                        </div>

                    </div>

                </div>
            @endif

            {{-- Notes --}}
            @if ($payment->notes)
                <div class="card rounded-0 overflow-hidden mt-3">
                    <div class="card-header rounded-0">
                        <h3 class="card-title">
                            <i class="bi bi-chat-left-text me-2"></i>
                            Participant Payment Notes
                        </h3>
                    </div>
                    <div class="card-body">
                        {!! nl2br(e($payment->notes)) !!}
                    </div>
                </div>
            @endif

        </div>


        {{-- ============================================================
            PAYMENT PROOF + ACTION
        ============================================================= --}}
        <div class="col-lg-4">

            {{-- Payment Proof --}}
            <div class="card rounded-0 overflow-hidden mb-3">

                <div class="card-header rounded-0">
                    <h3 class="card-title">
                        <i class="bi bi-file-earmark-check me-2"></i>
                        Payment Proof
                    </h3>
                </div>

                <div class="card-body">
                    @if ($payment->proof_file)
                        <div class="text-center mb-3">
                            <i class="bi bi-file-earmark-pdf display-4 text-danger"></i>
                        </div>
                        <a href="{{ route('admin.payments.proof.download', $payment) }}"
                            class="btn btn-outline-danger rounded-0 w-100">
                            <i class="bi bi-download me-1"></i>
                            Download Payment Proof
                        </a>
                    @else
                        <div class="text-muted text-center py-4">
                            <i class="bi bi-file-earmark-x display-5 d-block mb-2"></i>
                            No payment proof available.
                        </div>
                    @endif
                </div>

            </div>

            {{-- Verification --}}
            <div class="card rounded-0 overflow-hidden">

                <div class="card-header rounded-0">
                    <h3 class="card-title">
                        <i class="bi bi-shield-check me-2"></i>
                        Verification
                    </h3>
                </div>

                <div class="card-body">
                    @if ($payment->status === 'pending')
                        <div class="alert alert-warning rounded-0">
                            <i class="bi bi-exclamation-circle me-2"></i>
                            This payment is waiting for verification.
                        </div>
                        <form action="{{ route('admin.payments.verify', $payment) }}" method="POST"
                            class="verify-payment-form" id="form-verify">
                            @csrf
                            @method('PATCH')
                            <button type="button" class="btn btn-success rounded-0 w-100" id="btn-verify"
                                onclick="form_verify()">
                                <span id="btn-verify-text">
                                    <i class="bi bi-check-circle me-1"></i>
                                    Verify Payment
                                </span>
                                <span id="btn-verify-load" class="d-none">
                                    <span class="spinner-border spinner-border-sm me-1" role="status"
                                        aria-hidden="true"></span>
                                    Memproses...
                                </span>
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
                            Payment has been verified successfully.
                        </div>
                    @elseif ($payment->status === 'rejected')
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
        document
            .querySelectorAll('.verify-payment-form')
            .forEach(function(form) {

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
                        cancelButtonColor: '#6c757d',
                    }).then(function(result) {

                        if (result.isConfirmed) {
                            form.submit();
                        }

                    });

                });

            });


        document
            .querySelectorAll('.reject-payment-form')
            .forEach(function(form) {

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
                        cancelButtonColor: '#6c757d',
                    }).then(function(result) {

                        if (result.isConfirmed) {
                            form.submit();
                        }

                    });

                });

            });
    </script>
    <script>
        function form_verify() {
            const btnVerify = document.getElementById('btn-verify');
            const btnVerifyText = document.getElementById('btn-verify-text');
            const btnVerifyLoad = document.getElementById('btn-verify-load');
            const formVerify = document.getElementById('form-verify');

            btnVerify.disabled = true;

            btnVerifyText.classList.add('d-none');
            btnVerifyLoad.classList.remove('d-none');

            formVerify.submit();
        }
    </script>
@endpush
