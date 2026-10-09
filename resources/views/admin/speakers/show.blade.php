@extends('layouts.admin')

@section('title', 'Speaker Detail')

@section('header')
    <div class="row">
        <div class="col-sm-6">
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('admin.speakers.index') }}" class="btn btn-secondary rounded-0" title="Back">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h1 class="mb-0 fs-3">
                    Speaker Detail
                </h1>
            </div>
            <p class="text-muted mb-0">
                View the details and information of this conference speaker.
            </p>
        </div>
    </div>
@endsection

@section('content')
    <div class="card rounded-0 overflow-hidden">
        <div class="card-header rounded-0">
            <h3 class="card-title">
                <i class="bi bi-mic me-2"></i>
                Speaker Information
            </h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    @if ($speaker->photo)
                        <img src="{{ asset('storage/' . $speaker->photo) }}" alt="{{ $speaker->name }}"
                            class="img-thumbnail rounded-0 w-100" style="aspect-ratio: 1 / 1; object-fit: cover;">
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
                <div class="col-md-9">
                    <h2 class="fw-bold mb-1">
                        {{ $speaker->name }}
                    </h2>
                    @if ($speaker->title)
                        <div class="text-muted mb-3">
                            {{ $speaker->title }}
                        </div>
                    @endif
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
                                -
                            @endif
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <strong>Institution</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $speaker->institution ?: '-' }}
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <strong>Position</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $speaker->position ?: '-' }}
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
                                -
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
                                -
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
                                -
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
            <strong>Biography</strong>
            @if ($speaker->bio)
                <div>
                    {!! nl2br(e($speaker->bio)) !!}
                </div>
            @else
                <p class="text-muted mb-0">
                    No biography available.
                </p>
            @endif
        </div>
        <div class="card-footer rounded-0">
            <a href="{{ route('admin.speakers.edit', $speaker) }}" class="btn btn-warning rounded-0">
                <i class="bi bi-pencil me-1"></i>
                Edit Speaker
            </a>
        </div>
    </div>
@endsection
