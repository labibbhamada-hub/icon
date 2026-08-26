@extends('layouts.admin')

@section('title', 'Edit Payment Method')

@section('header')
    <div class="row align-items-center">

        <div class="col-sm-6">

            <div class="d-flex align-items-center gap-2">

                <a href="{{ route('admin.conferences.payment-methods.index', $conference) }}"
                    class="btn btn-secondary btn-sm rounded-0">

                    <i class="bi bi-arrow-left"></i>

                </a>

                <h1 class="mb-0 fs-3">
                    Edit Payment Method
                </h1>

            </div>

            <p class="text-muted mb-0 mt-1">
                Update payment method information.
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
                    <a href="{{ route('admin.conferences.index') }}">
                        Conferences
                    </a>
                </li>

                <li class="breadcrumb-item">
                    <a href="{{ route('admin.conferences.show', $conference) }}">
                        Detail
                    </a>
                </li>

                <li class="breadcrumb-item">
                    <a href="{{ route('admin.conferences.payment-methods.index', $conference) }}">
                        Payment Methods
                    </a>
                </li>

                <li class="breadcrumb-item active">
                    Edit
                </li>

            </ol>

        </div>

    </div>
@endsection

@section('content')

    @if ($errors->any())
        <div class="alert alert-danger rounded-0">

            <strong>
                Please correct the following:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach ($errors->all() as $error)
                    <li>
                        {{ $error }}
                    </li>
                @endforeach

            </ul>

        </div>
    @endif

    <div class="card rounded-0">

        <div class="card-header">

            <h3 class="card-title">
                <i class="bi bi-credit-card me-2"></i>
                Payment Method
            </h3>

        </div>

        <form action="{{ route('admin.conferences.payment-methods.update', [$conference, $paymentMethod]) }}" method="POST"
            enctype="multipart/form-data">

            @csrf
            @method('PUT')

            <div class="card-body">

                @include('admin.conference-payment-methods._form', [
                    'paymentMethod' => $paymentMethod,
                ])

            </div>

            <div class="card-footer text-end">

                <a href="{{ route('admin.conferences.payment-methods.index', $conference) }}"
                    class="btn btn-secondary btn-sm rounded-0 me-1">

                    Cancel

                </a>

                <button type="submit" class="btn btn-success btn-sm rounded-0">

                    <i class="bi bi-check-circle me-1"></i>
                    Update Payment Method

                </button>

            </div>

        </form>

    </div>

@endsection
