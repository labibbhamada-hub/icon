@extends('layouts.admin')

@section('title', 'Registration Type Details')

@section('header')
    <div class="row">
        <div class="col-sm-8">
            <div class="d-flex gap-2">
                <a href="{{ route('admin.registration-types.index') }}" class="btn btn-secondary rounded-0">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h1 class="mb-0 fs-3">
                    Registration Type Details
                </h1>
            </div>
            <p class="text-muted mb-0">
                View the details, fees, and benefits of this registration type.
            </p>
        </div>
    </div>
@endsection

@section('content')
    <div class="card rounded-0 overflow-hidden">
        <div class="card-header">
            <h3 class="card-title">
                <i class="bi bi-tags me-2"></i>
                {{ $conferenceRegistrationType->name }}
            </h3>
        </div>
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-md-4">
                    <strong>
                        Conference
                    </strong>
                </div>
                <div class="col-md-8">
                    @if ($conferenceRegistrationType->conference)
                        <strong>
                            {{ $conferenceRegistrationType->conference->name }}
                        </strong>
                        <small class="text-muted d-block">
                            {{ $conferenceRegistrationType->conference->short_name }}
                            ({{ $conferenceRegistrationType->conference->year }})
                        </small>
                    @else
                        <span class="text-muted">
                            -
                        </span>
                    @endif
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-4">
                    <strong>
                        Code
                    </strong>
                </div>
                <div class="col-md-8">
                    <strong>
                        {{ $conferenceRegistrationType->code }}
                    </strong>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-4">
                    <strong>
                        Category
                    </strong>
                </div>
                <div class="col-md-8">
                    @if ($conferenceRegistrationType->category === 'presenter')
                        <span class="badge text-bg-primary rounded-0">
                            Presenter
                        </span>
                    @else
                        <span class="badge text-bg-secondary rounded-0">
                            Participant
                        </span>
                    @endif
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-4">
                    <strong>
                        Description
                    </strong>
                </div>
                <div class="col-md-8">
                    @if ($conferenceRegistrationType->description)
                        <div>
                            {!! nl2br(e($conferenceRegistrationType->description)) !!}
                        </div>
                    @else
                        <p class="text-muted mb-0">
                            No description available.
                        </p>
                    @endif
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-4">
                    <strong>
                        Benefits
                    </strong>
                </div>
                <div class="col-md-8">
                    @if ($conferenceRegistrationType->benefits)
                        <ul class="mb-0">
                            @foreach (preg_split('/\r\n|\r|\n/', $conferenceRegistrationType->benefits) as $benefit)
                                @if (trim($benefit))
                                    <li>
                                        {{ trim($benefit) }}
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted mb-0">
                            No benefits specified.
                        </p>
                    @endif
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-4">
                    <strong>
                        Registration Fee
                    </strong>
                </div>
                <div class="col-md-8">
                    <strong class="text-success">
                        {{ $conferenceRegistrationType->currency }}
                        {{ number_format($conferenceRegistrationType->fee, 0, ',', '.') }}
                    </strong>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-4">
                    <strong>
                        Payment Timing
                    </strong>
                </div>
                <div class="col-md-8">
                    @if ($conferenceRegistrationType->payment_timing === 'after_acceptance')
                        <span>
                            After Paper Acceptance
                        </span>
                    @else
                        <span>
                            During Registration
                        </span>
                    @endif
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-4">
                    <strong>
                        Included Papers
                    </strong>
                </div>
                <div class="col-md-8">
                    <strong>
                        {{ $conferenceRegistrationType->included_papers }}
                    </strong>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-4">
                    <strong>
                        Additional Paper Fee
                    </strong>
                </div>
                <div class="col-md-8">
                    <strong class="text-success">
                        {{ $conferenceRegistrationType->currency }}
                        {{ number_format($conferenceRegistrationType->additional_paper_fee, 0, ',', '.') }}
                    </strong>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-4">
                    <strong>
                        Currency
                    </strong>
                </div>
                <div class="col-md-8">
                    <strong>
                        {{ $conferenceRegistrationType->currency }}
                    </strong>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-4">
                    <strong>
                        Status
                    </strong>
                </div>
                <div class="col-md-8">
                    @if ($conferenceRegistrationType->is_active)
                        <span class="badge text-bg-success rounded-0">
                            Active
                        </span>
                    @else
                        <span class="badge text-bg-secondary rounded-0">
                            Inactive
                        </span>
                    @endif
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-4">
                    <strong>
                        Sort Order
                    </strong>
                </div>
                <div class="col-md-8">
                    {{ $conferenceRegistrationType->sort_order }}
                </div>
            </div>
        </div>
        <div class="card-footer rounded-0">
            <a href="{{ route('admin.registration-types.edit', $conferenceRegistrationType) }}"
                class="btn btn-warning rounded-0">
                <i class="bi bi-pencil me-1"></i>
                Edit Registration Type
            </a>
        </div>
    </div>
@endsection
