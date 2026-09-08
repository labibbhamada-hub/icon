@extends('layouts.admin')

@section('title', 'Registration Type Details')

@section('header')

    <div class="row">

        <div class="col-sm-6 d-flex align-items-center gap-2">

            <a href="{{ route('admin.registration-types.index') }}" class="btn btn-secondary btn-sm rounded-0" title="Back">
                <i class="bi bi-arrow-left"></i>
            </a>

            <h1 class="mb-0 fs-3">
                Registration Type Details
            </h1>

        </div>

        <div class="col-sm-6">

            <nav aria-label="breadcrumb">

                <ol class="breadcrumb float-sm-end">

                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">
                            Dashboard
                        </a>
                    </li>

                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.registration-types.index') }}">
                            Registration Types
                        </a>
                    </li>

                    <li class="breadcrumb-item active" aria-current="page">
                        Details
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
                <i class="bi bi-tags me-2"></i>
                {{ $conferenceRegistrationType->name }}
            </h3>

            <div class="float-end">

                <a href="{{ route('admin.registration-types.edit', $conferenceRegistrationType) }}"
                    class="btn btn-warning btn-sm rounded-0">
                    <i class="bi bi-pencil me-1"></i>
                    Edit Registration Type
                </a>

            </div>

        </div>

        <div class="card-body">

            <div class="row">

                {{-- Conference --}}
                <div class="col-md-6 mb-3">

                    <small class="text-muted d-block">
                        Conference
                    </small>

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

                {{-- Code --}}
                <div class="col-md-6 mb-3">

                    <small class="text-muted d-block">
                        Code
                    </small>

                    <strong>
                        {{ $conferenceRegistrationType->code }}
                    </strong>

                </div>

                {{-- Category --}}
                <div class="col-md-6 mb-3">

                    <small class="text-muted d-block">
                        Category
                    </small>

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

                {{-- Pricing --}}
                <div class="col-md-6 mb-3">

                    <small class="text-muted d-block">
                        @if ($conferenceRegistrationType->category === 'presenter')
                            Presentation Pricing
                        @else
                            Registration Fee
                        @endif
                    </small>

                    @if ($conferenceRegistrationType->category === 'presenter')

                        @php
                            $oralPrice = $conferenceRegistrationType->presentationPrices->firstWhere(
                                'presentation_type',
                                'oral',
                            );

                            $posterPrice = $conferenceRegistrationType->presentationPrices->firstWhere(
                                'presentation_type',
                                'poster',
                            );
                        @endphp

                        @if ($oralPrice || $posterPrice)

                            <div class="mt-1">

                                @if ($oralPrice)
                                    <div>
                                        <span class="text-muted">
                                            Oral:
                                        </span>

                                        <strong class="text-success">
                                            {{ $oralPrice->currency }}
                                            {{ number_format($oralPrice->fee, 0, ',', '.') }}
                                        </strong>
                                    </div>
                                @endif

                                @if ($posterPrice)
                                    <div class="mt-1">
                                        <span class="text-muted">
                                            Poster:
                                        </span>

                                        <strong class="text-success">
                                            {{ $posterPrice->currency }}
                                            {{ number_format($posterPrice->fee, 0, ',', '.') }}
                                        </strong>
                                    </div>
                                @endif

                            </div>
                        @else
                            <span class="text-muted">
                                Presentation pricing not configured.
                            </span>

                        @endif
                    @else
                        <strong class="text-success">
                            {{ $conferenceRegistrationType->currency }}
                            {{ number_format($conferenceRegistrationType->fee, 0, ',', '.') }}
                        </strong>

                    @endif

                </div>

                {{-- Payment Timing --}}
                <div class="col-md-6 mb-3">

                    <small class="text-muted d-block">
                        Payment Timing
                    </small>

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

                {{-- Included Papers --}}
                <div class="col-md-6 mb-3">

                    <small class="text-muted d-block">
                        Included Papers
                    </small>

                    <strong>
                        {{ $conferenceRegistrationType->included_papers }}
                    </strong>

                </div>

                {{-- Additional Paper Fee --}}
                <div class="col-md-6 mb-3">

                    <small class="text-muted d-block">
                        Additional Paper Fee
                    </small>

                    <strong class="text-success">
                        {{ $conferenceRegistrationType->currency }}
                        {{ number_format($conferenceRegistrationType->additional_paper_fee, 0, ',', '.') }}
                    </strong>

                </div>

                {{-- Currency --}}
                <div class="col-md-6 mb-3">

                    <small class="text-muted d-block">
                        Currency
                    </small>

                    <strong>
                        {{ $conferenceRegistrationType->currency }}
                    </strong>

                </div>

                {{-- Status --}}
                <div class="col-md-6 mb-3">

                    <small class="text-muted d-block">
                        Status
                    </small>

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

                {{-- Sort Order --}}
                <div class="col-md-6 mb-3">

                    <small class="text-muted d-block">
                        Sort Order
                    </small>

                    <span>
                        {{ $conferenceRegistrationType->sort_order }}
                    </span>

                </div>

            </div>

        </div>

        {{-- Description --}}
        <div class="card-body border-top">

            <h5 class="fw-bold mb-2">
                Description
            </h5>

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

        {{-- Benefits --}}
        <div class="card-body border-top">

            <h5 class="fw-bold mb-2">
                Benefits
            </h5>

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

        <div class="card-footer">

            <a href="{{ route('admin.registration-types.index') }}" class="btn btn-secondary btn-sm rounded-0">
                <i class="bi bi-arrow-left me-1"></i>
                Back to Registration Types
            </a>

        </div>

    </div>

@endsection
