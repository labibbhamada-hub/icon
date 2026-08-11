@extends('layouts.admin')

@section('title', 'Partner Detail')

@section('header')
    <div class="row">
        <div class="col-sm-6 d-flex align-items-center gap-2">
            <a href="{{ route('admin.partners.index') }}" class="btn btn-secondary btn-sm rounded-0">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h1 class="mb-0 fs-3">
                Partner Detail
            </h1>
        </div>
        <div class="col-sm-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.partners.index') }}">Partner</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Detail</li>
                </ol>
            </nav>
        </div>
    </div>
@endsection

@section('content')
    <div class="card rounded-0">
        <div class="card-header">
            <h3 class="card-title">
                Partner Information
            </h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-2">
                    @if ($partner->logo)
                        <img src="{{ asset('storage/' . $partner->logo) }}" alt="{{ $partner->name }}"
                            class="img-thumbnail w-100" style="aspect-ratio: 1 / 1; object-fit: cover;">
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
                <div class="col-md-9">
                    <h2 class="fw-bold mb-2">
                        {{ $partner->name }}
                    </h2>
                    <div class="mb-2">
                        <span class="badge text-bg-secondary rounded-0">
                            {{ ucwords(str_replace('_', ' ', $partner->type)) }}
                        </span>
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
                    <hr>
                    <div class="row mb-2">
                        <div class="col-md-4">
                            Conference
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
                                —
                            @endif
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4">
                            Website
                        </div>
                        <div class="col-md-8">
                            @if ($partner->website)
                                <a href="{{ $partner->website }}" target="_blank" rel="noopener noreferrer">
                                    {{ $partner->website }}
                                    <i class="bi bi-box-arrow-up-right ms-1"></i>
                                </a>
                            @else
                                —
                            @endif
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4">
                            Sort Order
                        </div>
                        <div class="col-md-8">
                            {{ $partner->sort_order }}
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4">
                            Created At
                        </div>
                        <div class="col-md-8">
                            {{ $partner->created_at->format('d M Y H:i') }}
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4">
                            Last Updated
                        </div>
                        <div class="col-md-8">
                            {{ $partner->updated_at->format('d M Y H:i') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body border-top">
            <div class="row">
                <div class="col-12">
                    <h5 class="fw-bold mb-2">
                        Description
                    </h5>
                    @if ($partner->description)
                        <div>
                            {!! nl2br(e($partner->description)) !!}
                        </div>
                    @else
                        <p class="text-muted">
                            No description available.
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
