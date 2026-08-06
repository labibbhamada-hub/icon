@extends('layouts.admin')

@section('title', 'Conference Detail')

@section('content')
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">
                        Conference Detail
                    </h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">
                                Dashboard
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.conferences.index') }}">
                                Conference
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            Detail
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="app-content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ $conference->name }}
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.conferences.index') }}" class="btn btn-secondary btn-sm">
                            <i class="bi bi-arrow-left"></i>
                            Back
                        </a>
                        <a href="{{ route('admin.conferences.edit', $conference) }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil"></i>
                            Edit
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            @if ($conference->logo)
                                <img src="{{ asset('storage/' . $conference->logo) }}" class="img-fluid rounded border">
                            @else
                                <div class="text-center p-5 border rounded">
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
                                    <th width="220">
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
                    @if ($conference->banner)
                        <hr>
                        <h5>
                            Conference Banner
                        </h5>
                        <img src="{{ asset('storage/' . $conference->banner) }}" class="img-fluid rounded border">
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
