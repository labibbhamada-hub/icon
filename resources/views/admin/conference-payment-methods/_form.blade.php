<div class="row g-3">
    @php
        $selectedPaymentType = $paymentMethod?->type;
        if (old('type') !== null) {
            $selectedPaymentType = old('type');
        }
    @endphp
    <div class="col-md-6">
        <label class="form-label">
            Payment Type
            <span class="text-danger">*</span>
        </label>
        <select name="type" id="payment_type" class="form-select @error('type') is-invalid @enderror rounded-0"
            required>
            <option value="">
                Select Payment Type
            </option>
            <option value="bank_transfer" {{ $selectedPaymentType === 'bank_transfer' ? 'selected' : '' }}>
                Bank Transfer
            </option>
            <option value="qris" {{ $selectedPaymentType === 'qris' ? 'selected' : '' }}>
                QRIS
            </option>
            <option value="online_payment" {{ $selectedPaymentType === 'online_payment' ? 'selected' : '' }}>
                Online Payment
            </option>
            <option value="other" {{ $selectedPaymentType === 'other' ? 'selected' : '' }}>
                Other
            </option>
        </select>
        @error('type')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
        <div class="form-text">
            Select the payment method available for this conference.
        </div>
    </div>
    <div class="col-md-6">
        <label class="form-label">
            Display Name
            <span class="text-danger">*</span>
        </label>
        <input type="text" name="name" value="{{ old('name', $paymentMethod->name ?? '') }}"
            class="form-control @error('name') is-invalid @enderror rounded-0" placeholder="e.g. BRI Bhamada" required>
        @error('name')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
        <div class="form-text">
            This name will be shown to participants.
        </div>
    </div>
    <div class="col-md-6" id="provider-field">
        <label class="form-label">
            Provider / Bank
        </label>
        <input type="text" name="provider" value="{{ old('provider', $paymentMethod->provider ?? '') }}"
            class="form-control @error('provider') is-invalid @enderror rounded-0"
            placeholder="e.g. BRI, BCA, PayPal, QRIS">
        @error('provider')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="col-md-6" id="account-number-field">
        <label class="form-label">
            Account Number / Payment Identifier
        </label>
        <input type="text" name="account_number"
            value="{{ old('account_number', $paymentMethod->account_number ?? '') }}"
            class="form-control @error('account_number') is-invalid @enderror rounded-0" placeholder="e.g. 1234567890">
        @error('account_number')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="col-md-6" id="account-name-field">
        <label class="form-label">
            Account Name
        </label>
        <input type="text" name="account_name" value="{{ old('account_name', $paymentMethod->account_name ?? '') }}"
            class="form-control @error('account_name') is-invalid @enderror rounded-0"
            placeholder="e.g. Universitas Bhamada">
        @error('account_name')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">
            Currency
            <span class="text-danger">*</span>
        </label>
        <input type="text" name="currency" value="{{ old('currency', $paymentMethod->currency ?? 'IDR') }}"
            maxlength="3" class="form-control text-uppercase @error('currency') is-invalid @enderror rounded-0"
            placeholder="IDR" required>
        @error('currency')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="col-md-6" id="qr-code-field">
        <label class="form-label">
            QR Code
        </label>
        <input type="file" name="qr_code_file" accept=".jpg,.jpeg,.png,.webp"
            class="form-control @error('qr_code_file') is-invalid @enderror rounded-0">
        <div class="form-text">
            JPG, JPEG, PNG, or WebP. Maximum 2 MB.
        </div>
        @error('qr_code_file')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
        @if (!empty($paymentMethod?->qr_code_file))
            <div class="border rounded-0 p-3 mt-2">
                <small class="text-muted d-block mb-2">
                    Current QR Code
                </small>
                <img src="{{ asset('storage/' . $paymentMethod->qr_code_file) }}" alt="QR Code"
                    style="max-width: 220px; max-height: 220px; object-fit: contain;">
            </div>
        @endif
    </div>
    <div class="col-12">
        <label class="form-label">
            Instructions
        </label>
        <textarea name="instructions" rows="4" class="form-control @error('instructions') is-invalid @enderror rounded-0"
            placeholder="Instructions for participants...">{{ old('instructions', $paymentMethod->instructions ?? '') }}</textarea>
        @error('instructions')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
        <div class="form-text">
            Additional instructions shown to participants when choosing this method.
        </div>
    </div>
    <div class="col-md-6">
        <label class="form-label">
            Sort Order
            <span class="text-danger">*</span>
        </label>
        <input type="number" name="sort_order" min="0" max="255"
            value="{{ old('sort_order', $paymentMethod->sort_order ?? 0) }}"
            class="form-control @error('sort_order') is-invalid @enderror rounded-0" required>
        @error('sort_order')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label d-block">
            Status
        </label>
        <input type="hidden" name="is_active" value="0">
        <div class="form-check form-switch mt-2">
            <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active"
                @checked(old('is_active', $paymentMethod->is_active ?? true))>
            <label class="form-check-label" for="is_active">
                Active
            </label>
        </div>
        <div class="form-text">
            Only active payment methods are available to participants.
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const paymentType =
                document.getElementById('payment_type');
            const providerField =
                document.getElementById('provider-field');
            const accountNumberField =
                document.getElementById('account-number-field');
            const accountNameField =
                document.getElementById('account-name-field');
            const qrCodeField =
                document.getElementById('qr-code-field');
            function updateFields() {
                const type = paymentType.value;
                providerField.classList.remove('d-none');
                accountNumberField.classList.remove('d-none');
                accountNameField.classList.remove('d-none');
                qrCodeField.classList.remove('d-none');
                if (type === 'qris') {
                    accountNumberField.classList.add('d-none');
                    accountNameField.classList.add('d-none');
                }
                if (type === 'other') {
                    qrCodeField.classList.add('d-none');
                }
            }
            paymentType.addEventListener(
                'change',
                updateFields
            );
            updateFields();
        });
    </script>
@endpush
