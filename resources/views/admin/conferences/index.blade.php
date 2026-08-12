@extends('layouts.admin')

@section('title', 'Conferences Management')

@section('header')
    <div class="row">
        <div class="col-sm-6">
            <h1 class="mb-0 fs-3">Conferences Management</h1>
        </div>
        <div class="col-sm-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Conferences</li>
                </ol>
            </nav>
        </div>
    </div>
@endsection

@section('content')
    <div class="card rounded-0">
        <div class="card-header">
            <h3 class="card-title">
                Conferences List
            </h3>
            <div class="float-end">
                <a href="{{ route('admin.conferences.create') }}" class="btn btn-success btn-sm rounded-0">
                    <i class="bi bi-plus-circle"></i>
                    Add Conferences
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th width="60">No</th>
                            <th>Conferences</th>
                            <th>Year</th>
                            <th>Status</th>
                            <th width="120">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($conferences->count())
                            @foreach ($conferences as $conference)
                                <tr>
                                    <td class="align-top">
                                        {{ $loop->iteration }}
                                    </td>
                                    <td class="align-top">
                                        <div class="fw-semibold">
                                            {{ $conference->name }}
                                        </div>
                                        <small class="text-muted">
                                            {{ $conference->short_name }}
                                        </small>
                                    </td>
                                    <td class="align-top">
                                        {{ $conference->year }}
                                    </td>
                                    <td class="align-top">
                                        <x-admin.status-badge :status="$conference->status" />
                                    </td>
                                    <td class="align-top">
                                        <div class="btn-group gap-1">
                                            <a href="{{ route('admin.conferences.show', $conference) }}"
                                                class="btn btn-info btn-sm rounded-0">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.conferences.edit', $conference) }}"
                                                class="btn btn-warning btn-sm rounded-0">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.conferences.destroy', $conference) }}"
                                                method="POST" class="d-inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm rounded-0">
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
                                        <div class="mb-2">
                                            <i class="bi bi-calendar-event display-4 text-secondary"></i>
                                        </div>
                                        <div class="mb-2">
                                            <h5>No Conferences Found</h5>
                                            <p class="text-muted">There is no conferences data yet.</p>
                                        </div>
                                        <a href="{{ route('admin.conferences.create') }}" class="btn btn-success rounded-0">
                                            <i class="bi bi-plus-circle me-1"></i>
                                            Create First Conferences
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        @if ($conferences->hasPages())
            <div class="card-footer clearfix">
                {{ $conferences->links() }}
            </div>
        @endif
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
