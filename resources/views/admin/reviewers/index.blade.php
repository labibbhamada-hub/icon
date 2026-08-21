@extends('layouts.admin')

@section('title', 'Reviewers Management')

@section('header')
    <div class="row">
        <div class="col-sm-6">
            <h1 class="mb-0 fs-3">Reviewers Management</h1>
        </div>
        <div class="col-sm-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Reviewers</li>
                </ol>
            </nav>
        </div>
    </div>
@endsection

@section('content')
    <div class="card rounded-0">
        <div class="card-header">
            <h3 class="card-title">
                Reviewers List
            </h3>
            <div class="float-end">
                <a href="{{ route('admin.reviewers.create') }}" class="btn btn-success btn-sm rounded-0">
                    <i class="bi bi-person-plus"></i>
                    Add Reviewer
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th width="40">No</th>
                            <th>Reviewer</th>
                            <th>Conference</th>
                            <th>Institution</th>
                            <th>Expertise</th>
                            <th>Status</th>
                            <th width="120">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reviewers as $reviewer)
                            <tr>
                                <td class="align-top">
                                    {{ $reviewers->firstItem() + $loop->index }}
                                </td>
                                <td class="align-top">
                                    <div class="fw-semibold">
                                        {{ $reviewer->user->name }}
                                    </div>
                                    <small class="text-muted">
                                        {{ $reviewer->user->email }}
                                    </small>
                                </td>
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
                                    @if ($reviewer->expertise)
                                        <small>
                                            {{ \Illuminate\Support\Str::limit($reviewer->expertise, 80) }}
                                        </small>
                                    @else
                                        <span class="text-muted">
                                            —
                                        </span>
                                    @endif
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
                                <td class="align-top">
                                    <div class="btn-group gap-1">
                                        <a href="{{ route('admin.reviewers.show', $reviewer) }}"
                                            class="btn btn-info btn-sm rounded-0" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.reviewers.edit', $reviewer) }}"
                                            class="btn btn-warning btn-sm rounded-0" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.reviewers.destroy', $reviewer) }}" method="POST"
                                            class="d-inline delete-form">
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
                                        <i class="bi bi-person-check display-5 text-muted"></i>
                                    </div>
                                    <div class="mb-2">
                                        <h5>No Reviewers Found</h5>
                                        <p class="text-muted">There are no reviewers registered yet.</p>
                                    </div>
                                    <a href="{{ route('admin.reviewers.create') }}"
                                        class="btn btn-success rounded-0">
                                        <i class="bi bi-person-plus me-1"></i>
                                        Add Reviewer
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($reviewers->hasPages())
            <div class="card-footer">
                {{ $reviewers->links() }}
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
                    title: 'Delete Reviewer?',
                    text: 'This action will remove the reviewer from this conference.',
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
