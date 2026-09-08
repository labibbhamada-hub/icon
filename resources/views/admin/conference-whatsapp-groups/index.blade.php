@extends('layouts.admin')

@section('title', 'WhatsApp Groups')

@section('header')
    <div class="row">
        <div class="col-sm-6">
            <h1 class="mb-0 fs-3">
                WhatsApp Groups Management
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

                    <li class="breadcrumb-item active" aria-current="page">
                        WhatsApp Groups
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
                <i class="bi bi-whatsapp me-2"></i>
                WhatsApp Groups List
            </h3>

            <div class="float-end">
                <a href="{{ route('admin.conference-whatsapp-groups.create') }}" class="btn btn-success btn-sm rounded-0">
                    <i class="bi bi-plus-circle me-1"></i>
                    Add WhatsApp Group
                </a>
            </div>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead>
                        <tr>
                            <th width="60">
                                No
                            </th>

                            <th>
                                Conference
                            </th>

                            <th>
                                Group
                            </th>

                            <th>
                                Group URL
                            </th>

                            <th>
                                Status
                            </th>

                            <th width="120">
                                Action
                            </th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse ($groups as $group)
                            <tr>

                                <td>
                                    {{ $groups->firstItem() + $loop->index }}
                                </td>

                                <td>

                                    @if ($group->conference)
                                        <div class="fw-semibold">
                                            {{ $group->conference->short_name }}
                                        </div>

                                        <small class="text-muted d-block">
                                            {{ $group->conference->year }}
                                        </small>
                                    @else
                                        <span class="text-muted">
                                            -
                                        </span>
                                    @endif

                                </td>

                                <td>

                                    <div class="fw-semibold">
                                        {{ $group->title }}
                                    </div>

                                    @if ($group->description)
                                        <small class="text-muted d-block text-truncate" style="max-width: 300px;">
                                            {{ $group->description }}
                                        </small>
                                    @endif

                                </td>

                                <td>

                                    @if ($group->group_url)
                                        <a href="{{ $group->group_url }}" target="_blank" rel="noopener noreferrer"
                                            class="text-decoration-none">
                                            <i class="bi bi-box-arrow-up-right me-1"></i>
                                            Open Group
                                        </a>
                                    @else
                                        <span class="text-muted">
                                            -
                                        </span>
                                    @endif

                                </td>

                                <td>
                                    @if ($group->is_active)
                                        <span class="badge text-bg-success rounded-0">
                                            Active
                                        </span>
                                    @else
                                        <span class="badge text-bg-secondary rounded-0">
                                            Inactive
                                        </span>
                                    @endif
                                </td>

                                <td>

                                    <div class="btn-group gap-1">

                                        <a href="{{ route('admin.conference-whatsapp-groups.show', $group) }}"
                                            class="btn btn-info btn-sm rounded-0" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <a href="{{ route('admin.conference-whatsapp-groups.edit', $group) }}"
                                            class="btn btn-warning btn-sm rounded-0" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <form action="{{ route('admin.conference-whatsapp-groups.destroy', $group) }}"
                                            method="POST" class="d-inline delete-form">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-danger btn-sm rounded-0" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="6" class="text-center py-5">

                                    <div class="mb-2">
                                        <i class="bi bi-whatsapp display-5 text-muted"></i>
                                    </div>

                                    <h5 class="mb-1">
                                        No WhatsApp Groups Found
                                    </h5>

                                    <p class="text-muted mb-3">
                                        There are no WhatsApp group data yet.
                                    </p>

                                    <a href="{{ route('admin.conference-whatsapp-groups.create') }}"
                                        class="btn btn-success rounded-0">
                                        <i class="bi bi-plus-circle me-1"></i>
                                        Create First WhatsApp Group
                                    </a>

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

        @if ($groups->hasPages())
            <div class="card-footer">
                {{ $groups->links() }}
            </div>
        @endif

    </div>

@endsection

@push('scripts')
    <script>
        document.querySelectorAll('.delete-form').forEach(function(form) {

            form.addEventListener('submit', function(event) {

                event.preventDefault();

                Swal.fire({
                    title: 'Delete WhatsApp Group?',
                    text: 'This action cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it',
                    cancelButtonText: 'Cancel',
                    cancelButtonColor: '#6c757d',
                    confirmButtonColor: '#dc3545',
                }).then(function(result) {

                    if (result.isConfirmed) {
                        form.submit();
                    }

                });

            });

        });
    </script>
@endpush
