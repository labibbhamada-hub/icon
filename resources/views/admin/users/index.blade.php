@extends('layouts.admin')

@section('title', 'Users Management')

@section('header')

    <div class="row">

        <div class="col-sm-6">

            <h1 class="mb-0 fs-3">
                Users Management
            </h1>

        </div>

        <div class="col-sm-6">

            <nav aria-label="breadcrumb">

                <ol class="breadcrumb float-sm-end mb-0">

                    <li class="breadcrumb-item">

                        <a href="{{ route('admin.dashboard') }}">
                            Dashboard
                        </a>

                    </li>

                    <li class="breadcrumb-item active" aria-current="page">
                        Users
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

                <i class="bi bi-people me-2"></i>
                Users List

            </h3>

            <div class="float-end">

                <a href="{{ route('admin.users.create') }}" class="btn btn-success btn-sm rounded-0">
                    <i class="bi bi-person-plus me-1"></i>
                    Add User
                </a>

            </div>

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
                                User
                            </th>

                            <th width="130">
                                Role
                            </th>

                            <th width="120">
                                Status
                            </th>

                            <th width="130">
                                Created
                            </th>

                            <th width="120">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse ($users as $user)
                            <tr>

                                {{-- No --}}
                                <td class="align-top">

                                    {{ $users->firstItem() + $loop->index }}

                                </td>


                                {{-- User --}}
                                <td class="align-top">

                                    <div class="d-flex align-items-center">

                                        <div class="rounded-circle bg-secondary-subtle d-flex align-items-center justify-content-center me-3 flex-shrink-0"
                                            style="width: 42px; height: 42px;">

                                            <i class="bi bi-person text-secondary"></i>

                                        </div>


                                        <div>

                                            <div class="fw-semibold">

                                                {{ $user->name }}

                                            </div>

                                            <small class="text-muted">

                                                {{ $user->email }}

                                            </small>

                                        </div>

                                    </div>

                                </td>


                                {{-- Role --}}
                                <td class="align-top">

                                    @if ($user->role === 'admin')
                                        <span class="badge text-bg-primary rounded-0">
                                            <i class="bi bi-shield-check me-1"></i>
                                            Admin
                                        </span>
                                    @elseif ($user->role === 'reviewer')
                                        <span class="badge text-bg-warning rounded-0">
                                            <i class="bi bi-person-check me-1"></i>
                                            Reviewer
                                        </span>
                                    @elseif ($user->role === 'participant')
                                        <span class="badge text-bg-secondary rounded-0">
                                            <i class="bi bi-person me-1"></i>
                                            Participant
                                        </span>
                                    @else
                                        <span class="badge text-bg-secondary rounded-0">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    @endif
                                </td>


                                {{-- Status --}}
                                <td class="align-top">

                                    @if ($user->status === 'active')
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


                                {{-- Created --}}
                                <td class="align-top">

                                    <div>
                                        {{ $user->created_at->format('d M Y') }}
                                    </div>

                                    <small class="text-muted">
                                        {{ $user->created_at->format('H:i') }}
                                    </small>

                                </td>


                                {{-- Actions --}}
                                <td class="align-top">

                                    <div class="btn-group gap-1">

                                        <a href="{{ route('admin.users.show', $user) }}"
                                            class="btn btn-info btn-sm rounded-0" title="View User">
                                            <i class="bi bi-eye"></i>
                                        </a>


                                        <a href="{{ route('admin.users.edit', $user) }}"
                                            class="btn btn-warning btn-sm rounded-0" title="Edit User">
                                            <i class="bi bi-pencil"></i>
                                        </a>


                                        @if ($user->id !== auth()->id())
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                                class="d-inline delete-user-form">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="btn btn-danger btn-sm rounded-0"
                                                    title="Delete User">
                                                    <i class="bi bi-trash"></i>
                                                </button>

                                            </form>
                                        @else
                                            <button type="button" class="btn btn-secondary btn-sm rounded-0" disabled
                                                title="You cannot delete your own account">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        @endif

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="6" class="text-center py-5">

                                    <div class="mb-2">

                                        <i class="bi bi-people display-5 text-muted"></i>

                                    </div>

                                    <h5 class="mb-1">
                                        No Users Found
                                    </h5>

                                    <p class="text-muted mb-3">
                                        There are no users available yet.
                                    </p>

                                    <a href="{{ route('admin.users.create') }}" class="btn btn-success rounded-0">
                                        <i class="bi bi-person-plus me-1"></i>
                                        Add User
                                    </a>

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        @if ($users->hasPages())
            <div class="card-footer">

                {{ $users->links() }}

            </div>
        @endif

    </div>
    
@endsection


@push('scripts')
    <script>
        document
            .querySelectorAll('.delete-user-form')
            .forEach(function(form) {

                form.addEventListener(
                    'submit',
                    function(event) {

                        event.preventDefault();

                        Swal.fire({

                            title: 'Delete User?',

                            text: 'This action cannot be undone. Users assigned as reviewers cannot be deleted.',

                            icon: 'warning',

                            showCancelButton: true,

                            confirmButtonText: 'Yes, delete it',

                            cancelButtonText: 'Cancel',

                            confirmButtonColor: '#dc3545',

                            cancelButtonColor: '#6c757d',

                        }).then(function(result) {

                            if (result.isConfirmed) {

                                form.submit();

                            }

                        });

                    }
                );

            });
    </script>
@endpush
