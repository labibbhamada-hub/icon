@extends('layouts.admin')

@section('title', 'Participants Management')

@section('header')
    <div class="row">
        <div class="col-sm-6">
            <h1 class="mb-0 fs-3">
                Participants Management
            </h1>
            <p class="text-muted mb-0">
                Manage conference participants and their registration information.
            </p>
        </div>
        <div class="col-sm-6">
            <div class="float-sm-end">
                <a href="{{ route('admin.participants.export') }}" class="btn btn-dark rounded-0">
                    <i class="bi bi-file-earmark-excel me-1"></i>
                    Export Excel
                </a>
                <a href="{{ route('admin.participants.create') }}" class="btn btn-success rounded-0">
                    <i class="bi bi-person-plus me-1"></i>
                    Add Participant
                </a>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card rounded-0 overflow-hidden">
        <div class="card-header rounded-0">
            <h3 class="card-title">
                <i class="bi bi-people me-2"></i>
                Participants List
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
                                Registration
                            </th>
                            <th>
                                Participant
                            </th>
                            <th>
                                Registration Type
                            </th>
                            <th>
                                Attendance
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
                        @forelse ($participants as $participant)
                            <tr>
                                <td class="align-top">
                                    {{ $participants->firstItem() + $loop->index }}
                                </td>
                                <td class="align-top">
                                    <div class="fw-semibold">
                                        {{ $participant->registration_number }}
                                    </div>
                                    @if ($participant->registered_at)
                                        <small class="text-muted d-block">
                                            {{ $participant->registered_at->format('d M Y') }}
                                        </small>
                                    @endif
                                </td>
                                <td class="align-top">
                                    <div class="fw-semibold">
                                        {{ $participant->full_name }}
                                    </div>
                                    <small class="text-muted d-block">
                                        {{ $participant->email }}
                                    </small>
                                    @if ($participant->institution)
                                        <small class="text-muted d-block">
                                            {{ $participant->institution }}
                                        </small>
                                    @endif
                                </td>
                                <td class="align-top">
                                    @if ($participant->registrationType)
                                        <div class="fw-semibold">
                                            {{ $participant->registrationType->name }}
                                        </div>
                                        <small class="text-muted">
                                            {{ ucfirst($participant->registrationType->category) }}
                                        </small>
                                    @else
                                        <span class="text-muted">
                                            -
                                        </span>
                                    @endif
                                </td>
                                <td class="align-top">
                                    @php
                                        $attendanceTypes = [
                                            'offline' => 'Offline',
                                            'online' => 'Online',
                                            'hybrid' => 'Hybrid',
                                        ];
                                    @endphp
                                    <span class="badge text-bg-info rounded-0">
                                        {{ $attendanceTypes[$participant->attendance_type] ?? 'Other' }}
                                    </span>
                                </td>
                                <td class="align-top">
                                    @if ($participant->registration_status === 'confirmed')
                                        <span class="badge text-bg-success rounded-0">
                                            Confirmed
                                        </span>
                                    @elseif ($participant->registration_status === 'cancelled')
                                        <span class="badge text-bg-danger rounded-0">
                                            Cancelled
                                        </span>
                                    @else
                                        <span class="badge text-bg-warning rounded-0">
                                            Pending
                                        </span>
                                    @endif
                                </td>
                                <td class="align-top">
                                    <form action="{{ route('admin.participants.destroy', $participant) }}" method="POST"
                                        class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <a href="{{ route('admin.participants.show', $participant) }}"
                                            class="btn btn-info rounded-0" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.participants.edit', $participant) }}"
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
                                        <i class="bi bi-people display-5 text-muted"></i>
                                    </div>
                                    <h5 class="mb-1">
                                        No Participants Found
                                    </h5>
                                    <p class="text-muted mb-3">
                                        There are no participants registered yet.
                                    </p>
                                    <a href="{{ route('admin.participants.create') }}" class="btn btn-success rounded-0">
                                        <i class="bi bi-person-plus me-1"></i>
                                        Create First Participant
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($participants->hasPages())
            <div class="card-footer rounded-0">
                {{ $participants->links() }}
            </div>
        @endif

    </div>

@endsection
