@extends('layouts.admin')

@section('title', 'Important Date Detail')

@section('header')

    <div class="row">

        <div class="col-sm-6 d-flex align-items-center gap-2">

            <a href="{{ route('admin.important-dates.index') }}" class="btn btn-secondary btn-sm rounded-0" title="Back">
                <i class="bi bi-arrow-left"></i>
            </a>

            <h1 class="mb-0 fs-3">
                Important Date Detail
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
                        <a href="{{ route('admin.important-dates.index') }}">
                            Important Dates
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
                <i class="bi bi-calendar-event me-2"></i>
                Important Date Information
            </h3>

            <div class="float-end">

                <a href="{{ route('admin.important-dates.edit', $importantDate) }}"
                    class="btn btn-warning btn-sm rounded-0">
                    <i class="bi bi-pencil me-1"></i>
                    Edit Important Date
                </a>

            </div>

        </div>

        <div class="card-body">

            <div class="row">

                {{-- Event Date --}}
                <div class="col-md-4 mb-3">

                    <div class="border rounded-0 bg-light text-center p-4 h-100">

                        <div class="text-muted text-uppercase small">
                            Event Date
                        </div>

                        <div class="display-6 fw-bold mt-2">
                            {{ $importantDate->date->format('d') }}
                        </div>

                        <div class="fs-5">
                            {{ $importantDate->date->format('F Y') }}
                        </div>

                        @if ($importantDate->end_date)
                            <div class="text-muted mt-2">
                                to
                                {{ $importantDate->end_date->format('d F Y') }}
                            </div>
                        @endif

                    </div>

                </div>

                {{-- Information --}}
                <div class="col-md-8">

                    <h2 class="fw-bold mb-2">
                        {{ $importantDate->title }}
                    </h2>

                    @php
                        $typeLabels = [
                            'abstract_submission' => 'Abstract Submission',
                            'full_paper_submission' => 'Full Paper Submission',
                            'registration' => 'Registration',
                            'review' => 'Review',
                            'revision' => 'Revision',
                            'camera_ready' => 'Camera Ready',
                            'conference' => 'Conference',
                            'other' => 'Other',
                        ];
                    @endphp

                    <div class="mb-3">

                        <span class="badge text-bg-primary rounded-0">
                            {{ $typeLabels[$importantDate->type] ?? 'Other' }}
                        </span>

                        @if ($importantDate->is_active)
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

                                    @if ($importantDate->conference)
                                        <strong>
                                            {{ $importantDate->conference->name }}
                                        </strong>

                                        <small class="text-muted d-block">
                                            {{ $importantDate->conference->short_name }}
                                            ({{ $importantDate->conference->year }})
                                        </small>
                                    @else
                                        -
                                    @endif

                                </td>

                            </tr>

                            <tr>

                                <th>
                                    Start Date
                                </th>

                                <td>
                                    {{ $importantDate->date->format('d F Y') }}
                                </td>

                            </tr>

                            <tr>

                                <th>
                                    End Date
                                </th>

                                <td>

                                    @if ($importantDate->end_date)
                                        {{ $importantDate->end_date->format('d F Y') }}
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
                                    {{ $importantDate->sort_order }}
                                </td>

                            </tr>

                            <tr>

                                <th>
                                    Created At
                                </th>

                                <td>
                                    {{ $importantDate->created_at->format('d M Y H:i') }}
                                </td>

                            </tr>

                            <tr>

                                <th>
                                    Last Updated
                                </th>

                                <td>
                                    {{ $importantDate->updated_at->format('d M Y H:i') }}
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

            @if ($importantDate->description)
                <div>
                    {!! nl2br(e($importantDate->description)) !!}
                </div>
            @else
                <p class="text-muted mb-0">
                    No description available.
                </p>
            @endif

        </div>

        <div class="card-footer">

            <a href="{{ route('admin.important-dates.index') }}" class="btn btn-secondary btn-sm rounded-0">
                <i class="bi bi-arrow-left me-1"></i>
                Back to Important Dates
            </a>

        </div>

    </div>

@endsection
