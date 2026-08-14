@extends('layouts.participant')

@section('title', 'Submit Payment')

@section('content')

    <div class="app-content-header">

        <div class="container-fluid">

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

                    <p class="text-muted mb-0 mt-1">
                        Upload your payment proof for verification.
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
                            Create
                        </li>

                    </ol>

                </div>

            </div>

        </div>

    </div>


    <div class="app-content">

        <div class="container-fluid">

            @if ($participants->isEmpty())

                <div class="alert alert-info">

                    <i class="bi bi-info-circle me-2"></i>

                    There are no pending registrations available for payment.

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

                                <i class="bi bi-credit-card me-2"></i>

                                Payment Information

                            </h3>

                        </div>


                        <div class="card-body">

                            <div class="row g-4">

                                {{-- Participant / Conference --}}
                                <div class="col-md-12">

                                    <label class="form-label">

                                        Registration

                                        <span class="text-danger">*</span>

                                    </label>

                                    <select name="participant_id"
                                        class="form-select @error('participant_id') is-invalid @enderror">

                                        <option value="">
                                            Select Registration
                                        </option>

                                        @foreach ($participants as $participant)
                                            <option value="{{ $participant->id }}" @selected(old('participant_id') == $participant->id)>

                                                {{ $participant->conference->name }}

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

                                </div>


                                {{-- Amount --}}
                                <div class="col-md-6">

                                    <label class="form-label">

                                        Amount

                                        <span class="text-danger">*</span>

                                    </label>

                                    <div class="input-group">

                                        <span class="input-group-text">
                                            Rp
                                        </span>

                                        <input type="number" name="amount" min="0" step="0.01"
                                            value="{{ old('amount') }}"
                                            class="form-control @error('amount') is-invalid @enderror" placeholder="500000">

                                    </div>

                                    @error('amount')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>


                                {{-- Payment Method --}}
                                <div class="col-md-6">

                                    <label class="form-label">

                                        Payment Method

                                        <span class="text-danger">*</span>

                                    </label>

                                    <select name="payment_method"
                                        class="form-select @error('payment_method') is-invalid @enderror">

                                        <option value="bank_transfer" @selected(old('payment_method', 'bank_transfer') === 'bank_transfer')>

                                            Bank Transfer

                                        </option>

                                        <option value="cash" @selected(old('payment_method') === 'cash')>

                                            Cash

                                        </option>

                                        <option value="other" @selected(old('payment_method') === 'other')>

                                            Other

                                        </option>

                                    </select>

                                    @error('payment_method')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>


                                {{-- Paid At --}}
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


                                {{-- Proof --}}
                                <div class="col-md-6">

                                    <label class="form-label">

                                        Payment Proof

                                        <span class="text-danger">*</span>

                                    </label>

                                    <input type="file" name="proof_file" accept=".jpg,.jpeg,.png,.webp,.pdf"
                                        class="form-control @error('proof_file') is-invalid @enderror">

                                    <div class="form-text">
                                        JPG, PNG, WebP, or PDF. Maximum 5 MB.
                                    </div>

                                    @error('proof_file')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>


                                {{-- Notes --}}
                                <div class="col-12">

                                    <label class="form-label">
                                        Notes
                                    </label>

                                    <textarea name="notes" rows="4" class="form-control @error('notes') is-invalid @enderror"
                                        placeholder="Additional payment information...">{{ old('notes') }}</textarea>

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

                                <i class="bi bi-upload me-1"></i>

                                Submit Payment

                            </button>

                        </div>

                    </div>

                </form>

            @endif

        </div>

    </div>

@endsection
