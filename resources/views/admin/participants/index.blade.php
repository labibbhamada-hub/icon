@extends('layouts.admin')

@section('title', 'Participants Management')

@section('header')
    <div class="row">
        <div class="col-sm-6">
            <h1 class="mb-0 fs-3">Participants Management</h1>
        </div>
        <div class="col-sm-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Participants</li>
                </ol>
            </nav>
        </div>
    </div>
@endsection

@section('content')
    <div class="card rounded-0">
        <div class="card-header">
            <h3 class="card-title">
                Participants List
            </h3>
            <div class="float-end">
                <a href="{{ route('admin.participants.create') }}" class="btn btn-success btn-sm rounded-0">
                    <i class="bi bi-person-plus"></i>
                    Add Participant
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th width="40">No</th>
                            <th>Registration</th>
                            <th>Participant</th>
                            <th>Institution</th>
                            <th>Type</th>
                            <th>Attendance</th>
                            <th>Status</th>
                            <th width="120">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($participants as $participant)
                            <tr>
                                <td class="align-top">
                                    {{ $participants->firstItem() + $loop->index }}
                                </td>
                                <td class="align-top">
                                    <span class="fw-semibold">
                                        {{ $participant->registration_number }}
                                    </span>
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
                                    <small class="text-muted">
                                        {{ $participant->email }}
                                    </small>
                                </td>
                                <td class="align-top">
                                    @if ($participant->institution)
                                        {{ $participant->institution }}
                                        @if ($participant->department)
                                            <small class="text-muted d-block">
                                                {{ $participant->department }}
                                            </small>
                                        @endif
                                    @else
                                        <span class="text-muted">
                                            —
                                        </span>
                                    @endif
                                </td>
                                <td class="align-top">
                                    @php
                                        $participantTypes = [
                                            'regular' => 'Regular',
                                            'student' => 'Student',
                                            'speaker' => 'Speaker',
                                            'committee' => 'Committee',
                                        ];
                                    @endphp
                                    <span class="badge text-bg-secondary rounded-0">
                                        {{ $participantTypes[$participant->participant_type] ?? 'Other' }}
                                    </span>
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
                                    <div class="btn-group gap-1">
                                        <a href="{{ route('admin.participants.show', $participant) }}"
                                            class="btn btn-info btn-sm rounded-0" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.participants.edit', $participant) }}"
                                            class="btn btn-warning btn-sm rounded-0" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.participants.destroy', $participant) }}"
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
                                <td colspan="8" class="text-center py-5">
                                    <i class="bi bi-people display-5 text-muted">
                                    </i>
                                    <h5 class="mt-3">
                                        No Participants Found
                                    </h5>
                                    <p class="text-muted mb-3">
                                        There are no participants registered yet.
                                    </p>
                                    <a href="{{ route('admin.participants.create') }}" class="btn btn-success rounded-0">
                                        <i class="bi bi-person-plus me-2"></i>
                                        Add Participant
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($participants->hasPages())
            <div class="card-footer">
                {{ $participants->links() }}
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
                    title: 'Delete Participant?',
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
