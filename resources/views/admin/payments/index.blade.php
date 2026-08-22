@extends('layouts.admin')

@section('title', 'Payments Management')

@section('header')
    <div class="row align-items-center">
        <div class="col-sm-6">
            <h3 class="mb-0 fs-3">Payments Management</h3>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item active">Payments</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="card rounded-0">
        <div class="card-header">
            <h3 class="card-title">
                <i class="bi bi-credit-card me-2"></i>
                Payment List
            </h3>
            <div class="float-end">
                <a href="{{ route('admin.payments.export') }}" class="btn btn-dark btn-sm rounded-0">
                    <i class="bi bi-file-earmark-excel me-1"></i>
                    Export Excel
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th width="40">No</th>
                            <th>Payment</th>
                            <th>Participant</th>
                            <th>Conference</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th width="40">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($payments as $payment)
                            <tr>
                                <td class="align-top">
                                    {{ $payments->firstItem() + $loop->index }}
                                </td>
                                <td class="align-top">
                                    <strong>
                                        {{ $payment->payment_code }}
                                    </strong>
                                    <small class="text-muted d-block">
                                        {{ $payment->paid_at?->format('d M Y H:i') ?? '—' }}
                                    </small>
                                </td>
                                <td class="align-top">
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
                                </td>
                                <td class="align-top">
                                    {{ $payment->participant?->conference?->short_name ?? '—' }}
                                </td>
                                <td class="align-top">
                                    <strong>
                                        Rp
                                        {{ number_format($payment->amount, 0, ',', '.') }}
                                    </strong>
                                </td>
                                <td class="align-top">
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
                                <td class="align-top">
                                    <a href="{{ route('admin.payments.show', $payment) }}"
                                        class="btn btn-info btn-sm rounded-0" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="bi bi-credit-card display-5 text-muted"></i>
                                    <h5 class="mt-3">
                                        No Payments Found
                                    </h5>
                                    <p class="text-muted mb-0">
                                        There are no payment records yet.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($payments->hasPages())
            <div class="card-footer">
                {{ $payments->links() }}
            </div>
        @endif
    </div>
@endsection
