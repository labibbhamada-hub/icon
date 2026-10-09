@extends('layouts.admin')

@section('title', 'Payment Methods')

@section('header')
    <div class="row align-items-top">
        <div class="col-sm-6">
            <h1 class="mb-0 fs-3">
                Payment Methods
            </h1>
            <p class="text-muted mb-0">
                Manage payment methods available for this conference.
            </p>
        </div>
        <div class="col-sm-6">
            <a href="{{ route('admin.conferences.payment-methods.create', $conference) }}"
                class="btn btn-success float-sm-end rounded-0">
                <i class="bi bi-plus-circle me-1"></i>
                Add Payment Method
            </a>
        </div>
    </div>
@endsection

@section('content')
    @if ($paymentMethods->isEmpty())
        <div class="card rounded-0 overflow-hidden">

        </div>
    @else
        <div class="card rounded-0 overflow-hidden">
            <div class="card-header rounded-0">
                <h3 class="card-title">
                    <i class="bi bi-credit-card me-2"></i>
                    {{ $conference->name }}
                </h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive rounded-0">
                    <table class="table table-hover align-top mb-0">
                        <thead>
                            <tr>
                                <th width="50">
                                    Order
                                </th>
                                <th>
                                    Payment Method
                                </th>
                                <th>
                                    Provider
                                </th>
                                <th>
                                    Currency
                                </th>
                                <th>
                                    Account / Identifier
                                </th>
                                <th>
                                    Status
                                </th>
                                <th width="150">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($paymentMethods as $paymentMethod)
                                <tr>
                                    <td>
                                        {{ $paymentMethod->sort_order }}
                                    </td>
                                    <td>
                                        <strong>
                                            {{ $paymentMethod->name }}
                                        </strong>
                                        <small class="text-muted d-block">
                                            {{ ucwords(str_replace('_', ' ', $paymentMethod->type)) }}
                                        </small>
                                    </td>
                                    <td>
                                        {{ $paymentMethod->provider ?? '—' }}
                                    </td>
                                    <td>
                                        {{ strtoupper($paymentMethod->currency) }}
                                    </td>
                                    <td>
                                        @if ($paymentMethod->account_number)
                                            {{ $paymentMethod->account_number }}
                                        @elseif ($paymentMethod->qr_code_file)
                                            <span class="text-success">
                                                QR Code Available
                                            </span>
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td>
                                        @if ($paymentMethod->is_active)
                                            <span class="badge text-bg-success rounded-0">
                                                Active
                                            </span>
                                        @else
                                            <span class="badge text-bg-secondary rounded-0">
                                                Inactive
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <form
                                            action="{{ route('admin.conferences.payment-methods.destroy', [$conference, $paymentMethod]) }}"
                                            method="POST" class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <a href="{{ route('admin.conferences.payment-methods.edit', [$conference, $paymentMethod]) }}"
                                                class="btn btn-warning rounded-0" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="submit" class="btn btn-danger rounded-0"
                                                title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="mb-2">
                                            <i class="bi bi-credit-card-2-front display-5 text-muted"></i>
                                        </div>
                                        <h5 class="mb-1">
                                            No Payment Methods
                                        </h5>
                                        <p class="text-muted mb-3">
                                            This conference does not have any payment methods yet.
                                        </p>
                                        <a href="{{ route('admin.conferences.payment-methods.create', $conference) }}"
                                            class="btn btn-success rounded-0">
                                            <i class="bi bi-plus-circle me-1"></i>
                                            Add Payment Method
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if ($paymentMethods->hasPages())
                <div class="card-footer rounded-0">
                    {{ $paymentMethods->links() }}
                </div>
            @endif

        </div>
    @endif
@endsection
