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
                                yet.</p>
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
        @if ($nextAction)
            <div class="card rounded-0 mb-3 border-start border-4 border-{{ $nextAction['type'] }}">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi {{ $nextAction['icon'] }} me-2"></i>
                        Next Action
                    </h3>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-start gap-3">
                        <div class="flex-shrink-0">
                            <div class="bg-{{ $nextAction['type'] }}-subtle rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 52px; height: 52px;">
                                <i class="bi {{ $nextAction['icon'] }} fs-4 text-{{ $nextAction['type'] }}"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="fw-bold mb-1">
                                {{ $nextAction['title'] }}
                            </h5>
                            <p class="text-muted mb-3">
                                {{ $nextAction['description'] }}
                            </p>
                            @if ($nextAction['button'] && $nextAction['route'])
                                <a href="{{ $nextAction['route'] }}" class="btn btn-{{ $nextAction['type'] }} rounded-0">
                                    <i class="bi bi-arrow-right me-1"></i>
                                    {{ $nextAction['button'] }}
                                </a>
                            @else
                                <span class="badge text-bg-secondary rounded-0">
                                    No action required
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if ($importantDates->isNotEmpty())
            <div class="card rounded-0 mb-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-calendar-event me-2"></i>
                        Important Dates
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach ($participants as $participant)
                            @php
                                $conferenceDates = $importantDates->get($participant->conference_id, collect());
                            @endphp
                            @if ($conferenceDates->isNotEmpty())
                                <div class="col-lg-6 mb-3">
                                    <div class="border rounded-0 h-100 p-3">
                                        <h6 class="fw-bold mb-1">
                                            {{ $participant->conference?->name ?? 'Conference' }}
                                        </h6>
                                        <small class="text-muted d-block mb-3">
                                            {{ $participant->conference?->short_name ?? '—' }}
                                            @if ($participant->conference?->year)
                                                ({{ $participant->conference->year }})
                                            @endif
                                        </small>
                                        <div class="list-group list-group-flush">
                                            @foreach ($conferenceDates as $importantDate)
                                                <div class="list-group-item px-0">
                                                    <div class="d-flex justify-content-between align-items-start gap-3">
                                                        <div>
                                                            <div class="fw-semibold">
                                                                {{ $importantDate->title }}
                                                            </div>
                                                            @if ($importantDate->description)
                                                                <small class="text-muted d-block mt-1">
                                                                    {{ $importantDate->description }}
                                                                </small>
                                                            @endif
                                                        </div>
                                                        <div class="text-end text-nowrap">
                                                            <small class="fw-semibold">
                                                                {{ $importantDate->date->format('d M Y') }}
                                                            </small>
                                                            @if ($importantDate->end_date)
                                                                <small class="text-muted d-block">
                                                                    to {{ $importantDate->end_date->format('d M Y') }}
                                                                </small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
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
                        <div class="col-12 mb-3">
                            <div class="border rounded-0 p-3">

                                <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">
                                    <div>
                                        <h5 class="fw-bold mb-1">
                                            {{ $participant->conference?->name ?? 'Conference' }}
                                        </h5>

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

                                <div class="row">
                                    <div class="col-md-3 col-6 mb-2">
                                        <small class="text-muted d-block">
                                            Registration
                                        </small>

                                        <strong>
                                            {{ $participant->registration_number }}
                                        </strong>
                                    </div>

                                    <div class="col-md-3 col-6 mb-2">
                                        <small class="text-muted d-block">
                                            Registration Type
                                        </small>

                                        <strong>
                                            {{ $participant->registrationType?->name ?? ucfirst($participant->participant_type) }}
                                        </strong>
                                    </div>

                                    <div class="col-md-3 col-6 mb-2">
                                        <small class="text-muted d-block">
                                            Attendance
                                        </small>

                                        <strong>
                                            {{ ucfirst($participant->attendance_type) }}
                                        </strong>
                                    </div>

                                    <div class="col-md-3 col-6 mb-2">
                                        <small class="text-muted d-block">
                                            Submissions
                                        </small>

                                        <strong>
                                            {{ $participant->submissions->count() }}
                                        </strong>
                                    </div>
                                </div>

                                @if ($participant->submissions->isNotEmpty())
                                    <hr>

                                    <div>
                                        <div class="small text-muted mb-2">
                                            Recent Submissions
                                        </div>

                                        <div class="list-group">
                                            @foreach ($participant->submissions->sortByDesc('created_at')->take(3) as $submission)
                                                <div class="list-group-item rounded-0 px-3 py-3">
                                                    <div class="d-flex justify-content-between align-items-start gap-3">
                                                        <div>
                                                            <strong>
                                                                {{ $submission->submission_code }}
                                                            </strong>

                                                            <div class="mt-1">
                                                                {{ \Illuminate\Support\Str::limit($submission->title, 80) }}
                                                            </div>
                                                        </div>

                                                        <div class="text-nowrap">
                                                            @if ($submission->status === 'submitted')
                                                                <span class="badge text-bg-primary rounded-0">
                                                                    Submitted
                                                                </span>
                                                            @elseif ($submission->status === 'under_review')
                                                                <span class="badge text-bg-warning rounded-0">
                                                                    Under Review
                                                                </span>
                                                            @elseif ($submission->status === 'revision')
                                                                <span class="badge text-bg-warning rounded-0">
                                                                    Revision Required
                                                                </span>
                                                            @elseif ($submission->status === 'accepted')
                                                                <span class="badge text-bg-success rounded-0">
                                                                    Accepted
                                                                </span>
                                                            @elseif ($submission->status === 'camera_ready')
                                                                <span class="badge text-bg-info rounded-0">
                                                                    Camera Ready
                                                                </span>
                                                            @elseif ($submission->status === 'published')
                                                                <span class="badge text-bg-dark rounded-0">
                                                                    Published
                                                                </span>
                                                            @elseif ($submission->status === 'rejected')
                                                                <span class="badge text-bg-danger rounded-0">
                                                                    Rejected
                                                                </span>
                                                            @else
                                                                <span class="badge text-bg-secondary rounded-0">
                                                                    {{ ucfirst($submission->status) }}
                                                                </span>
                                                            @endif
                                                        </div>

                                                    </div>

                                                    <div class="mt-3">

                                                        <a href="{{ route('participant.submissions.show', $submission) }}"
                                                            class="btn btn-outline-primary btn-sm rounded-0">

                                                            <i class="bi bi-eye me-1"></i>
                                                            View Submission

                                                        </a>

                                                    </div>

                                                </div>
                                            @endforeach

                                        </div>
                                    </div>
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
