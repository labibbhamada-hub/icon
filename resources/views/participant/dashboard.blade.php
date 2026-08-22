@extends('layouts.participant')

@section('title', 'Dashboard')

@section('header')
    <div class="row">
        <div class="col-sm-6">
            <h1 class="mb-0 fs-3 fw-bold">Dashboard</h1>
            <p class="text-muted mb-0">
                Welcome back,
                <strong>{{ auth()->user()->name }}</strong>
            </p>
        </div>
        <div class="col-sm-6 text-end">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                </ol>
            </nav>
        </div>
    </div>
@endsection

@section('content')
    @if ($participants->isEmpty())
        <div class="row">
            <div class="col-12">
                <div class="card rounded-0">
                    <div class="card-body text-center py-5">
                        <div class="rounded-circle bg-warning-subtle d-flex align-items-center justify-content-center mx-auto mb-2"
                            style="width: 100px; height: 100px;">
                            <i class="bi bi-person-exclamation display-5 text-warning"></i>
                        </div>
                        <div class="mb-2">
                            <h4 class="fw-bold">Welcome to ICON 2026</h4>
                            <p class="text-muted">Your account is active, but you have not registered for any conference
                                yet.
                            </p>
                        </div>
                        <a href="{{ route('participant.registration.create') }}" class="btn btn-success rounded-0">
                            <i class="bi bi-calendar-plus me-2"></i>
                            Register Conference
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        @php
            $totalRegistrations = $participants->count();
            $confirmedRegistrations = $participants->where('registration_status', 'confirmed')->count();
            $pendingRegistrations = $participants->where('registration_status', 'pending')->count();
            $cancelledRegistrations = $participants->where('registration_status', 'cancelled')->count();
            $totalSubmissions = $participants->sum(fn($participant) => $participant->submissions->count());
            $acceptedSubmissions = $participants->sum(
                fn($participant) => $participant->submissions->where('status', 'accepted')->count(),
            );
        @endphp
        <div class="row mb-2">
            <div class="col-lg-3 col-md-6">
                <div class="small-box text-bg-primary rounded-0 mb-2">
                    <div class="inner">
                        <h3>{{ $totalRegistrations }}</h3>
                        <p>Registrations</p>
                    </div>
                    <div class="small-box-icon">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="small-box text-bg-success rounded-0 mb-2">
                    <div class="inner">
                        <h3>{{ $confirmedRegistrations }}</h3>
                        <p>Confirmed</p>
                    </div>
                    <div class="small-box-icon">
                        <i class="bi bi-check-circle"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="small-box text-bg-warning rounded-0 mb-2">
                    <div class="inner">
                        <h3>{{ $pendingRegistrations }}</h3>
                        <p>Pending</p>
                    </div>
                    <div class="small-box-icon">
                        <i class="bi bi-clock"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="small-box text-bg-info rounded-0 mb-2">
                    <div class="inner">
                        <h3>{{ $totalSubmissions }}</h3>
                        <p>My Submissions</p>
                    </div>
                    <div class="small-box-icon">
                        <i class="bi bi-file-earmark-text"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="card rounded-0 mb-3">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="bi bi-person-vcard me-2"></i>
                    Account Information
                </h3>
                <div class="float-end">
                    <a href="{{ route('participant.profile.edit') }}" class="btn btn-warning btn-sm rounded-0">
                        <i class="bi bi-pencil"></i>
                        Edit Profile
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-2">
                        <small class="text-muted d-block">Name</small>
                        <div class="fw-semibold">{{ auth()->user()->name }}</div>
                    </div>
                    <div class="col-md-4 mb-2">
                        <small class="text-muted d-block">Email</small>
                        <div class="fw-semibold">{{ auth()->user()->email }}</div>
                    </div>
                    <div class="col-md-4 mb-2">
                        <small class="text-muted d-block">Account Status</small>
                        <div>
                            @if (auth()->user()->status === 'active')
                                <span class="badge text-bg-success rounded-0">
                                    Active
                                </span>
                            @else
                                <span class="badge text-bg-secondary rounded-0">
                                    Inactive
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card rounded-0 mb-3">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="bi bi-calendar-event me-2"></i>
                    My Conference Registrations
                </h3>
                <div class="float-end">
                    <a href="{{ route('participant.registration.create') }}" class="btn btn-success btn-sm rounded-0">
                        <i class="bi bi-plus-circle me-1"></i>
                        Register Conference
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach ($participants as $participant)
                        <div class="col-lg-6 mb-3">
                            <div class="border rounded-0 h-100 p-3">
                                <div class="d-flex justify-content-between align-items-start gap-3">
                                    <div>
                                        <h5 class="fw-bold">{{ $participant->conference?->name ?? 'Conference' }}</h5>
                                        <small class="text-muted">
                                            {{ $participant->conference?->short_name ?? '—' }}
                                            @if ($participant->conference?->year)
                                                ({{ $participant->conference->year }})
                                            @endif
                                        </small>
                                    </div>
                                    <div>
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
                                    </div>
                                </div>
                                <hr>
                                <div class="row mb-2">
                                    <div class="col-6 mb-2">
                                        <small class="text-muted d-block">Registration</small>
                                        <strong>{{ $participant->registration_number }}</strong>
                                    </div>
                                    <div class="col-6 mb-2">
                                        <small class="text-muted d-block">Participant Type</small>
                                        <span>{{ ucfirst($participant->participant_type) }}</span>
                                    </div>
                                    <div class="col-6 mb-2">
                                        <small class="text-muted d-block">Attendance</small>
                                        <span>{{ ucfirst($participant->attendance_type) }}</span>
                                    </div>
                                    <div class="col-6 mb-2">
                                        <small class="text-muted d-block">Submissions</small>
                                        <strong>{{ $participant->submissions->count() }}</strong>
                                    </div>
                                </div>
                                @if (
                                    $participant->registration_status === 'confirmed' &&
                                        $participant->conference?->settings?->submission_enabled &&
                                        !$participant->conference?->settings?->maintenance_mode)
                                    <a href="{{ route('participant.submissions.create') }}"
                                        class="btn btn-outline-success btn-sm rounded-0">
                                        <i class="bi bi-file-earmark-plus me-1"></i>
                                        Submit Paper
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="card rounded-0">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="bi bi-file-earmark-check me-2"></i>
                    Recent Submissions
                </h3>
                <div class="float-end">
                    <a href="{{ route('participant.submissions.index') }}"
                        class="btn btn-outline-primary btn-sm rounded-0">
                        View All
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                @php
                    $recentSubmissions = $participants
                        ->flatMap(fn($participant) => $participant->submissions)
                        ->sortByDesc('created_at')
                        ->take(5);
                @endphp
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Submission</th>
                                <th>Conference</th>
                                <th>Topic</th>
                                <th>Status</th>
                                <th>Submitted</th>
                                <th width="40" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentSubmissions as $submission)
                                <tr>
                                    <td class="align-top">
                                        <strong>
                                            {{ $submission->submission_code }}
                                        </strong>
                                        <small class="text-muted d-block">
                                            {{ \Illuminate\Support\Str::limit($submission->title, 55) }}
                                        </small>
                                    </td>
                                    <td class="align-top">
                                        {{ $submission->conference?->short_name ?? '—' }}
                                    </td>
                                    <td class="align-top">
                                        {{ $submission->topic?->name ?? '—' }}
                                    </td>
                                    <td class="align-top">
                                        @if ($submission->status === 'draft')
                                            <span class="badge text-bg-secondary rounded-0">
                                                Draft
                                            </span>
                                        @elseif ($submission->status === 'submitted')
                                            <span class="badge text-bg-primary rounded-0">
                                                Submitted
                                            </span>
                                        @elseif ($submission->status === 'under_review')
                                            <span class="badge text-bg-warning rounded-0">
                                                Under Review
                                            </span>
                                        @elseif ($submission->status === 'revision')
                                            <span class="badge text-bg-warning rounded-0">
                                                Revision
                                            </span>
                                        @elseif ($submission->status === 'accepted')
                                            <span class="badge text-bg-success rounded-0">
                                                Accepted
                                            </span>
                                        @elseif ($submission->status === 'rejected')
                                            <span class="badge text-bg-danger rounded-0">
                                                Rejected
                                            </span>
                                        @elseif ($submission->status === 'camera_ready')
                                            <span class="badge text-bg-info rounded-0">
                                                Camera Ready
                                            </span>
                                        @elseif ($submission->status === 'published')
                                            <span class="badge text-bg-dark rounded-0">
                                                Published
                                            </span>
                                        @else
                                            <span class="badge text-bg-secondary rounded-0">
                                                Unknown
                                            </span>
                                        @endif
                                    </td>
                                    <td class="align-top">
                                        {{ $submission->submitted_at?->format('d M Y') ?? '—' }}
                                    </td>
                                    <td class="align-top">
                                        <a href="{{ route('participant.submissions.show', $submission) }}"
                                            class="btn btn-info btn-sm rounded-0" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <i class="bi bi-file-earmark-x display-5 text-muted"></i>
                                        <h5 class="mt-3">
                                            No Submissions Yet
                                        </h5>
                                        <p class="text-muted mb-0">
                                            Your submitted papers will appear here.
                                        </p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
@endsection
