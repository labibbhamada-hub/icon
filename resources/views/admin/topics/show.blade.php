@extends('layouts.admin')

@section('title', 'Topic Detail')

@section('header')
    <div class="row">
        <div class="col-sm-6 d-flex align-items-center gap-2">
            <a href="{{ route('admin.conferences.index') }}" class="btn btn-secondary btn-sm rounded-0">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h1 class="mb-0 fs-3">
                Topic Detail
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
                        <a href="{{ route('admin.topics.index') }}">
                            Topics
                        </a>
                    </li>
                    <li class="breadcrumb-item active">Detail</li>
                </ol>
            </nav>
        </div>
    </div>
@endsection

@section('content')
    <div class="card rounded-0">
        <div class="card-header">
            <h3 class="card-title">
                Topic Information
            </h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered align-middle">
                <tbody>
                    <tr>
                        <th>
                            Conference
                        </th>
                        <td>
                            <strong>
                                {{ $topic->conference->name }}
                            </strong>
                            <br>
                            <small class="text-muted">
                                {{ $topic->conference->short_name }}
                                ({{ $topic->conference->year }})
                            </small>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Topic Name
                        </th>
                        <td>
                            <strong>
                                {{ $topic->name }}
                            </strong>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Description
                        </th>
                        <td>
                            {!! nl2br(e($topic->description ?: '-')) !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Bootstrap Icon
                        </th>
                        <td>
                            @if ($topic->icon)
                                <i class="bi {{ $topic->icon }} me-2"></i>
                                <code>{{ $topic->icon }}</code>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Color
                        </th>
                        <td>
                            <span class="badge text-bg-{{ $topic->color }} rounded-0">
                                {{ ucfirst($topic->color) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Sort Order
                        </th>
                        <td>
                            {{ $topic->sort_order }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Status
                        </th>
                        <td>
                            @if ($topic->is_active)
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
                    <tr>
                        <th>
                            Created At
                        </th>
                        <td>
                            {{ $topic->created_at->format('d M Y H:i') }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Last Updated
                        </th>
                        <td>
                            {{ $topic->updated_at->format('d M Y H:i') }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
