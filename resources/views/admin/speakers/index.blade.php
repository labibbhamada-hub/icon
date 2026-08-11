@extends('layouts.admin')

@section('title', 'Speakers')

@section('header')
    <div class="row">
        <div class="col-sm-6">
            <h1 class="mb-0 fs-3">Speakers Management</h1>
        </div>
        <div class="col-sm-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Speakers</li>
                </ol>
            </nav>
        </div>
    </div>
@endsection

@section('content')
    <div class="card rounded-0">
        <div class="card-header">
            <h3 class="card-title">
                Speakers List
            </h3>
            <div class="float-end">
                <a href="{{ route('admin.speakers.create') }}" class="btn btn-success btn-sm rounded-0">
                    <i class="bi bi-plus-circle me-1"></i>
                    Add Speaker
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th width="60">No</th>
                            <th width="100">Photo</th>
                            <th>Speaker</th>
                            <th>Institution</th>
                            <th>Conference</th>
                            <th width="100">Status</th>
                            <th width="160">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($speakers as $speaker)
                            <tr>
                                <td>
                                    {{ $speakers->firstItem() + $loop->index }}
                                </td>
                                <td>
                                    @if ($speaker->photo)
                                        <img src="{{ asset('storage/' . $speaker->photo) }}" alt="{{ $speaker->name }}"
                                            class="rounded-circle object-fit-cover" width="50" height="50">
                                    @else
                                        <div class="rounded-circle bg-secondary-subtle d-flex align-items-center justify-content-center"
                                            style="width: 50px; height: 50px;">
                                            <i class="bi bi-person fs-4 text-secondary"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-semibold">
                                        {{ $speaker->name }}
                                    </div>
                                    @if ($speaker->title)
                                        <small class="text-muted">
                                            {{ $speaker->title }}
                                        </small>
                                    @endif
                                    @if ($speaker->position)
                                        <small class="text-muted d-block">
                                            {{ $speaker->position }}
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    @if ($speaker->institution)
                                        {{ $speaker->institution }}
                                    @else
                                        <span class="text-muted">
                                            —
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if ($speaker->conference)
                                        <span class="fw-semibold">
                                            {{ $speaker->conference->short_name }}
                                        </span>
                                        <small class="text-muted d-block">
                                            {{ $speaker->conference->year }}
                                        </small>
                                    @else
                                        <span class="text-muted">
                                            —
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if ($speaker->is_active)
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
                                    <a href="{{ route('admin.speakers.show', $speaker) }}"
                                        class="btn btn-info btn-sm rounded-0" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.speakers.edit', $speaker) }}"
                                        class="btn btn-warning btn-sm rounded-0" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.speakers.destroy', $speaker) }}" method="POST"
                                        class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm rounded-0" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="bi bi-mic display-5 text-muted"></i>
                                    <h5 class="mt-3">
                                        No Speakers Available
                                    </h5>
                                    <p class="text-muted mb-3">
                                        There are no speakers data yet.
                                    </p>
                                    <a href="{{ route('admin.speakers.create') }}"
                                        class="btn btn-success rounded-0">
                                        <i class="bi bi-plus-circle me-1"></i>
                                        Create First Speakers
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($speakers->hasPages())
            <div class="card-footer">
                {{ $speakers->links() }}
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
                    title: 'Delete Speaker?',
                    text: 'This action cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d'
                }).then(function(result) {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
