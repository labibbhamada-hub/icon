@extends('layouts.admin')

@section('title', 'Online Meetings')

@section('header')
    <div class="row">
        <div class="col-sm-6">
            <h1 class="mb-0 fs-3">
                Online Meetings Management
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
                        Online Meetings
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
                <i class="bi bi-camera-video me-2"></i>
                Online Meetings List
            </h3>

            <div class="float-end">
                <a href="{{ route('admin.conference-online-meetings.create') }}" class="btn btn-success btn-sm rounded-0">
                    <i class="bi bi-plus-circle me-1"></i>
                    Add Online Meeting
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
                                Meeting
                            </th>

                            <th>
                                Meeting ID
                            </th>

                            <th>
                                Passcode
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

                        @forelse ($meetings as $meeting)
                            <tr>

                                <td>
                                    {{ $meetings->firstItem() + $loop->index }}
                                </td>

                                <td>

                                    @if ($meeting->conference)
                                        <div class="fw-semibold">
                                            {{ $meeting->conference->short_name }}
                                        </div>

                                        <small class="text-muted d-block">
                                            {{ $meeting->conference->year }}
                                        </small>
                                    @else
                                        <span class="text-muted">
                                            -
                                        </span>
                                    @endif

                                </td>

                                <td>

                                    <div class="fw-semibold">
                                        {{ $meeting->title }}
                                    </div>

                                    @if ($meeting->meeting_url)
                                        <small class="text-muted d-block text-truncate" style="max-width: 300px;">
                                            {{ $meeting->meeting_url }}
                                        </small>
                                    @endif

                                </td>

                                <td>
                                    @if ($meeting->meeting_id)
                                        <code>
                                            {{ $meeting->meeting_id }}
                                        </code>
                                    @else
                                        <span class="text-muted">
                                            -
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    @if ($meeting->passcode)
                                        <code>
                                            {{ $meeting->passcode }}
                                        </code>
                                    @else
                                        <span class="text-muted">
                                            -
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    @if ($meeting->is_active)
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

                                        <a href="{{ route('admin.conference-online-meetings.show', $meeting) }}"
                                            class="btn btn-info btn-sm rounded-0" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <a href="{{ route('admin.conference-online-meetings.edit', $meeting) }}"
                                            class="btn btn-warning btn-sm rounded-0" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <form action="{{ route('admin.conference-online-meetings.destroy', $meeting) }}"
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

                                <td colspan="7" class="text-center py-5">

                                    <div class="mb-2">
                                        <i class="bi bi-camera-video display-5 text-muted"></i>
                                    </div>

                                    <h5 class="mb-1">
                                        No Online Meetings Found
                                    </h5>

                                    <p class="text-muted mb-3">
                                        There are no online meeting data yet.
                                    </p>

                                    <a href="{{ route('admin.conference-online-meetings.create') }}"
                                        class="btn btn-success rounded-0">
                                        <i class="bi bi-plus-circle me-1"></i>
                                        Create First Online Meeting
                                    </a>

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

        @if ($meetings->hasPages())
            <div class="card-footer">
                {{ $meetings->links() }}
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
                    title: 'Delete Online Meeting?',
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
