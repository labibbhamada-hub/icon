@extends('layouts.admin')

@section('title', 'User Detail')

@section('header')
    <div class="row">
        <div class="col-sm-6 d-flex align-items-center gap-2">
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm rounded-0">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h1 class="mb-0 fs-3">
                User Detail
            </h1>
        </div>
        <div class="col-sm-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.users.index') }}">Users</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Detail</li>
                </ol>
            </nav>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-4 mb-3">
            <div class="card rounded-0">
                <div class="card-body text-center p-4">
                    <div class="rounded-circle bg-primary-subtle d-flex align-items-center justify-content-center mx-auto mb-2"
                        style="width:100px; height:100px;">
                        <i class="bi bi-person display-4 text-primary"></i>
                    </div>
                    <div class="mb-2">
                        <h4 class="fw-bold">{{ $user->name }}</h4>
                        <p class="text-muted">{{ $user->email }}</p>
                    </div>
                    @if ($user->role === 'admin')
                        <span class="badge text-bg-primary rounded-0">
                            Admin
                        </span>
                    @elseif ($user->role === 'reviewer')
                        <span class="badge text-bg-warning rounded-0">
                            Reviewer
                        </span>
                    @else
                        <span class="badge text-bg-secondary rounded-0">
                            Participant
                        </span>
                    @endif
                    @if ($user->status === 'active')
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
        </div>
        <div class="col-lg-8">
            <div class="card rounded-0 mb-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-person-gear me-2"></i>
                        Account Information
                    </h3>
                    <div class="float-end">
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning btn-sm rounded-0">
                            <i class="bi bi-pencil"></i>
                            Edit
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <strong>
                                Name
                            </strong>
                        </div>
                        <div class="col-md-8">
                            {{ $user->name }}
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <strong>
                                Email
                            </strong>
                        </div>
                        <div class="col-md-8">
                            {{ $user->email }}
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <strong>
                                Role
                            </strong>
                        </div>
                        <div class="col-md-8">
                            @if ($user->role === 'admin')
                                <span class="badge text-bg-primary rounded-0">
                                    Admin
                                </span>
                            @elseif ($user->role === 'reviewer')
                                <span class="badge text-bg-warning rounded-0">
                                    Reviewer
                                </span>
                            @else
                                <span class="badge text-bg-secondary rounded-0">
                                    Participant
                                </span>
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
                            @if ($user->status === 'active')
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
                                Created At
                            </strong>
                        </div>
                        <div class="col-md-8">
                            {{ $user->created_at->format('d M Y H:i') }}
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <strong>
                                Updated At
                            </strong>
                        </div>
                        <div class="col-md-8">
                            {{ $user->updated_at->format('d M Y H:i') }}
                        </div>
                    </div>
                </div>
            </div>
            @if ($user->role === 'reviewer' && $user->reviewers->count())
                <div class="card rounded-0 mb-3">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="bi bi-person-check me-2"></i>
                            Reviewer Assignments
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Conference</th>
                                        <th>Institution</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($user->reviewers as $reviewer)
                                        <tr>
                                            <td class="align-top">
                                                @if ($reviewer->conference)
                                                    <strong>
                                                        {{ $reviewer->conference->short_name }}
                                                    </strong>
                                                    <small class="text-muted d-block">
                                                        {{ $reviewer->conference->year }}
                                                    </small>
                                                @else
                                                    —
                                                @endif
                                            </td>
                                            <td class="align-top">
                                                {{ $reviewer->institution ?: '—' }}
                                            </td>
                                            <td class="align-top">
                                                @if ($reviewer->is_active)
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
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
