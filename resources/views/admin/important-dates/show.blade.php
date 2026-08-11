@extends('layouts.admin')

@section('title', 'Important Date Detail')

@section('header')
    <div class="row">
        <div class="col-sm-6 d-flex align-items-center gap-2">
            <a href="{{ route('admin.important-dates.index') }}" class="btn btn-secondary btn-sm rounded-0">
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
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.important-dates.index') }}">Important Date</a>
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
                Important Date Information
            </h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="border rounded-0 bg-light text-center p-4">
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
                <div class="col-md-8">
                    <h2 class="fw-bold mb-2">
                        {{ $importantDate->title }}
                    </h2>
                    <div class="mb-2">
                        @php
                            $typeLabels = [
                                'abstract_submission' => 'Abstract Submission',
                                'full_paper_submission' => 'Full Paper Submission',
                                'registration' => 'Registration',
                                'conference' => 'Conference',
                                'camera_ready' => 'Camera Ready',
                                'other' => 'Other',
                            ];
                        @endphp
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

                    <hr>

                    <div class="row mb-2">
                        <div class="col-md-4">
                            <strong>
                                Conference
                            </strong>
                        </div>
                        <div class="col-md-8">
                            @if ($importantDate->conference)
                                <strong>
                                    {{ $importantDate->conference->name }}
                                </strong>
                                <small class="text-muted d-block">
                                    {{ $importantDate->conference->short_name }}
                                    ({{ $importantDate->conference->year }})
                                </small>
                            @else
                                —
                            @endif
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <strong>
                                Start Date
                            </strong>
                        </div>
                        <div class="col-md-8">
                            {{ $importantDate->date->format('d F Y') }}
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <strong>
                                End Date
                            </strong>
                        </div>
                        <div class="col-md-8">
                            @if ($importantDate->end_date)
                                {{ $importantDate->end_date->format('d F Y') }}
                            @else
                                —
                            @endif
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <strong>
                                Sort Order
                            </strong>
                        </div>
                        <div class="col-md-8">
                            {{ $importantDate->sort_order }}
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <strong>
                                Created At
                            </strong>
                        </div>
                        <div class="col-md-8">
                            {{ $importantDate->created_at->format('d M Y H:i') }}
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <strong>
                                Last Updated
                            </strong>
                        </div>
                        <div class="col-md-8">
                            {{ $importantDate->updated_at->format('d M Y H:i') }}
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
                    @if ($importantDate->description)
                        <div>
                            {!! nl2br(e($importantDate->description)) !!}
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
