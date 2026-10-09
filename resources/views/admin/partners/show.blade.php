@extends('layouts.admin')

@section('title', 'Partner Detail')

@section('header')
    <div class="row">
        <div class="col-sm-6">
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('admin.partners.index') }}" class="btn btn-secondary rounded-0" title="Back">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h1 class="mb-0 fs-3">
                    Partner Detail
                </h1>
            </div>
            <p class="text-muted mb-0">
                View the details and information of this conference partner.
            </p>
        </div>
    </div>
@endsection

@section('content')
    <div class="card rounded-0 overflow-hidden">
        <div class="card-header rounded-0">
            <h3 class="card-title">
                <i class="bi bi-buildings me-2"></i>
                Partner Information
            </h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-2">
                    @if ($partner->logo)
                        <img src="{{ asset('storage/' . $partner->logo) }}" alt="{{ $partner->name }}"
                            class="img-thumbnail rounded-0 w-100" style="aspect-ratio: 1 / 1; object-fit: contain;">
                    @else
                        <div class="border rounded-0 d-flex align-items-center justify-content-center bg-light w-100"
                            style="aspect-ratio: 1 / 1;">
                            <div class="text-center text-muted">
                                <i class="bi bi-building display-1"></i>
                                <div>
                                    No Logo
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="col-md-8">
                    <h2 class="fw-bold mb-1">
                        {{ $partner->name }}
                    </h2>
                    <hr>
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <strong>Partner Type</strong>
                        </div>
                        <div class="col-md-8">
                            <span class="badge text-bg-secondary rounded-0">
                                {{ ucwords(str_replace('_', ' ', $partner->type)) }}
                            </span>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <strong>Conference</strong>
                        </div>
                        <div class="col-md-8">
                            @if ($partner->conference)
                                <strong>
                                    {{ $partner->conference->name }}
                                </strong>
                                <small class="text-muted d-block">
                                    {{ $partner->conference->short_name }}
                                    ({{ $partner->conference->year }})
                                </small>
                            @else
                                -
                            @endif
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <strong>Website</strong>
                        </div>
                        <div class="col-md-8">
                            @if ($partner->website)
                                <a href="{{ $partner->website }}" target="_blank" rel="noopener noreferrer">
                                    {{ $partner->website }}
                                    <i class="bi bi-box-arrow-up-right ms-1"></i>
                                </a>
                            @else
                                -
                            @endif
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <strong>Sort Order</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $partner->sort_order }}
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <strong>Status</strong>
                        </div>
                        <div class="col-md-8">
                            @if ($partner->is_active)
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
                            <strong>Created At</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $partner->created_at->format('d M Y H:i') }}
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <strong>Last Updated</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $partner->updated_at->format('d M Y H:i') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body border-top">
            <strong>Description</strong>
            @if ($partner->description)
                <div>
                    {!! nl2br(e($partner->description)) !!}
                </div>
            @else
                <p class="text-muted mb-0">
                    No description available.
                </p>
            @endif
        </div>
        <div class="card-footer">
            <a href="{{ route('admin.partners.edit', $partner) }}" class="btn btn-warning rounded-0">
                <i class="bi bi-pencil me-1"></i>
                Edit Partner
            </a>
        </div>
    </div>
@endsection
