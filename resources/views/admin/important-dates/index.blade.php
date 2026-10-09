@extends('layouts.admin')

@section('title', 'Important Dates')

@section('header')
    <div class="row align-items-top">
        <div class="col-sm-8">
            <h1 class="mb-0 fs-3">
                Important Dates Management
            </h1>
            <p class="text-muted mb-0">
                Manage important conference dates and schedules.
            </p>
        </div>
        <div class="col-sm-4">
            <a href="{{ route('admin.important-dates.create') }}" class="btn btn-success float-sm-end rounded-0">
                <i class="bi bi-plus-circle me-1"></i>
                Add Important Date
            </a>
        </div>
    </div>
@endsection

@section('content')
    <div class="card rounded-0 overflow-hidden">
        <div class="card-header rounded-0">
            <h3 class="card-title">
                <i class="bi bi-calendar-event me-2"></i>
                Important Dates List
            </h3>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive rounded-0">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th width="50">
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
                            <th width="170">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($importantDates as $importantDate)
                            <tr>
                                <td class="align-top">
                                    {{ $importantDates->firstItem() + $loop->index }}
                                </td>
                                <td class="align-top">
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
                                <td class="align-top">
                                    <div class="fw-semibold">
                                        {{ $importantDate->title }}
                                    </div>
                                    @if ($importantDate->description)
                                        <small class="text-muted d-block">
                                            {{ \Illuminate\Support\Str::limit($importantDate->description, 80) }}
                                        </small>
                                    @endif
                                </td>
                                <td class="align-top">
                                    @php
                                        $typeLabels = [
                                            'abstract_submission' => 'Abstract Submission',
                                            'full_paper_submission' => 'Full Paper Submission',
                                            'registration' => 'Registration',
                                            'review' => 'Review',
                                            'revision' => 'Revision',
                                            'camera_ready' => 'Camera Ready',
                                            'publication' => 'Publication',
                                            'conference' => 'Conference',
                                            'other' => 'Other',
                                        ];
                                    @endphp
                                    <span class="badge text-bg-primary rounded-0">
                                        {{ $typeLabels[$importantDate->type] ?? 'Other' }}
                                    </span>
                                </td>
                                <td class="align-top">
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
                                <td class="align-top">
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
                                <td class="align-top">
                                    <form action="{{ route('admin.important-dates.destroy', $importantDate) }}"
                                        method="POST" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <a href="{{ route('admin.important-dates.show', $importantDate) }}"
                                            class="btn btn-info rounded-0" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.important-dates.edit', $importantDate) }}"
                                            class="btn btn-warning rounded-0" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="submit" class="btn btn-danger rounded-0" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
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
            <div class="card-footer rounded-0">
                {{ $importantDates->links() }}
            </div>
        @endif
    </div>
@endsection
