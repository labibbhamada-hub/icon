@extends('layouts.participant')

@section('title', 'Dashboard')

@section('header') <div class="row">
        <div class="col-sm-6">
            <h1 class="mb-0 fs-3 fw-bold">Dashboard</h1>
            <p class="text-muted mb-0">
                Welcome back, <strong>{{ auth()->user()->name }}</strong> </p>
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
                <div class="card rounded-0 overflow-hidden">
                    <div class="card-body text-center py-5">
                        <div class="rounded-0 bg-warning-subtle d-flex align-items-center justify-content-center mx-auto mb-2"
                            style="width: 100px; height: 100px;"> <i
                                class="bi bi-person-exclamation display-5 text-warning"></i> </div>

                        <div class="mb-2">
                            <h4 class="fw-bold">Welcome to ICON 2026</h4>
                            <p class="text-muted">
                                Your account is active, but you have not registered for any conference
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
            $statColumnClass = $hasPresenterRegistration ? 'col-lg-3 col-md-6' : 'col-lg-4 col-md-6';
            $acceptedSubmissions = $participants->sum(
                fn($participant) => $participant->submissions->where('status', 'accepted')->count(),
            );
        @endphp

        <div class="row mb-2">
            <div class="{{ $statColumnClass }}">
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

            <div class="{{ $statColumnClass }}">
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

            <div class="{{ $statColumnClass }}">
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

            @if ($hasPresenterRegistration)
                <div class="{{ $statColumnClass }}">
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
            @endif
        </div>

        @if ($nextAction)
            <div class="card rounded-0 overflow-hidden mb-3 border-start border-4 border-{{ $nextAction['type'] }}">
                <div class="card-header rounded-0">
                    <h3 class="card-title">
                        <i class="bi {{ $nextAction['icon'] }} me-2"></i>
                        Next Action
                    </h3>
                </div>

                <div class="card-body">
                    <div class="d-flex align-items-start gap-3">
                        <div class="flex-shrink-0">
                            <div class="bg-{{ $nextAction['type'] }}-subtle rounded-0 d-flex align-items-center justify-content-center"
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

        @php
            $eligibleMeetings = collect();

            foreach ($participants as $participant) {
                $meeting = $participantMeetings[$participant->id] ?? null;

                if ($meeting) {
                    $eligibleMeetings->put($participant->conference_id, [
                        'participant' => $participant,
                        'meeting' => $meeting,
                    ]);
                }
            }
        @endphp

        @if ($eligibleMeetings->isNotEmpty())
            <div class="card rounded-0 overflow-hidden mb-3">

                <div class="card-header rounded-0">
                    <h3 class="card-title">
                        <i class="bi bi-camera-video me-2"></i>
                        Online Meeting
                    </h3>
                </div>

                <div class="card-body">

                    @foreach ($eligibleMeetings as $item)
                        @php
                            $participant = $item['participant'];
                            $meeting = $item['meeting'];
                        @endphp

                        <div class="border rounded-0 p-3 mb-3">

                            <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">

                                <div>
                                    <h5 class="fw-bold mb-1">
                                        {{ $meeting->title }}
                                    </h5>

                                    <small class="text-muted">
                                        {{ $participant->conference?->name ?? 'Conference' }}

                                        @if ($participant->conference?->year)
                                            ({{ $participant->conference->year }})
                                        @endif
                                    </small>
                                </div>

                                <span class="badge text-bg-success rounded-0">
                                    Active
                                </span>

                            </div>

                            <hr>

                            <div class="row g-3">

                                @if ($meeting->meeting_id)
                                    <div class="col-md-6">
                                        <small class="text-muted d-block">
                                            Meeting ID
                                        </small>

                                        <strong>
                                            {{ $meeting->meeting_id }}
                                        </strong>
                                    </div>
                                @endif

                                @if ($meeting->passcode)
                                    <div class="col-md-6">
                                        <small class="text-muted d-block">
                                            Passcode
                                        </small>

                                        <strong>
                                            {{ $meeting->passcode }}
                                        </strong>
                                    </div>
                                @endif

                                @if ($meeting->instructions)
                                    <div class="col-12">
                                        <small class="text-muted d-block mb-1">
                                            Instructions
                                        </small>

                                        <div>
                                            {!! nl2br(e($meeting->instructions)) !!}
                                        </div>
                                    </div>
                                @endif

                            </div>

                            <div class="mt-3">
                                <a href="{{ $meeting->meeting_url }}" target="_blank" rel="noopener noreferrer"
                                    class="btn btn-primary rounded-0">
                                    <i class="bi bi-box-arrow-up-right me-1"></i>
                                    Join Zoom Meeting
                                </a>
                            </div>

                        </div>
                    @endforeach

                </div>

            </div>
        @endif

        @php
            $eligibleWhatsappGroups = collect();

            foreach ($participants as $participant) {
                $group = $participantWhatsappGroups[$participant->id] ?? null;

                if ($group) {
                    $eligibleWhatsappGroups->put($participant->conference_id, [
                        'participant' => $participant,
                        'group' => $group,
                    ]);
                }
            }
        @endphp

        @if ($eligibleWhatsappGroups->isNotEmpty())
            <div class="card rounded-0 overflow-hidden mb-3">

                <div class="card-header rounded-0">
                    <h3 class="card-title">
                        <i class="bi bi-whatsapp me-2"></i>
                        WhatsApp Group
                    </h3>
                </div>

                <div class="card-body">

                    @foreach ($eligibleWhatsappGroups as $item)
                        @php
                            $participant = $item['participant'];
                            $group = $item['group'];
                        @endphp

                        <div class="border rounded-0 p-3 mb-3">

                            <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">

                                <div>
                                    <h5 class="fw-bold mb-1">
                                        {{ $group->title }}
                                    </h5>

                                    <small class="text-muted">
                                        {{ $participant->conference?->name ?? 'Conference' }}

                                        @if ($participant->conference?->year)
                                            ({{ $participant->conference->year }})
                                        @endif
                                    </small>
                                </div>

                                <span class="badge text-bg-success rounded-0">
                                    Active
                                </span>

                            </div>

                            <hr>

                            @if ($group->description)
                                <div class="mb-3">
                                    <small class="text-muted d-block mb-1">
                                        Description
                                    </small>

                                    <div>
                                        {!! nl2br(e($group->description)) !!}
                                    </div>
                                </div>
                            @endif

                            <div>
                                <a href="{{ $group->group_url }}" target="_blank" rel="noopener noreferrer"
                                    class="btn btn-success rounded-0">
                                    <i class="bi bi-whatsapp me-1"></i>
                                    Join WhatsApp Group
                                </a>
                            </div>

                        </div>
                    @endforeach

                </div>

            </div>
        @endif

        @if ($seminarConferences->isNotEmpty())
            <div class="card rounded-0 overflow-hidden mb-3">

                <div class="card-header rounded-0">
                    <h3 class="card-title">
                        <i class="bi bi-calendar-event me-2"></i>
                        Conference Schedule
                    </h3>
                </div>

                <div class="card-body">

                    @foreach ($seminarConferences as $conference)
                        @php
                            $startDate = $conference->start_date
                                ? \Illuminate\Support\Carbon::parse($conference->start_date)->startOfDay()
                                : null;

                            $endDate = $conference->end_date
                                ? \Illuminate\Support\Carbon::parse($conference->end_date)->endOfDay()
                                : null;
                        @endphp

                        <div class="border rounded-0 p-3 mb-3">

                            <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">

                                <div>
                                    <h5 class="fw-bold mb-1">
                                        {{ $conference->name }}
                                    </h5>

                                    <small class="text-muted">
                                        {{ $conference->short_name ?? '—' }}

                                        @if ($conference->year)
                                            ({{ $conference->year }})
                                        @endif
                                    </small>
                                </div>

                                <span class="badge text-bg-primary rounded-0">
                                    Conference
                                </span>

                            </div>

                            <hr>

                            <div class="row g-3">

                                <div class="col-md-6">
                                    <small class="text-muted d-block">
                                        Start Date
                                    </small>

                                    <strong>
                                        {{ $startDate?->format('d F Y') ?? '—' }}
                                    </strong>
                                </div>

                                <div class="col-md-6">
                                    <small class="text-muted d-block">
                                        End Date
                                    </small>

                                    <strong>
                                        {{ $endDate?->format('d F Y') ?? '—' }}
                                    </strong>
                                </div>

                            </div>

                            @if ($startDate && $endDate)
                                <div class="border rounded-0 p-3 mt-3 text-center" data-conference-countdown
                                    data-start="{{ $startDate->timestamp }}" data-end="{{ $endDate->timestamp }}">

                                    <small class="text-muted d-block" data-countdown-label>
                                        Starts In
                                    </small>

                                    <strong class="fs-4" data-countdown-value>
                                        —
                                    </strong>

                                </div>
                            @endif

                        </div>
                    @endforeach

                </div>

            </div>
        @endif

        @if ($hasPresenterRegistration && $importantDates->isNotEmpty())
            @php
                $conferenceIds = $presenterParticipants->pluck('conference_id')->unique()->values();
            @endphp

            <div class="card rounded-0 overflow-hidden mb-3">
                <div class="card-header rounded-0">
                    <h3 class="card-title">
                        <i class="bi bi-calendar-event me-2"></i>
                        Important Dates
                    </h3>
                </div>

                <div class="card-body">
                    <div class="row">
                        @foreach ($conferenceIds as $conferenceId)
                            @php
                                $participant = $participants->firstWhere('conference_id', $conferenceId);
                                $conferenceDates = $importantDates->get($conferenceId, collect());
                            @endphp

                            @if ($participant && $conferenceDates->isNotEmpty())
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
                                                <div class="list-group-item rounded-0 px-0">
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

                                                            @if ($importantDate->end_date && !$importantDate->date->isSameDay($importantDate->end_date))
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

        @php
            $profileParticipant = $participants->first();

            $profileIncomplete =
                !$profileParticipant || blank($profileParticipant->phone) || blank($profileParticipant->institution);
        @endphp
        <div class="card rounded-0 overflow-hidden mb-3">
            <div class="card-header rounded-0">
                <h3 class="card-title">
                    <i class="bi bi-person-vcard me-2"></i>
                    Account Information
                </h3>
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

                    <div class="col-md-4 mb-2">
                        <small class="text-muted d-block">
                            Phone Number
                        </small>

                        <div class="fw-semibold">
                            {{ $profileParticipant?->phone ?: 'Not provided' }}
                        </div>
                    </div>

                    <div class="col-md-4 mb-2">
                        <small class="text-muted d-block">
                            Institution
                        </small>

                        <div class="fw-semibold">
                            {{ $profileParticipant?->institution ?: 'Not provided' }}
                        </div>
                    </div>
                </div>
            </div>
            @if ($profileIncomplete)
                <div class="card-body border-top">
                    <div class="alert alert-warning rounded-0 mb-3">
                        <div class="flex-grow-1">
                            <strong>
                                Profile Incomplete
                            </strong>
                            <div class="small mt-1">
                                Please complete your Phone Number and Institution information.
                            </div>
                            <div class="mt-2">
                                <a href="{{ route('participant.profile.edit') }}" class="btn btn-warning rounded-0">
                                    <i class="bi bi-pencil"></i>
                                    Complete Profile
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="card rounded-0 overflow-hidden mb-3">
            <div class="card-header rounded-0">
                <h3 class="card-title">
                    <i class="bi bi-calendar-event me-2"></i>
                    Registration History
                </h3>
            </div>

            <div class="card-body">
                <div class="row">
                    @foreach ($participants as $participant)
                        @php
                            $isPresenter = $participant->registrationType?->category === 'presenter';
                        @endphp
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
                                    <div class="col-md-{{ $isPresenter ? '4' : '6' }} col-6 mb-2">
                                        <small class="text-muted d-block">
                                            Registration
                                        </small>

                                        <strong>
                                            {{ $participant->registration_number }}
                                        </strong>

                                        <small class="text-muted d-block">
                                            Registration Type
                                        </small>

                                        <strong>
                                            {{ $participant->registrationType?->name ?? ucfirst($participant->participant_type) }}
                                        </strong>
                                    </div>

                                    <div class="col-md-{{ $isPresenter ? '4' : '6' }} col-6 mb-2">
                                        <dt class="col-sm-5">
                                            Attendance Type
                                        </dt>

                                        <dd class="col-sm-7">
                                            {{ ucfirst($participant->attendance_type) }}
                                        </dd>

                                        @php
                                            $attendance = $participant->attendances->first();
                                        @endphp

                                        <dt class="col-sm-5">
                                            Conference Attendance
                                        </dt>

                                        <dd class="col-sm-7">

                                            @if (!$attendance || $attendance->attendance_status === 'not_checked_in')
                                                <div class="">

                                                    <span class="badge text-bg-secondary rounded-0">
                                                        Not Checked In
                                                    </span>

                                                    @if ($participant->registration_status === 'confirmed')
                                                        <div class="">
                                                            <form method="POST"
                                                                action="{{ route('participant.attendance.check-in', $participant) }}"
                                                                class="d-inline">
                                                                @csrf

                                                                <button type="submit"
                                                                    class="btn btn-primary btn-sm rounded-0">
                                                                    <i class="bi bi-box-arrow-in-right me-1"></i>
                                                                    Check In
                                                                </button>
                                                            </form>
                                                        </div>
                                                    @else
                                                        <div class="">
                                                            <small class="text-muted">
                                                                Check-in is available after registration is confirmed.
                                                            </small>
                                                        </div>
                                                    @endif

                                                </div>
                                            @elseif ($attendance->attendance_status === 'checked_in')
                                                <div>

                                                    <span class="badge text-bg-warning rounded-0">
                                                        Waiting for Verification
                                                    </span>

                                                    <small class="text-muted d-block mt-1">
                                                        Checked in at
                                                        {{ $attendance->checked_in_at?->format('d F Y H:i') ?? '-' }}
                                                    </small>

                                                </div>
                                            @elseif ($attendance->attendance_status === 'verified')
                                                <div>

                                                    <span class="badge text-bg-success rounded-0">
                                                        Verified
                                                    </span>

                                                    <small class="text-muted d-block mt-1">
                                                        Checked in at
                                                        {{ $attendance->checked_in_at?->format('d F Y H:i') ?? '-' }}
                                                    </small>

                                                    <small class="text-muted d-block">
                                                        Verified at
                                                        {{ $attendance->verified_at?->format('d F Y H:i') ?? '-' }}
                                                    </small>

                                                </div>
                                            @else
                                                <span class="badge text-bg-secondary rounded-0">
                                                    {{ ucfirst(str_replace('_', ' ', $attendance->attendance_status)) }}
                                                </span>
                                            @endif

                                        </dd>
                                    </div>

                                    @if ($isPresenter)
                                        <div class="col-md-{{ $isPresenter ? '4' : '6' }} col-6 mb-2">
                                            <small class="text-muted d-block">
                                                Submissions
                                            </small>

                                            <strong>
                                                {{ $participant->submissions->count() }}
                                            </strong>
                                        </div>
                                    @endif
                                </div>

                                @if ($isPresenter && $participant->submissions->isNotEmpty())
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

        @if ($hasPresenterRegistration)
            <div class="card rounded-0 overflow-hidden">
                <div class="card-header rounded-0">
                    <h3 class="card-title">
                        <i class="bi bi-file-earmark-check me-2"></i>
                        Recent Submissions
                    </h3>

                </div>

                <div class="card-body p-0">
                    @php
                        $recentSubmissions = $participants
                            ->flatMap(fn($participant) => $participant->submissions)
                            ->sortByDesc('created_at')
                            ->take(5);
                    @endphp

                    <div class="table-responsive rounded-0">
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
                                                No Submissions Found
                                            </h5>

                                            <p class="text-muted mb-3">
                                                You have not submitted a paper yet.
                                            </p>

                                            <a href="{{ route('participant.submissions.create') }}"
                                                class="btn btn-success rounded-0">
                                                <i class="bi bi-plus-circle me-1"></i>
                                                New Submission
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    @endif

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            document
                .querySelectorAll('[data-conference-countdown]')
                .forEach(function(element) {

                    const start = Number(element.dataset.start);
                    const end = Number(element.dataset.end);

                    const label =
                        element.querySelector('[data-countdown-label]');

                    const value =
                        element.querySelector('[data-countdown-value]');

                    function updateCountdown() {

                        const now =
                            Math.floor(Date.now() / 1000);

                        if (now < start) {

                            let remaining = start - now;

                            const days =
                                Math.floor(remaining / 86400);

                            remaining %= 86400;

                            const hours =
                                Math.floor(remaining / 3600);

                            remaining %= 3600;

                            const minutes =
                                Math.floor(remaining / 60);

                            const seconds =
                                remaining % 60;

                            label.textContent = 'Starts In';

                            value.textContent =
                                `${days}d ${hours}h ${minutes}m ${seconds}s`;

                        } else if (now <= end) {

                            label.textContent = 'Status';
                            value.textContent = 'Conference is Live';

                        } else {

                            label.textContent = 'Status';
                            value.textContent = 'Conference Ended';

                        }

                    }

                    updateCountdown();

                    setInterval(
                        updateCountdown,
                        1000
                    );

                });

        });
    </script>
@endpush
