@extends('layouts.admin')

@section('title', 'Registration Type Details')

@section('header')
    <div class="row">
        <div class="col-sm-6 d-flex align-items-center gap-2">
            <a href="{{ route('admin.registration-types.index') }}" class="btn btn-secondary btn-sm rounded-0">
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
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.registration-types.index') }}">Registration Types</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Show</li>
                </ol>
            </nav>
        </div>
    </div>
@endsection

@section('content')
    <div class="card rounded-0">
        <div class="card-header">
            <h3 class="card-title">
                {{ $conferenceRegistrationType->name }}
            </h3>
            <div class="float-end">
                <a href="{{ route('admin.registration-types.edit', $conferenceRegistrationType) }}"
                    class="btn btn-warning btn-sm rounded-0">
                    <i class="bi bi-pencil me-1"></i>
                    Edit
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <small class="text-muted d-block">
                        Conference
                    </small>
                    <strong>
                        {{ $conferenceRegistrationType->conference?->name ?? '—' }}
                    </strong>
                </div>
                <div class="col-md-6 mb-3">
                    <small class="text-muted d-block">
                        Code
                    </small>
                    <strong>
                        {{ $conferenceRegistrationType->code }}
                    </strong>
                </div>
                <div class="col-md-6 mb-3">
                    <small class="text-muted d-block">
                        Category
                    </small>
                    <span>
                        {{ ucfirst($conferenceRegistrationType->category) }}
                    </span>
                </div>
                <div class="col-md-6 mb-3">
                    <small class="text-muted d-block">
                        Fee
                    </small>
                    <strong>
                        {{ $conferenceRegistrationType->currency }}
                        {{ number_format($conferenceRegistrationType->fee, 2, ',', '.') }}
                    </strong>
                </div>
                <div class="col-md-12 mb-3">
                    <small class="text-muted d-block">
                        Description
                    </small>
                    <div>
                        {!! nl2br(e($conferenceRegistrationType->description ?? '—')) !!}
                    </div>
                </div>
                <div class="col-md-12 mb-3">
                    <small class="text-muted d-block">
                        Benefits
                    </small>
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
                        —
                    @endif
                </div>
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
    </div>
@endsection
