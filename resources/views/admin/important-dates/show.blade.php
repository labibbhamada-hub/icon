@extends('layouts.admin')

@section('title', 'Important Date Detail')

@section('header')
    <div class="row">
        <div class="col-sm-6">
            <div class="d-flex gap-2">
                <a href="{{ route('admin.important-dates.index') }}" class="btn btn-secondary rounded-0">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h1 class="mb-0 fs-3">
                    Important Date Detail
                </h1>
            </div>
            <p class="text-muted mb-0">
                View the details and information of this important conference date.
            </p>
        </div>
    </div>
@endsection

@section('content')
    <div class="card rounded-0 overflow-hidden">
        <div class="card-header rounded-0">
            <h3 class="card-title">
                <i class="bi bi-calendar-event me-2"></i>
                Important Date Information
            </h3>
        </div>
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-md-4">
                    <strong>
                        Event / Date Title
                    </strong>
                </div>
                <div class="col-md-8">
                    {{ $importantDate->title }}
                </div>
            </div>
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
                        -
                    @endif
                </div>
            </div>
            @php
                $typeLabels = [
                    'abstract_submission' => 'Abstract Submission',
                    'full_paper_submission' => 'Full Paper Submission',
                    'registration' => 'Registration',
                    'review' => 'Review',
                    'revision' => 'Revision',
                    'camera_ready' => 'Camera Ready',
                    'publication' => 'Publication',
                    'conference' => 'Conference',
                    'other' => 'Other',
                ];
            @endphp
            <div class="row mb-2">
                <div class="col-md-4">
                    <strong>
                        Type
                    </strong>
                </div>
                <div class="col-md-8">
                    {{ $typeLabels[$importantDate->type] ?? 'Other' }}
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-4">
                    <strong>
                        Description
                    </strong>
                </div>
                <div class="col-md-8">
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
                        -
                    @endif
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-4">
                    <strong>
                        Status
                    </strong>
                </div>
                <div class="col-md-8">
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
        <div class="card-footer">
            <a href="{{ route('admin.important-dates.edit', $importantDate) }}" class="btn btn-warning rounded-0">
                <i class="bi bi-pencil me-1"></i>
                Edit Important Date
            </a>
        </div>
    </div>
@endsection
