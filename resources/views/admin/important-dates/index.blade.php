@extends('layouts.admin')

@section('title', 'Important Dates Management')

@section('header')
    <div class="row">
        <div class="col-sm-6">
            <h1 class="mb-0 fs-3">Important Dates Management</h1>
        </div>
        <div class="col-sm-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Important Dates</li>
                </ol>
            </nav>
        </div>
    </div>
@endsection

@section('content')
    <div class="card rounded-0">
        <div class="card-header">
            <h3 class="card-title">
                Important Dates List
            </h3>
            <div class="float-end">
                <a href="{{ route('admin.important-dates.create') }}" class="btn btn-success btn-sm rounded-0">
                    <i class="bi bi-plus-circle"></i>
                    Add Important Date
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th width="60">No</th>
                            <th>Date</th>
                            <th>Event</th>
                            <th>Type</th>
                            <th>Conference</th>
                            <th width="100">Status</th>
                            <th width="160">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($importantDates as $importantDate)
                            <tr>
                                <td>
                                    {{ $importantDates->firstItem() + $loop->index }}
                                </td>
                                <td>
                                    <div class="fw-semibold">
                                        {{ $importantDate->date->format('d M Y') }}
                                    </div>
                                    @if ($importantDate->end_date)
                                        <small class="text-muted">
                                            to
                                            {{ $importantDate->end_date->format('d M Y') }}
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-semibold">
                                        {{ $importantDate->title }}
                                    </div>
                                    @if ($importantDate->description)
                                        <small class="text-muted">
                                            {{ \Illuminate\Support\Str::limit($importantDate->description, 80) }}
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $typeLabels = [
                                            'abstract_submission' => 'Abstract Submission',
                                            'full_paper_submission' => 'Full Paper Submission',
                                            'registration' => 'Registration',
                                            'conference' => 'Conference',
                                            'camera_ready' => 'Camera Ready',
                                            'other' => 'Other',
                                        ];
                                    @endphp
                                    <span class="badge text-bg-primary rounded-0">
                                        {{ $typeLabels[$importantDate->type] ?? 'Other' }}
                                    </span>
                                </td>
                                <td>
                                    @if ($importantDate->conference)
                                        <strong>
                                            {{ $importantDate->conference->short_name }}
                                        </strong>
                                        <small class="text-muted d-block">
                                            {{ $importantDate->conference->year }}
                                        </small>
                                    @else
                                        <span class="text-muted">
                                            —
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if ($importantDate->is_active)
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
                                        <a href="{{ route('admin.important-dates.show', $importantDate) }}"
                                            class="btn btn-info btn-sm rounded-0" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.important-dates.edit', $importantDate) }}"
                                            class="btn btn-warning btn-sm rounded-0" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.important-dates.destroy', $importantDate) }}"
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
                                    <i class="bi bi-calendar-event display-5 text-muted">
                                    </i>
                                    <h5 class="mt-3">
                                        No Important Dates Found
                                    </h5>
                                    <p class="text-muted mb-3">
                                        There are no important dates data yet.
                                    </p>
                                    <a href="{{ route('admin.important-dates.create') }}" class="btn btn-success btn-sm">
                                        <i class="bi bi-plus-circle me-1"></i>
                                        Create First Important Dates
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                @if ($importantDates->hasPages())
                    <div class="card-footer">
                        {{ $importantDates->links() }}
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
                    title: 'Delete Important Date?',
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
