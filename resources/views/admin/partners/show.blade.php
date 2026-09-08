@extends('layouts.admin')

@section('title', 'Partner Detail')

@section('header')

    <div class="row">

        <div class="col-sm-6 d-flex align-items-center gap-2">

            <a href="{{ route('admin.partners.index') }}" class="btn btn-secondary btn-sm rounded-0" title="Back">
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
                        <a href="{{ route('admin.dashboard') }}">
                            Dashboard
                        </a>
                    </li>

                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.partners.index') }}">
                            Partners
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
                <i class="bi bi-buildings me-2"></i>
                Partner Information
            </h3>

            <div class="float-end">

                <a href="{{ route('admin.partners.edit', $partner) }}" class="btn btn-warning btn-sm rounded-0">
                    <i class="bi bi-pencil me-1"></i>
                    Edit Partner
                </a>

            </div>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-3 mb-3">

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

                <div class="col-md-9">

                    <h2 class="fw-bold mb-1">
                        {{ $partner->name }}
                    </h2>

                    <div class="mb-3">

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

                    <table class="table table-borderless align-middle mb-0">

                        <tbody>

                            <tr>

                                <th width="180">
                                    Conference
                                </th>

                                <td>

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

                                </td>

                            </tr>

                            <tr>

                                <th>
                                    Website
                                </th>

                                <td>

                                    @if ($partner->website)
                                        <a href="{{ $partner->website }}" target="_blank" rel="noopener noreferrer">
                                            {{ $partner->website }}

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
                                    {{ $partner->sort_order }}
                                </td>

                            </tr>

                            <tr>

                                <th>
                                    Created At
                                </th>

                                <td>
                                    {{ $partner->created_at->format('d M Y H:i') }}
                                </td>

                            </tr>

                            <tr>

                                <th>
                                    Last Updated
                                </th>

                                <td>
                                    {{ $partner->updated_at->format('d M Y H:i') }}
                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

        <div class="card-body border-top">

            <h5 class="fw-bold mb-2">
                Description
            </h5>

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

            <a href="{{ route('admin.partners.index') }}" class="btn btn-secondary btn-sm rounded-0">
                <i class="bi bi-arrow-left me-1"></i>
                Back to Partners
            </a>

        </div>

    </div>

@endsection
