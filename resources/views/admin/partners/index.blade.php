@extends('layouts.admin')

@section('title', 'Partners Management')

@section('header')
    <div class="row">
        <div class="col-sm-6">
            <h1 class="mb-0 fs-3">Partners Management</h1>
        </div>
        <div class="col-sm-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Partners</li>
                </ol>
            </nav>
        </div>
    </div>
@endsection

@section('content')
    <div class="card rounded-0">
        <div class="card-header">
            <h3 class="card-title">
                Partners List
            </h3>
            <div class="float-end">
                <a href="{{ route('admin.partners.create') }}" class="btn btn-success btn-sm rounded-0">
                    <i class="bi bi-plus-circle"></i>
                    Add Partner
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th width="60">No</th>
                            <th>Logo</th>
                            <th>Partner</th>
                            <th>Type</th>
                            <th>Partners</th>
                            <th>Status</th>
                            <th width="160">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($partners->count())
                            @foreach ($partners as $partner)
                                <tr>
                                    <td>
                                        {{ $partners->firstItem() + $loop->index }}
                                    </td>
                                    <td>
                                        @if ($partner->logo)
                                            <img src="{{ asset('storage/' . $partner->logo) }}" alt="{{ $partner->name }}"
                                                class="img-thumbnail rounded-0"
                                                style="width: 55px; height: 55px; object-fit: contain;">
                                        @else
                                            <div class="rounded-0 bg-light border d-flex align-items-center justify-content-center"
                                                style="width: 55px; height: 55px;">
                                                <i class="bi bi-building text-muted fs-4"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-semibold">
                                            {{ $partner->name }}
                                        </div>
                                        @if ($partner->description)
                                            <small class="text-muted">
                                                {{ \Illuminate\Support\Str::limit($partner->description, 70) }}
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge text-bg-secondary rounded-0">
                                            {{ ucwords(str_replace('_', ' ', $partner->type)) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($partner->conference)
                                            <strong>
                                                {{ $partner->conference->short_name }}
                                            </strong>
                                            <small class="text-muted d-block">
                                                {{ $partner->conference->year }}
                                            </small>
                                        @else
                                            <span class="text-muted">
                                                —
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($partner->is_active)
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
                                        <div class="btn-group gap-2">
                                            <a href="{{ route('admin.partners.show', $partner) }}"
                                                class="btn btn-info btn-sm rounded-0" title="View">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.partners.edit', $partner) }}"
                                                class="btn btn-warning btn-sm rounded-0" title="Edit">

                                                <i class="bi bi-pencil"></i>

                                            </a>
                                            <form action="{{ route('admin.partners.destroy', $partner) }}" method="POST"
                                                class="d-inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm rounded-0"
                                                    title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7">
                                    <div class="text-center py-5">
                                        <i class="bi bi-calendar-event display-4 text-secondary"></i>
                                        <h5 class="mt-3">
                                            No Partners Found
                                        </h5>
                                        <p class="text-muted mb-3">
                                            There is no partners data yet.
                                        </p>
                                        <a href="{{ route('admin.partners.create') }}" class="btn btn-success rounded-0">
                                            <i class="bi bi-plus-circle me-2"></i>
                                            Create First Partners
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                @if ($partners->hasPages())
                    <div class="card-footer clearfix">
                        {{ $partners->links() }}
                    </div>
                @endif
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
                    title: 'Delete Partner?',
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
