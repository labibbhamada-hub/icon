@extends('layouts.admin')

@section('title', 'Payments Management')

@section('header')

    <div class="row align-items-center">

        <div class="col-sm-6">

            <h1 class="mb-0 fs-3">
                Payments Management
            </h1>

        </div>

        <div class="col-sm-6">

            <nav aria-label="breadcrumb">

                <ol class="breadcrumb float-sm-end mb-0">

                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">
                            Dashboard
                        </a>
                    </li>

                    <li class="breadcrumb-item active" aria-current="page">
                        Payments
                    </li>

                </ol>

            </nav>

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

                            <th width="50">
                                No
                            </th>

                            <th>
                                Payment
                            </th>

                            <th>
                                Participant
                            </th>

                            <th>
                                Conference
                            </th>

                            <th>
                                Registration Type
                            </th>

                            <th>
                                Payment Method
                            </th>

                            <th>
                                Amount
                            </th>

                            <th>
                                Status
                            </th>

                            <th width="80">
                                Action
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse ($payments as $payment)

                            <tr>

                                <td>
                                    {{ $payments->firstItem() + $loop->index }}
                                </td>

                                {{-- Payment --}}
                                <td>

                                    <div class="fw-semibold">
                                        {{ $payment->payment_code }}
                                    </div>

                                    <small class="text-muted d-block">
                                        Paid:
                                        {{ $payment->paid_at?->format('d M Y H:i') ?? '-' }}
                                    </small>

                                    @if ($payment->verified_at)
                                        <small class="text-muted d-block">
                                            Verified:
                                            {{ $payment->verified_at->format('d M Y H:i') }}
                                        </small>
                                    @endif

                                </td>

                                {{-- Participant --}}
                                <td>

                                    @if ($payment->participant)
                                        <div class="fw-semibold">
                                            {{ $payment->participant->full_name }}
                                        </div>

                                        <small class="text-muted d-block">
                                            {{ $payment->participant->registration_number }}
                                        </small>

                                        @if ($payment->participant->email)
                                            <small class="text-muted d-block">
                                                {{ $payment->participant->email }}
                                            </small>
                                        @endif
                                    @else
                                        <span class="text-muted">
                                            -
                                        </span>
                                    @endif

                                </td>

                                {{-- Conference --}}
                                <td>

                                    @if ($payment->participant?->conference)
                                        <strong>
                                            {{ $payment->participant->conference->short_name }}
                                        </strong>

                                        <small class="text-muted d-block">
                                            {{ $payment->participant->conference->year }}
                                        </small>
                                    @else
                                        <span class="text-muted">
                                            -
                                        </span>
                                    @endif

                                </td>

                                {{-- Registration Type --}}
                                <td>

                                    @if ($payment->participant?->registrationType)
                                        <div class="fw-semibold">
                                            {{ $payment->participant->registrationType->name }}
                                        </div>

                                        <small class="text-muted d-block">
                                            {{ ucfirst($payment->participant->registrationType->category) }}
                                        </small>
                                    @else
                                        <span class="text-muted">
                                            -
                                        </span>
                                    @endif

                                </td>

                                {{-- Payment Method --}}
                                <td>

                                    @if ($payment->paymentMethod)
                                        <div class="fw-semibold">
                                            {{ $payment->paymentMethod->name }}
                                        </div>

                                        @if ($payment->paymentMethod->type)
                                            <small class="text-muted d-block">
                                                {{ ucwords(str_replace('_', ' ', $payment->paymentMethod->type)) }}
                                            </small>
                                        @endif
                                    @else
                                        <span class="text-muted">
                                            -
                                        </span>
                                    @endif

                                </td>

                                {{-- Amount --}}
                                <td>

                                    <strong class="text-nowrap">

                                        {{ $payment->participant?->registrationType?->currency ?? 'IDR' }}

                                        {{ number_format($payment->amount, 0, ',', '.') }}

                                    </strong>

                                </td>

                                {{-- Status --}}
                                <td>

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

                                </td>

                                {{-- Action --}}
                                <td>

                                    <a href="{{ route('admin.payments.show', $payment) }}"
                                        class="btn btn-info btn-sm rounded-0" title="View Payment">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="9" class="text-center py-5">

                                    <div class="mb-2">

                                        <i class="bi bi-credit-card display-5 text-muted"></i>

                                    </div>

                                    <h5 class="mb-1">
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
