@extends('layouts.admin')

@section('title', 'Speaker Detail')

@section('header')

    <div class="row">

        <div class="col-sm-6 d-flex align-items-center gap-2">

            <a href="{{ route('admin.speakers.index') }}" class="btn btn-secondary btn-sm rounded-0" title="Back">
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
                        <a href="{{ route('admin.dashboard') }}">
                            Dashboard
                        </a>
                    </li>

                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.speakers.index') }}">
                            Speakers
                        </a>
                    </li>

                    <li class="breadcrumb-item active" aria-current="page">
                        Detail
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
                <i class="bi bi-mic me-2"></i>
                Speaker Information
            </h3>

            <div class="float-end">

                <a href="{{ route('admin.speakers.edit', $speaker) }}" class="btn btn-warning btn-sm rounded-0">
                    <i class="bi bi-pencil me-1"></i>
                    Edit Speaker
                </a>

            </div>

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

                    <table class="table table-borderless align-middle mb-0">

                        <tbody>

                            <tr>
                                <th width="180">
                                    Conference
                                </th>

                                <td>

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

                                </td>
                            </tr>

                            <tr>
                                <th>
                                    Institution
                                </th>

                                <td>
                                    {{ $speaker->institution ?: '-' }}
                                </td>
                            </tr>

                            <tr>
                                <th>
                                    Position
                                </th>

                                <td>
                                    {{ $speaker->position ?: '-' }}
                                </td>
                            </tr>

                            <tr>
                                <th>
                                    Email
                                </th>

                                <td>

                                    @if ($speaker->email)
                                        <a href="mailto:{{ $speaker->email }}">
                                            {{ $speaker->email }}
                                        </a>
                                    @else
                                        -
                                    @endif

                                </td>
                            </tr>

                            <tr>
                                <th>
                                    LinkedIn
                                </th>

                                <td>

                                    @if ($speaker->linkedin)
                                        <a href="{{ $speaker->linkedin }}" target="_blank" rel="noopener noreferrer">
                                            {{ $speaker->linkedin }}
                                            <i class="bi bi-box-arrow-up-right ms-1"></i>
                                        </a>
                                    @else
                                        -
                                    @endif

                                </td>
                            </tr>

                            <tr>
                                <th>
                                    Website
                                </th>

                                <td>

                                    @if ($speaker->website)
                                        <a href="{{ $speaker->website }}" target="_blank" rel="noopener noreferrer">
                                            {{ $speaker->website }}
                                            <i class="bi bi-box-arrow-up-right ms-1"></i>
                                        </a>
                                    @else
                                        -
                                    @endif

                                </td>
                            </tr>

                            <tr>
                                <th>
                                    Sort Order
                                </th>

                                <td>
                                    {{ $speaker->sort_order }}
                                </td>
                            </tr>

                            <tr>
                                <th>
                                    Status
                                </th>

                                <td>

                                    @if ($speaker->is_active)
                                        <span class="badge text-bg-success rounded-0">
                                            Active
                                        </span>
                                    @else
                                        <span class="badge text-bg-secondary rounded-0">
                                            Inactive
                                        </span>
                                    @endif

                                </td>
                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

        <div class="card-body border-top">

            <h5 class="fw-bold mb-2">
                Biography
            </h5>

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

        <div class="card-footer">

            <a href="{{ route('admin.speakers.index') }}" class="btn btn-secondary btn-sm rounded-0">
                <i class="bi bi-arrow-left me-1"></i>
                Back to Speakers
            </a>

        </div>

    </div>

@endsection
