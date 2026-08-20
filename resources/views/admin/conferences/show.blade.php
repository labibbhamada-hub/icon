@extends('layouts.admin')

@section('title', 'Conference Detail')

@section('header')
    <div class="row">
        <div class="col-sm-6 d-flex align-items-center gap-2">
            <a href="{{ route('admin.conferences.index') }}" class="btn btn-secondary btn-sm rounded-0">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h1 class="mb-0 fs-3">
                Conference Detail
            </h1>
        </div>
        <div class="col-sm-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.conferences.index') }}">Conference</a>
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
                {{ $conference->name }}
            </h3>
            <div class="float-end">
                <a href="{{ route('admin.conferences.settings.edit', $conference) }}"
                    class="btn btn-secondary btn-sm rounded-0">
                    <i class="bi bi-toggles me-1"></i>
                    Settings
                </a>
                <a href="{{ route('admin.conferences.configuration.edit', $conference) }}"
                    class="btn btn-primary btn-sm rounded-0">
                    <i class="bi bi-sliders me-1"></i>
                    Configuration
                </a>
                <a href="{{ route('admin.conferences.edit', $conference) }}" class="btn btn-warning btn-sm rounded-0">
                    <i class="bi bi-pencil me-1"></i>
                    Edit
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    @if ($conference->logo)
                        <img src="{{ asset('storage/' . $conference->logo) }}" class="img-fluid rounded-0 border">
                    @else
                        <div class="text-center p-5 border rounded-0">
                            <i class="bi bi-image display-4 text-secondary"></i>
                            <p class="mt-2">
                                No Logo
                            </p>
                        </div>
                    @endif
                </div>
                <div class="col-md-9">
                    <table class="table table-bordered">
                        <tr>
                            <th>
                                Conference Name
                            </th>
                            <td>
                                {{ $conference->name }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Short Name
                            </th>
                            <td>
                                {{ $conference->short_name }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Year
                            </th>
                            <td>
                                {{ $conference->year }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Theme
                            </th>
                            <td>
                                {{ $conference->theme ?: '-' }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Venue
                            </th>
                            <td>
                                {{ $conference->venue ?: '-' }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                City
                            </th>
                            <td>
                                {{ $conference->city ?: '-' }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Country
                            </th>
                            <td>
                                {{ $conference->country }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Start Date
                            </th>
                            <td>
                                {{ optional($conference->start_date)->format('d F Y') }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                End Date
                            </th>
                            <td>
                                {{ optional($conference->end_date)->format('d F Y') }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Abstract Deadline
                            </th>
                            <td>
                                {{ optional($conference->abstract_deadline)->format('d F Y') ?? '-' }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Full Paper Deadline
                            </th>
                            <td>
                                {{ optional($conference->fullpaper_deadline)->format('d F Y') ?? '-' }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Registration Deadline
                            </th>
                            <td>
                                {{ optional($conference->registration_deadline)->format('d F Y') ?? '-' }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Status
                            </th>
                            <td>
                                <x-admin.status-badge :status="$conference->status" />
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="card-body border-top">
            @if ($conference->banner)
                <h5>
                    Conference Banner
                </h5>
                <img src="{{ asset('storage/' . $conference->banner) }}" class="img-fluid rounded-0 border">
            @endif
        </div>
    </div>
@endsection
