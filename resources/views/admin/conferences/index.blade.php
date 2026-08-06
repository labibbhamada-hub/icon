@extends('layouts.admin')

@section('title', 'Conference Management')

@section('content')
    <div class="app-content-header">
        <div class="container-fluid">
            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold mb-1">
                        Conference Management
                    </h3>
                    <p class="text-muted mb-0">
                        Manage conference information for ICON CMS.
                    </p>
                </div>
                <a href="{{ route('admin.conferences.create') }}" class="btn btn-success">
                    <i class="bi bi-plus-circle me-2"></i>
                    Add Conference
                </a>
            </div>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        Conference List
                    </h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th width="60">No</th>
                                    <th>Conference</th>
                                    <th>Year</th>
                                    <th>Status</th>
                                    <th width="180">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($conferences->count())
                                    @foreach ($conferences as $conference)
                                        <tr>
                                            <td>
                                                {{ $loop->iteration }}
                                            </td>
                                            <td>
                                                <div class="fw-semibold">
                                                    {{ $conference->name }}
                                                </div>
                                                <small class="text-muted">
                                                    {{ $conference->short_name }}
                                                </small>
                                            </td>
                                            <td>
                                                {{ $conference->year }}
                                            </td>
                                            <td>
                                                <x-admin.status-badge :status="$conference->status" />
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('admin.conferences.show', $conference) }}"
                                                        class="btn btn-sm btn-info">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.conferences.edit', $conference) }}"
                                                        class="btn btn-sm btn-warning">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <form action="{{ route('admin.conferences.destroy', $conference) }}"
                                                        method="POST" class="d-inline delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5">
                                            <div class="text-center py-5">
                                                <i class="bi bi-calendar-event display-4 text-secondary"></i>
                                                <h5 class="mt-3">
                                                    No Conference Found
                                                </h5>
                                                <p class="text-muted mb-3">
                                                    There is no conference data yet.
                                                </p>
                                                <a href="{{ route('admin.conferences.create') }}" class="btn btn-success">
                                                    <i class="bi bi-plus-circle me-2"></i>
                                                    Create First Conference
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                        @if ($conferences->hasPages())
                            <div class="card-footer clearfix">
                                {{ $conferences->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Delete Conference?',
                    text: 'This action cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Delete',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
