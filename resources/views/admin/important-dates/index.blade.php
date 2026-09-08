@extends('layouts.admin')

@section('title', 'Important Dates')

@section('header')
    <div class="row">
        <div class="col-sm-6">
            <h1 class="mb-0 fs-3">
                Important Dates Management
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
                        Important Dates
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
                <i class="bi bi-calendar-event me-2"></i>
                Important Dates List
            </h3>

            <div class="float-end">
                <a href="{{ route('admin.important-dates.create') }}" class="btn btn-success btn-sm rounded-0">
                    <i class="bi bi-plus-circle me-1"></i>
                    Add Important Date
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
                                Date
                            </th>

                            <th>
                                Event
                            </th>

                            <th>
                                Type
                            </th>

                            <th>
                                Conference
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
                                        <small class="text-muted d-block">
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
                                        <small class="text-muted d-block">
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
                                            'review' => 'Review',
                                            'revision' => 'Revision',
                                            'camera_ready' => 'Camera Ready',
                                            'conference' => 'Conference',
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
                                            -
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

                                    <div class="btn-group gap-1">

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

                                    <div class="mb-2">
                                        <i class="bi bi-calendar-event display-5 text-muted"></i>
                                    </div>

                                    <h5 class="mb-1">
                                        No Important Dates Found
                                    </h5>

                                    <p class="text-muted mb-3">
                                        There are no important date data yet.
                                    </p>

                                    <a href="{{ route('admin.important-dates.create') }}"
                                        class="btn btn-success rounded-0">
                                        <i class="bi bi-plus-circle me-1"></i>
                                        Create First Important Date
                                    </a>

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

        @if ($importantDates->hasPages())
            <div class="card-footer">
                {{ $importantDates->links() }}
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
                    title: 'Delete Important Date?',
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
