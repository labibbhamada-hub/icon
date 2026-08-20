@extends('layouts.participant')

@section('title', 'Submit Payment')

@section('header')
    <div class="row align-items-center">
        <div class="col-sm-6">
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('participant.payments.index') }}" class="btn btn-secondary btn-sm rounded-0">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h1 class="mb-0 fs-3">
                    Submit Payment
                </h1>
            </div>
            <p class="text-muted mb-0">
                Upload your payment proof for registration verification.
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
            <i class="bi bi-info-circle me-2"></i>
            There are no pending registrations currently available
            for payment.
        </div>
        <a href="{{ route('participant.registration.index') }}" class="btn btn-secondary rounded-0">
            <i class="bi bi-arrow-left me-1"></i>
            Back to Registration
        </a>
    @else
        <form action="{{ route('participant.payments.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card rounded-0">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-calendar-check me-2"></i>
                        Registration
                    </h3>
                </div>
                <div class="card-body">
                    <label class="form-label">
                        Select Registration
                        <span class="text-danger">*</span>
                    </label>
                    <select name="participant_id" id="participant_id"
                        class="form-select @error('participant_id') is-invalid @enderror">
                        <option value="">
                            Select Registration
                        </option>
                        @foreach ($participants as $participant)
                            <option value="{{ $participant->id }}" data-type="{{ $participant->participant_type }}"
                                data-bank="{{ $participant->conference?->configuration?->bank_name ?? '' }}"
                                data-account-number="{{ $participant->conference?->configuration?->account_number ?? '' }}"
                                data-account-name="{{ $participant->conference?->configuration?->account_name ?? '' }}"
                                data-regular-fee="{{ $participant->conference?->configuration?->regular_fee ?? 0 }}"
                                data-student-fee="{{ $participant->conference?->configuration?->student_fee ?? 0 }}"
                                @selected(old('participant_id') == $participant->id)>
                                {{ $participant->conference?->name ?? 'Conference' }}
                                —
                                {{ $participant->registration_number }}
                            </option>
                        @endforeach
                    </select>
                    <div class="form-text">
                        Select the registration you want to pay.
                    </div>
                    @error('participant_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            <div class="card rounded-0 mt-3" id="payment-information-card" style="display: none;">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-bank me-2"></i>
                        Payment Information
                    </h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-info rounded-0">
                        <div class="fw-semibold mb-3">
                            Please transfer the exact amount to
                            the following account:
                        </div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <small class="text-muted d-block">
                                    Bank
                                </small>
                                <strong id="bank-name">
                                    —
                                </strong>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted d-block">
                                    Account Number
                                </small>
                                <strong id="account-number">
                                    —
                                </strong>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted d-block">
                                    Account Name
                                </small>
                                <strong id="account-name">
                                    —
                                </strong>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3 mt-1">
                        <div class="col-md-6">
                            <div class="border rounded-0 p-3">
                                <small class="text-muted d-block">
                                    Participant Type
                                </small>
                                <strong id="participant-type">
                                    —
                                </strong>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded-0 p-3">
                                <small class="text-muted d-block">
                                    Registration Fee
                                </small>
                                <div id="registration-fee" class="fs-4 fw-bold text-success">
                                    Rp 0
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card rounded-0 mt-3" id="payment-form-card" style="display: none;">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-upload me-2"></i>
                        Payment Proof
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label">
                                Payment Method
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" value="Bank Transfer" readonly>
                            <input type="hidden" name="payment_method" value="bank_transfer">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                Payment Date
                                <span class="text-danger">*</span>
                            </label>
                            <input type="datetime-local" name="paid_at"
                                value="{{ old('paid_at', now()->format('Y-m-d\TH:i')) }}"
                                class="form-control @error('paid_at') is-invalid @enderror">
                            @error('paid_at')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">
                                Transfer Proof
                                <span class="text-danger">*</span>
                            </label>
                            <input type="file" name="proof_file" accept=".jpg,.jpeg,.png,.webp,.pdf"
                                class="form-control @error('proof_file') is-invalid @enderror">
                            <div class="form-text">
                                JPG, JPEG, PNG, WebP, or PDF.
                                Maximum 5 MB.
                            </div>
                            @error('proof_file')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">
                                Notes
                            </label>
                            <textarea name="notes" rows="4" class="form-control @error('notes') is-invalid @enderror"
                                placeholder="Optional payment information...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <a href="{{ route('participant.payments.index') }}" class="btn btn-secondary">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle me-1"></i>
                        Submit Payment Proof
                    </button>
                </div>
            </div>
        </form>
    @endif
@endsection


@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const participantSelect =
                document.getElementById('participant_id');
            const paymentInfoCard =
                document.getElementById(
                    'payment-information-card'
                );
            const paymentFormCard =
                document.getElementById(
                    'payment-form-card'
                );
            const bankName =
                document.getElementById('bank-name');
            const accountNumber =
                document.getElementById('account-number');
            const accountName =
                document.getElementById('account-name');
            const participantType =
                document.getElementById('participant-type');
            const registrationFee =
                document.getElementById('registration-fee');

            function formatRupiah(value) {
                const amount =
                    Number(value || 0);
                return 'Rp ' +
                    new Intl.NumberFormat(
                        'id-ID'
                    ).format(amount);
            }

            function refreshPaymentInformation() {
                const selected =
                    participantSelect.options[
                        participantSelect.selectedIndex
                    ];
                if (!selected || !selected.value) {
                    paymentInfoCard.style.display =
                        'none';
                    paymentFormCard.style.display =
                        'none';
                    return;
                }
                const type =
                    selected.dataset.type || 'regular';
                const bank =
                    selected.dataset.bank || '';
                const account =
                    selected.dataset.accountNumber || '';
                const accountHolder =
                    selected.dataset.accountName || '';
                const regularFee =
                    selected.dataset.regularFee || 0;
                const studentFee =
                    selected.dataset.studentFee || 0;
                const fee =
                    type === 'student' ?
                    studentFee :
                    regularFee;
                bankName.textContent =
                    bank || 'Not configured';
                accountNumber.textContent =
                    account || 'Not configured';
                accountName.textContent =
                    accountHolder || 'Not configured';
                participantType.textContent =
                    type === 'student' ?
                    'Student' :
                    'Regular';
                registrationFee.textContent =
                    formatRupiah(fee);
                paymentInfoCard.style.display =
                    '';
                paymentFormCard.style.display =
                    '';
            }
            participantSelect.addEventListener(
                'change',
                refreshPaymentInformation
            );
            refreshPaymentInformation();
        });
    </script>
@endpush
