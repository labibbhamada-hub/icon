@extends('layouts.participant')

@section('title', 'Registration')

@section('header')
    <div class="row align-items-top">
        <div class="col-sm-6">
            <h1 class="mb-0 fs-3">
                My Registration
            </h1>
            <p class="text-muted mb-0">View your conference registrations.</p>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item">
                    <a href="{{ route('participant.dashboard') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Registration</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="card rounded-0">
        <div class="card-header">
            <h3 class="card-title">
                Conference Registrations
            </h3>
            <div class="float-end">
                <a href="{{ route('participant.registration.create') }}" class="btn btn-success btn-sm rounded-0">
                    <i class="bi bi-plus-circle me-1"></i>
                    Register Conference
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="align-top">Registration</th>
                            <th class="align-top">Conference</th>
                            <th class="align-top">Participant Type</th>
                            <th class="align-top">Attendance</th>
                            <th class="align-top">Status</th>
                            <th class="align-top">Registered</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($registrations as $registration)
                            <tr>
                                <td class="align-top">
                                    <strong>
                                        {{ $registration->registration_number }}
                                    </strong>
                                </td>
                                <td class="align-top">
                                    @if ($registration->conference)
                                        <strong>
                                            {{ $registration->conference->name }}
                                        </strong>
                                        <small class="text-muted d-block">
                                            {{ $registration->conference->short_name }}
                                            ({{ $registration->conference->year }})
                                        </small>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="align-top">
                                    {{ ucwords(str_replace('_', ' ', $registration->participant_type)) }}
                                </td>
                                <td class="align-top">
                                    {{ ucfirst($registration->attendance_type) }}
                                </td>
                                <td class="align-top">
                                    @if ($registration->registration_status === 'confirmed')
                                        <span class="badge text-bg-success rounded-0">
                                            Confirmed
                                        </span>
                                    @elseif ($registration->registration_status === 'cancelled')
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
                                    {{ $registration->registered_at?->format('d M Y H:i') ?? '—' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="bi bi-calendar-x display-5 text-muted"></i>
                                    <h5 class="mt-3">
                                        No Registrations
                                    </h5>
                                    <p class="text-muted mb-3">
                                        You have not registered for any conference yet.
                                    </p>
                                    <a href="{{ route('participant.registration.create') }}"
                                        class="btn btn-success rounded-0">
                                        <i class="bi bi-plus-circle me-1"></i>
                                        Register Conference
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
