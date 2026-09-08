@extends('layouts.admin')

@section('title', 'User Detail')

@section('header')

    <div class="row">

        <div class="col-sm-6">

            <div class="d-flex align-items-center gap-2">

                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm rounded-0" title="Back">
                    <i class="bi bi-arrow-left"></i>
                </a>

                <h1 class="mb-0 fs-3">
                    User Detail
                </h1>

            </div>

        </div>

        <div class="col-sm-6">

            <nav aria-label="breadcrumb">

                <ol class="breadcrumb float-sm-end mb-0">

                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">
                            Dashboard
                        </a>
                    </li>

                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.users.index') }}">
                            Users
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

    @php

        $roleLabels = [
            'admin' => 'Admin',
            'reviewer' => 'Reviewer',
            'participant' => 'Participant',
        ];

        $roleClasses = [
            'admin' => 'primary',
            'reviewer' => 'warning',
            'participant' => 'secondary',
        ];

        $roleLabel = $roleLabels[$user->role] ?? ucfirst($user->role);

        $roleClass = $roleClasses[$user->role] ?? 'secondary';

    @endphp


    {{-- ============================================================
        ACCOUNT INFORMATION
    ============================================================= --}}

    <div class="card rounded-0 mb-3">

        <div class="card-header">

            <h3 class="card-title">

                <i class="bi bi-person-gear me-2"></i>

                Account Information

            </h3>

            <div class="float-end">

                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning btn-sm rounded-0">
                    <i class="bi bi-pencil me-1"></i>
                    Edit User
                </a>

            </div>

        </div>


        <div class="card-body">

            <div class="row align-items-start">

                {{-- Identity --}}
                <div class="col-lg-3 col-md-4 mb-3 mb-lg-0">

                    <div class="border rounded-0 p-3 text-center h-100">

                        <div class="rounded-circle bg-primary-subtle d-flex align-items-center justify-content-center mx-auto mb-3"
                            style="width: 80px; height: 80px;">

                            <i class="bi bi-person text-primary" style="font-size: 2.5rem;"></i>

                        </div>


                        <h5 class="fw-bold mb-1">
                            {{ $user->name }}
                        </h5>


                        <div class="text-muted small mb-3">

                            {{ $user->email }}

                        </div>


                        <div class="d-flex justify-content-center flex-wrap gap-1">

                            <span class="badge text-bg-{{ $roleClass }} rounded-0">
                                {{ $roleLabel }}
                            </span>


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


                {{-- Details --}}
                <div class="col-lg-9 col-md-8">

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <div class="text-muted small mb-1">
                                Name
                            </div>

                            <div class="fw-semibold">
                                {{ $user->name }}
                            </div>

                        </div>


                        <div class="col-md-6 mb-3">

                            <div class="text-muted small mb-1">
                                Email
                            </div>

                            <div class="fw-semibold text-break">
                                {{ $user->email }}
                            </div>

                        </div>


                        <div class="col-md-6 mb-3">

                            <div class="text-muted small mb-1">
                                Role
                            </div>

                            <span class="badge text-bg-{{ $roleClass }} rounded-0">
                                {{ $roleLabel }}
                            </span>

                        </div>


                        <div class="col-md-6 mb-3">

                            <div class="text-muted small mb-1">
                                Status
                            </div>

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


                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Created At
                            </div>

                            <div>
                                {{ $user->created_at?->format('d F Y H:i') ?? '-' }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Updated At
                            </div>

                            <div>
                                {{ $user->updated_at?->format('d F Y H:i') ?? '-' }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
        REVIEWER ASSIGNMENTS
    ============================================================= --}}

    @if ($user->role === 'reviewer' && $user->reviewers->count())

        <div class="card rounded-0">

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

                                <th width="50">
                                    No
                                </th>

                                <th>
                                    Conference
                                </th>

                                <th>
                                    Institution
                                </th>

                                <th>
                                    Status
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach ($user->reviewers as $reviewer)
                                <tr>

                                    <td>
                                        {{ $loop->iteration }}
                                    </td>


                                    <td>

                                        @if ($reviewer->conference)
                                            <strong>
                                                {{ $reviewer->conference->short_name }}
                                            </strong>

                                            <small class="text-muted d-block">
                                                {{ $reviewer->conference->year }}
                                            </small>
                                        @else
                                            <span class="text-muted">
                                                -
                                            </span>
                                        @endif

                                    </td>


                                    <td>

                                        {{ $reviewer->institution ?: '-' }}

                                    </td>


                                    <td>

                                        @if ($reviewer->is_active)
                                            <span class="badge text-bg-success rounded-0">
                                                <i class="bi bi-check-circle me-1"></i>
                                                Active
                                            </span>
                                        @else
                                            <span class="badge text-bg-secondary rounded-0">
                                                <i class="bi bi-pause-circle me-1"></i>
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
    @elseif ($user->role === 'reviewer')
        <div class="card rounded-0">

            <div class="card-header">

                <h3 class="card-title">

                    <i class="bi bi-person-check me-2"></i>

                    Reviewer Assignments

                </h3>

            </div>


            <div class="card-body text-center py-5">

                <i class="bi bi-person-x display-6 text-muted"></i>

                <h5 class="mt-2 mb-1">
                    No Reviewer Assignments
                </h5>

                <p class="text-muted mb-0">
                    This reviewer has not been assigned to any conference yet.
                </p>

            </div>

        </div>

    @endif

@endsection
