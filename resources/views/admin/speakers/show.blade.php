@extends('layouts.admin')

@section('title', 'Speaker Detail')

@section('header')
    <div class="row">
        <div class="col-sm-6 d-flex align-items-center gap-2">
            <a href="{{ route('admin.speakers.index') }}" class="btn btn-secondary btn-sm rounded-0">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h1 class="mb-0 fs-3">
                Speaker Detail
            </h1>
        </div>
        <div class="col-sm-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.speakers.index') }}">Speaker</a>
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
                Speaker Information
            </h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-2">
                    @if ($speaker->photo)
                        <img src="{{ asset('storage/' . $speaker->photo) }}" alt="{{ $speaker->name }}"
                            class="img-thumbnail w-100" style="aspect-ratio: 1 / 1; object-fit: cover;">
                    @else
                        <div class="border rounded-0 d-flex align-items-center justify-content-center bg-light w-100"
                            style="aspect-ratio: 1 / 1;">
                            <div class="text-center text-muted">
                                <i class="bi bi-person display-1"></i>
                                <div>
                                    No Photo
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="col-md-9 mb-2">
                    <h2 class="fw-bold mb-2">
                        {{ $speaker->name }}
                    </h2>
                    @if ($speaker->title)
                        <p class="text-muted">
                            {{ $speaker->title }}
                        </p>
                    @endif
                    <hr>
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <strong>Conference</strong>
                        </div>
                        <div class="col-md-8">
                            @if ($speaker->conference)
                                <strong>
                                    {{ $speaker->conference->name }}
                                </strong>
                                <small class="text-muted d-block">
                                    {{ $speaker->conference->short_name }}
                                    ({{ $speaker->conference->year }})
                                </small>
                            @else
                                —
                            @endif
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <strong>Institution</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $speaker->institution ?: '—' }}
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <strong>Position</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $speaker->position ?: '—' }}
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <strong>Email</strong>
                        </div>
                        <div class="col-md-8">
                            @if ($speaker->email)
                                <a href="mailto:{{ $speaker->email }}">
                                    {{ $speaker->email }}
                                </a>
                            @else
                                —
                            @endif
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <strong>LinkedIn</strong>
                        </div>
                        <div class="col-md-8">
                            @if ($speaker->linkedin)
                                <a href="{{ $speaker->linkedin }}" target="_blank" rel="noopener noreferrer">
                                    {{ $speaker->linkedin }}
                                    <i class="bi bi-box-arrow-up-right ms-1"></i>
                                </a>
                            @else
                                —
                            @endif
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <strong>Website</strong>
                        </div>
                        <div class="col-md-8">
                            @if ($speaker->website)
                                <a href="{{ $speaker->website }}" target="_blank" rel="noopener noreferrer">
                                    {{ $speaker->website }}
                                    <i class="bi bi-box-arrow-up-right ms-1"></i>
                                </a>
                            @else
                                —
                            @endif
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <strong>Sort Order</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $speaker->sort_order }}
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <strong>Status</strong>
                        </div>
                        <div class="col-md-8">
                            @if ($speaker->is_active)
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
                </div>
            </div>
        </div>
        <div class="card-body border-top">
            <div class="row">
                <div class="col-12">
                    <h5 class="fw-bold mb-2">
                        Biography
                    </h5>
                    @if ($speaker->bio)
                        <div>
                            {!! nl2br(e($speaker->bio)) !!}
                        </div>
                    @else
                        <p class="text-muted">
                            No biography available.
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
