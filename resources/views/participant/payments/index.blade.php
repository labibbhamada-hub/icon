@extends('layouts.participant')

@section('title', 'Payments')

@section('header')
    <div class="row align-items-top">
        <div class="col-sm-6">
            <h3 class="mb-0">
                My Payments
            </h3>
            <p class="text-muted mb-0 mt-1">
                View and manage your conference payments.
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
                    Payments
                </li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="card rounded-0">
        <div class="card-header">
            <h3 class="card-title">
                <i class="bi bi-credit-card me-2"></i>
                Payment History
            </h3>
            <div class="float-end">
                <a href="{{ route('participant.payments.create') }}" class="btn btn-success btn-sm rounded-0">
                    <i class="bi bi-upload me-1"></i>
                    Submit Payment
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th width="40">
                                No
                            </th>
                            <th>
                                Payment
                            </th>
                            <th>
                                Conference
                            </th>
                            <th>
                                Amount
                            </th>
                            <th>
                                Method
                            </th>
                            <th>
                                Status
                            </th>
                            <th>
                                Paid At
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($payments as $payment)
                            <tr>
                                <td>
                                    {{ $loop->iteration }}
                                </td>
                                <td>
                                    <strong>
                                        {{ $payment->payment_code }}
                                    </strong>
                                    <small class="text-muted d-block">
                                        {{ $payment->created_at->format('d M Y') }}
                                    </small>
                                </td>
                                <td>
                                    @if ($payment->participant?->conference)
                                        <strong>
                                            {{ $payment->participant->conference->short_name }}
                                        </strong>
                                        <small class="text-muted d-block">
                                            {{ $payment->participant->conference->year }}
                                        </small>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>
                                    <strong>
                                        Rp
                                        {{ number_format($payment->amount, 0, ',', '.') }}
                                    </strong>
                                </td>
                                <td>
                                    {{ ucwords(str_replace('_', ' ', $payment->payment_method)) }}
                                </td>
                                <td>
                                    @if ($payment->status === 'verified')
                                        <span class="badge text-bg-success rounded-0">
                                            Verified
                                        </span>
                                    @elseif ($payment->status === 'rejected')
                                        <span class="badge text-bg-danger rounded-0">
                                            Rejected
                                        </span>
                                    @else
                                        <span class="badge text-bg-warning rounded-0">
                                            Pending
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    {{ $payment->paid_at?->format('d M Y H:i') ?? '—' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="bi bi-credit-card-2-front display-5 text-muted"></i>
                                    <h5 class="mt-3">
                                        No Payments Found
                                    </h5>
                                    <p class="text-muted mb-3">
                                        You have not submitted a payment yet.
                                    </p>
                                    <a href="{{ route('participant.payments.create') }}" class="btn btn-success rounded-0">
                                        <i class="bi bi-upload me-1"></i>
                                        Submit Payment
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
