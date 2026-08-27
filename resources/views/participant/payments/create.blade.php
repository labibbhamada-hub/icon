@extends('layouts.participant')

@section('title', 'Complete Payment')

@section('header')
    <div class="row align-items-center">

        <div class="col-sm-6">

            <div class="d-flex align-items-center gap-2">

                <a href="{{ route('participant.payments.index') }}" class="btn btn-secondary btn-sm rounded-0">

                    <i class="bi bi-arrow-left"></i>

                </a>

                <h1 class="mb-0 fs-3">
                    Complete Payment
                </h1>

            </div>

            <p class="text-muted mb-0 mt-1">
                Complete your payment and upload the payment proof.
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
                    <a href="{{ route('participant.payments.index') }}">
                        Payments
                    </a>
                </li>

                <li class="breadcrumb-item active">
                    Submit
                </li>

            </ol>

        </div>

    </div>
@endsection

@section('content')

    @if ($participants->isEmpty())

        <div class="alert alert-info rounded-0">

            <div class="d-flex align-items-start gap-2">

                <i class="bi bi-info-circle fs-5"></i>

                <div>

                    <strong>
                        No Payment Available
                    </strong>

                    <div class="small mt-1">
                        There is currently no registration that requires
                        payment or is eligible for payment.
                    </div>

                </div>

            </div>

        </div>

        <a href="{{ route('participant.registration.index') }}" class="btn btn-secondary rounded-0">

            <i class="bi bi-arrow-left me-1"></i>
            Back to Registration

        </a>
    @else
        @php
            $defaultParticipant = $participants->first();

            $defaultCalculation = app(\App\Services\PaymentCalculationService::class)->calculate($defaultParticipant);

            $oldParticipantId = old('participant_id', $defaultParticipant->id);

            $selectedParticipant = $participants->firstWhere('id', $oldParticipantId) ?? $defaultParticipant;
        @endphp

        <form action="{{ route('participant.payments.store') }}" method="POST" enctype="multipart/form-data">

            @csrf

            {{-- =========================================================
                 REGISTRATION
            ========================================================== --}}
            <div class="card rounded-0 mb-3">

                <div class="card-header">

                    <h3 class="card-title">
                        <i class="bi bi-person-check me-2"></i>
                        Your Registration
                    </h3>

                </div>

                <div class="card-body">

                    @if ($participants->count() > 1)

                        <div class="mb-3">

                            <label for="participant_id" class="form-label">

                                Select Registration
                                <span class="text-danger">*</span>

                            </label>

                            <select name="participant_id" id="participant_id"
                                class="form-select @error('participant_id') is-invalid @enderror rounded-0">

                                @foreach ($participants as $participant)
                                    <option value="{{ $participant->id }}" @selected($oldParticipantId == $participant->id)>

                                        {{ $participant->conference?->name ?? 'Conference' }}
                                        —
                                        {{ $participant->registration_number }}

                                    </option>
                                @endforeach

                            </select>

                            @error('participant_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                            <div class="form-text">
                                Select the registration you want to pay.
                            </div>

                        </div>
                    @else
                        <input type="hidden" name="participant_id" value="{{ $selectedParticipant->id }}">

                    @endif

                    <div class="row g-3">

                        <div class="col-md-6">

                            <div class="border rounded-0 p-3 h-100">

                                <small class="text-muted d-block">
                                    Conference
                                </small>

                                <strong class="d-block mt-1">
                                    {{ $selectedParticipant->conference?->name ?? '—' }}
                                </strong>

                                <small class="text-muted">
                                    {{ $selectedParticipant->conference?->short_name }}
                                    @if ($selectedParticipant->conference?->year)
                                        ({{ $selectedParticipant->conference->year }})
                                    @endif
                                </small>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="border rounded-0 p-3 h-100">

                                <small class="text-muted d-block">
                                    Registration Number
                                </small>

                                <strong class="d-block mt-1">
                                    {{ $selectedParticipant->registration_number }}
                                </strong>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="border rounded-0 p-3 h-100">

                                <small class="text-muted d-block">
                                    Registration Type
                                </small>

                                <strong class="d-block mt-1">
                                    {{ $selectedParticipant->registrationType?->name ?? '—' }}
                                </strong>

                                @if ($selectedParticipant->registrationType?->payment_timing === 'after_acceptance')
                                    <small class="text-muted d-block mt-1">
                                        Payment after paper acceptance
                                    </small>
                                @else
                                    <small class="text-muted d-block mt-1">
                                        Payment during registration
                                    </small>
                                @endif

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="border rounded-0 p-3 h-100">

                                <small class="text-muted d-block">
                                    Attendance
                                </small>

                                <strong class="d-block mt-1">

                                    @if ($selectedParticipant->attendance_type === 'online')
                                        <i class="bi bi-camera-video me-1"></i>
                                    @elseif ($selectedParticipant->attendance_type === 'offline')
                                        <i class="bi bi-building me-1"></i>
                                    @else
                                        <i class="bi bi-diagram-3 me-1"></i>
                                    @endif

                                    {{ ucfirst($selectedParticipant->attendance_type) }}

                                </strong>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            {{-- =========================================================
                 PAYMENT SUMMARY
            ========================================================== --}}
            <div class="card rounded-0 mb-3">

                <div class="card-header">

                    <h3 class="card-title">
                        <i class="bi bi-receipt me-2"></i>
                        Payment Summary
                    </h3>

                </div>

                <div class="card-body">

                    <div class="row g-3">

                        <div class="col-md-6">

                            <div class="border rounded-0 p-3 h-100">

                                <small class="text-muted d-block">
                                    Base Registration Fee
                                </small>

                                <strong id="base-fee" class="fs-5 d-block mt-1">

                                    {{ $defaultCalculation['currency'] }}
                                    {{ number_format($defaultCalculation['base_fee'], 0, ',', '.') }}

                                </strong>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="border rounded-0 p-3 h-100">

                                <small class="text-muted d-block">
                                    Accepted Papers
                                </small>

                                <strong id="accepted-papers" class="fs-5 d-block mt-1">

                                    {{ $defaultCalculation['accepted_papers'] }}

                                </strong>

                            </div>

                        </div>

                        <div class="col-md-4">

                            <div class="border rounded-0 p-3 h-100">

                                <small class="text-muted d-block">
                                    Included Papers
                                </small>

                                <strong id="included-papers" class="fs-5 d-block mt-1">

                                    {{ $defaultCalculation['included_papers'] }}

                                </strong>

                            </div>

                        </div>

                        <div class="col-md-4">

                            <div class="border rounded-0 p-3 h-100">

                                <small class="text-muted d-block">
                                    Additional Papers
                                </small>

                                <strong id="additional-papers" class="fs-5 d-block mt-1">

                                    {{ $defaultCalculation['additional_papers'] }}

                                </strong>

                            </div>

                        </div>

                        <div class="col-md-4">

                            <div class="border rounded-0 p-3 h-100">

                                <small class="text-muted d-block">
                                    Additional Fee
                                </small>

                                <strong id="additional-amount" class="fs-5 d-block mt-1">

                                    {{ $defaultCalculation['currency'] }}
                                    {{ number_format($defaultCalculation['additional_amount'], 0, ',', '.') }}

                                </strong>

                            </div>

                        </div>

                    </div>
                    <div class="border-top mt-4 pt-3">

                        <div class="row g-3">

                            <div class="col-md-6">

                                <div class="border rounded-0 p-3 h-100">

                                    <small class="text-muted d-block">
                                        Total Obligation
                                    </small>

                                    <strong id="total-obligation" class="fs-5 d-block mt-1">

                                        {{ $defaultCalculation['currency'] }}
                                        {{ number_format($defaultCalculation['total_amount'], 0, ',', '.') }}

                                    </strong>

                                    <small class="text-muted d-block mt-1">
                                        Total amount required for this registration.
                                    </small>

                                </div>

                            </div>

                            <div class="col-md-6">

                                <div class="border rounded-0 p-3 h-100">

                                    <small class="text-muted d-block">
                                        Already Paid
                                    </small>

                                    <strong id="verified-payment-amount" class="fs-5 d-block mt-1">

                                        {{ $defaultCalculation['currency'] }}
                                        {{ number_format($defaultCalculation['verified_payment_amount'], 0, ',', '.') }}

                                    </strong>

                                    <small class="text-muted d-block mt-1">
                                        Verified payments for this registration.
                                    </small>

                                </div>

                            </div>

                        </div>

                        <div class="border rounded-0 p-3 mt-3">

                            <div class="d-flex justify-content-between align-items-center">

                                <div>

                                    <small class="text-muted d-block">
                                        Amount Due Now
                                    </small>

                                    <strong>
                                        This is the amount you need to pay now.
                                    </strong>

                                </div>

                                <div class="text-end">

                                    <div id="outstanding-amount" class="fs-3 fw-bold text-success">

                                        {{ $defaultCalculation['currency'] }}
                                        {{ number_format($defaultCalculation['outstanding_amount'], 0, ',', '.') }}

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>
                    @if ($defaultParticipant->registrationType?->payment_timing === 'after_acceptance')
                        <div class="alert alert-info rounded-0 mt-3 mb-0">
                            <i class="bi bi-info-circle me-2"></i>
                            Your payment is calculated from your accepted papers.
                            Additional accepted papers may increase your total obligation.
                            Any previous verified payment is deducted from the amount due now.
                        </div>
                    @endif
                </div>
            </div>
            {{-- =========================================================
                 PAYMENT METHOD
            ========================================================== --}}
            <div class="card rounded-0 mb-3">

                <div class="card-header">

                    <h3 class="card-title">
                        <i class="bi bi-credit-card me-2"></i>
                        Payment Method
                    </h3>

                </div>

                <div class="card-body">

                    @php
                        $paymentMethods = $selectedParticipant->conference?->paymentMethods
                            ?->where('is_active', true)
                            ->sortBy('sort_order')
                            ->values();
                    @endphp

                    @if ($paymentMethods && $paymentMethods->isNotEmpty())

                        @error('payment_method_id')
                            <div class="alert alert-danger rounded-0 mb-3">

                                <div class="d-flex align-items-start gap-2">

                                    <i class="bi bi-exclamation-circle fs-5"></i>

                                    <div>

                                        <strong>
                                            Payment Method Required
                                        </strong>

                                        <div class="small mt-1">
                                            {{ $message }}
                                        </div>

                                    </div>

                                </div>

                            </div>
                        @enderror

                        <div class="row g-3">

                            @foreach ($paymentMethods as $paymentMethod)
                                <div class="col-md-6">

                                    <label class="d-block h-100">

                                        <input type="radio" name="payment_method_id" value="{{ $paymentMethod->id }}"
                                            class="btn-check payment-method-option" @checked(old('payment_method_id', $paymentMethods->first()->id) == $paymentMethod->id)>

                                        <div class="border rounded-0 p-3 h-100 payment-method-card">

                                            <div class="d-flex justify-content-between align-items-start gap-3">

                                                <div>

                                                    <h5 class="fw-bold mb-1">
                                                        {{ $paymentMethod->name }}
                                                    </h5>

                                                    <small class="text-muted d-block">
                                                        {{ ucwords(str_replace('_', ' ', $paymentMethod->type)) }}
                                                    </small>

                                                </div>

                                                <i
                                                    class="bi bi-check-circle-fill text-success d-none payment-method-check"></i>

                                            </div>

                                            @if ($paymentMethod->provider)
                                                <div class="small mt-3">

                                                    <span class="text-muted">
                                                        Provider:
                                                    </span>

                                                    <strong>
                                                        {{ $paymentMethod->provider }}
                                                    </strong>

                                                </div>
                                            @endif

                                            @if ($paymentMethod->account_number)
                                                <div class="small mt-1">

                                                    <span class="text-muted">
                                                        Account / Identifier:
                                                    </span>

                                                    <strong>
                                                        {{ $paymentMethod->account_number }}
                                                    </strong>

                                                </div>
                                            @endif

                                            @if ($paymentMethod->account_name)
                                                <div class="small mt-1">

                                                    <span class="text-muted">
                                                        Account Name:
                                                    </span>

                                                    <strong>
                                                        {{ $paymentMethod->account_name }}
                                                    </strong>

                                                </div>
                                            @endif

                                            @if ($paymentMethod->qr_code_file)
                                                <div class="mt-3">

                                                    <small class="text-muted d-block mb-2">
                                                        QR Code
                                                    </small>

                                                    <img src="{{ asset('storage/' . $paymentMethod->qr_code_file) }}"
                                                        alt="{{ $paymentMethod->name }}" class="img-fluid border"
                                                        style="max-width: 220px;">

                                                </div>
                                            @endif

                                            @if ($paymentMethod->instructions)
                                                <div class="small text-muted mt-3">

                                                    <strong class="text-body">
                                                        Instructions
                                                    </strong>

                                                    <div class="mt-1">
                                                        {!! nl2br(e($paymentMethod->instructions)) !!}
                                                    </div>

                                                </div>
                                            @endif

                                        </div>

                                    </label>

                                </div>
                            @endforeach

                        </div>
                    @else
                        <div class="alert alert-warning rounded-0 mb-0">

                            <div class="d-flex align-items-start gap-2">

                                <i class="bi bi-exclamation-triangle fs-5"></i>

                                <div>

                                    <strong>
                                        Payment Method Unavailable
                                    </strong>

                                    <div class="small mt-1">
                                        No active payment method has been configured
                                        for this conference.
                                    </div>

                                </div>

                            </div>

                        </div>

                    @endif

                </div>

            </div>

            {{-- =========================================================
                 PAYMENT PROOF
            ========================================================== --}}
            <div class="card rounded-0 mb-3">

                <div class="card-header">

                    <h3 class="card-title">
                        <i class="bi bi-file-earmark-arrow-up me-2"></i>
                        Payment Proof
                    </h3>

                </div>

                <div class="card-body">

                    <div class="row g-3">

                        <div class="col-md-6">

                            <label for="paid_at" class="form-label">

                                Payment Date
                                <span class="text-danger">*</span>

                            </label>

                            <input type="datetime-local" name="paid_at" id="paid_at"
                                value="{{ old('paid_at', now()->format('Y-m-d\TH:i')) }}"
                                class="form-control @error('paid_at') is-invalid @enderror rounded-0">

                            @error('paid_at')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                            <div class="form-text">
                                Enter the date and time when you made the payment.
                            </div>

                        </div>

                        <div class="col-md-6">

                            <label for="proof_file" class="form-label">

                                Payment Proof
                                <span class="text-danger">*</span>

                            </label>

                            <input type="file" name="proof_file" id="proof_file" accept=".jpg,.jpeg,.png,.webp,.pdf"
                                class="form-control @error('proof_file') is-invalid @enderror rounded-0">

                            @error('proof_file')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                            <div class="form-text">
                                JPG, JPEG, PNG, WebP, or PDF. Maximum 5 MB.
                            </div>

                        </div>

                        <div class="col-12">

                            <label for="notes" class="form-label">

                                Notes
                            </label>

                            <textarea name="notes" id="notes" rows="4"
                                class="form-control @error('notes') is-invalid @enderror rounded-0" placeholder="Optional payment information...">{{ old('notes') }}</textarea>

                            @error('notes')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>

                </div>

                <div class="card-footer">

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                        <div class="text-muted small">

                            <i class="bi bi-shield-check me-1"></i>

                            Your payment will be reviewed by the conference administrator.

                        </div>

                        <button type="submit" class="btn btn-success rounded-0" @disabled(!$paymentMethods || $paymentMethods->isEmpty())>

                            <i class="bi bi-check-circle me-1"></i>
                            Submit Payment Proof

                        </button>

                    </div>

                </div>

            </div>

        </form>

    @endif

@endsection

@push('styles')
    <style>
        .payment-method-card {
            cursor: pointer;
            transition:
                border-color .15s ease,
                box-shadow .15s ease;
        }

        .payment-method-card:hover {
            border-color:
                var(--bs-primary) !important;
        }

        .btn-check:checked+.payment-method-card {
            border-color:
                var(--bs-primary) !important;

            box-shadow:
                0 0 0 .15rem rgba(var(--bs-primary-rgb), .15);
        }

        .btn-check:checked+.payment-method-card .payment-method-check {
            display: block !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const participantSelect =
                document.getElementById('participant_id');

            if (participantSelect) {

                participantSelect.addEventListener(
                    'change',
                    function() {

                        /*
                         * Multiple pending registrations are supported.
                         *
                         * To keep the form simple, the page will reload
                         * with the selected registration when changed.
                         */

                        const url =
                            new URL(window.location.href);

                        url.searchParams.set(
                            'participant_id',
                            this.value
                        );

                        window.location.href =
                            url.toString();

                    }
                );

            }

        });
    </script>
@endpush
