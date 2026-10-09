@extends('layouts.admin')

@section('title', 'Topic Detail')

@section('header')
    <div class="row">
        <div class="col-sm-6">
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('admin.topics.index') }}" class="btn btn-secondary rounded-0" title="Back">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h1 class="mb-0 fs-3">
                    Topic Detail
                </h1>
            </div>
            <p class="text-muted mb-0">
                View the details and information of this conference topic.
            </p>
        </div>
    </div>
@endsection

@section('content')
    <div class="card rounded-0 overflow-hidden">
        <div class="card-header rounded-0">
            <h3 class="card-title">
                <i class="bi bi-info-circle me-2"></i>
                Topic Information
            </h3>
        </div>
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-md-4">
                    <strong>Conference</strong>
                </div>
                <div class="col-md-8">
                    <strong>
                        {{ $topic->conference->name }}
                    </strong>
                    <small class="text-muted d-block">
                        {{ $topic->conference->short_name }}
                        ({{ $topic->conference->year }})
                    </small>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-4">
                    <strong>Topic Name</strong>
                </div>
                <div class="col-md-8">
                    {{ $topic->name }}
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-4">
                    <strong>Description</strong>
                </div>
                <div class="col-md-8">
                    {!! nl2br(e($topic->description ?: '-')) !!}
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-4">
                    <strong>Bootstrap Icon</strong>
                </div>
                <div class="col-md-8">
                    @if ($topic->icon)
                        <i class="bi {{ $topic->icon }} me-2"></i>
                        <code>
                            {{ $topic->icon }}
                        </code>
                    @else
                        -
                    @endif
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-4">
                    <strong>Color</strong>
                </div>
                <div class="col-md-8">
                    <span class="badge text-bg-{{ $topic->color }} rounded-0">
                        {{ ucfirst($topic->color) }}
                    </span>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-4">
                    <strong>Sort Order</strong>
                </div>
                <div class="col-md-8">
                    {{ $topic->sort_order }}
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-4">
                    <strong>Status</strong>
                </div>
                <div class="col-md-8">
                    @if ($topic->is_active)
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
                    <strong>Created At</strong>
                </div>
                <div class="col-md-8">
                    {{ $topic->created_at->format('d M Y H:i') }}
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-4">
                    <strong>Last Updated</strong>
                </div>
                <div class="col-md-8">
                    {{ $topic->updated_at->format('d M Y H:i') }}
                </div>
            </div>
        </div>
        <div class="card-footer">
            <a href="{{ route('admin.topics.edit', $topic) }}" class="btn btn-warning rounded-0">
                <i class="bi bi-pencil me-1"></i>
                Edit Topic
            </a>
        </div>
    </div>
@endsection
