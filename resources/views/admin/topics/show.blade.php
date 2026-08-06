@extends('layouts.admin')

@section('title', 'Topic Detail')

@section('content')
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">
                        Topic Detail
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
                            <a href="{{ route('admin.topics.index') }}">
                                Topics
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
                        Topic Information
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.topics.index') }}" class="btn btn-secondary btn-sm">
                            <i class="bi bi-arrow-left"></i>
                            Back
                        </a>
                        <a href="{{ route('admin.topics.edit', $topic) }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil-square"></i>
                            Edit
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered align-middle">
                        <tbody>
                            <tr>
                                <th width="250">
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
                                    @if ($topic->icon)
                                        <i class="bi {{ $topic->icon }} me-2"></i>
                                    @endif
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
                                    <span class="badge text-bg-{{ $topic->color }}">
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
                                        <span class="badge text-bg-success">
                                            Active
                                        </span>
                                    @else
                                        <span class="badge text-bg-secondary">
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
        </div>
    </div>
@endsection