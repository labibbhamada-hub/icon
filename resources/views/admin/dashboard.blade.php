@extends('layouts.admin')

@section('title', 'Dashboard')

@section('header')
    <div class="row">
        <div class="col-sm-6">
            <h1 class="mb-0 fs-3 fw-bold">Dashboard</h1>
            <p class="text-muted mb-0">
                Welcome back,
                <strong>{{ Auth::user()->name ?? 'Administrator' }}</strong>
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
    <div class="row mb-2">
        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-primary rounded-0 mb-2">
                <div class="inner">
                    <h3>{{ $conferenceCount }}</h3>
                    <p>Conferences</p>
                </div>
                <i class="small-box-icon bi bi-calendar-event"></i>
                <a href="#"
                    class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                    Manage Conferences
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-success rounded-0 mb-2">
                <div class="inner">
                    <h3>{{ $topicCount }}</h3>
                    <p>Topics</p>
                </div>
                <i class="small-box-icon bi bi-diagram-3"></i>
                <a href="#"
                    class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                    Manage Topics
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-warning rounded-0 mb-2">
                <div class="inner">
                    <h3>0</h3>
                    <p>Speakers</p>
                </div>
                <i class="small-box-icon bi bi-mic"></i>
                <a href="#"
                    class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                    Coming Soon
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-info rounded-0 mb-2">
                <div class="inner">
                    <h3>{{ $participantCount }}</h3>
                    <p>Participants</p>
                </div>
                <i class="small-box-icon bi bi-people"></i>
                <a href="{{ route('admin.participants.index') }}"
                    class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                    Manage Participants
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="row mb-2">
        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-success rounded-0 mb-2">
                <div class="inner">
                    <h3>
                        {{ $confirmedParticipantCount }}
                    </h3>
                    <p>
                        Confirmed Participants
                    </p>
                </div>
                <i class="small-box-icon bi bi-person-check"></i>
                <a href="{{ route('admin.participants.index') }}"
                    class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                    View Participants
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-warning rounded-0 mb-2">
                <div class="inner">
                    <h3>
                        {{ $pendingPaymentCount }}
                    </h3>
                    <p>
                        Pending Payments
                    </p>
                </div>
                <i class="small-box-icon bi bi-credit-card"></i>
                <a href="{{ route('admin.payments.index') }}"
                    class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                    Verify Payments
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-info rounded-0 mb-2">
                <div class="inner">
                    <h3>
                        {{ $submissionCount }}
                    </h3>
                    <p>
                        Submissions
                    </p>
                </div>
                <i class="small-box-icon bi bi-file-earmark-text"></i>
                <a href="{{ route('admin.submissions.index') }}"
                    class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                    View Submissions
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-dark rounded-0 mb-2">
                <div class="inner">
                    <h3>
                        {{ $publishedCount }}
                    </h3>
                    <p>
                        Published Papers
                    </p>
                </div>
                <i class="small-box-icon bi bi-journal-check"></i>
                <a href="{{ route('admin.submissions.index') }}"
                    class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                    View Submissions
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="row mb-2">
        <div class="col-lg-3 col-md-6">
            <div class="card rounded-0 mb-2">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted d-block">
                                Under Review
                            </small>
                            <h3 class="mb-0">
                                {{ $underReviewCount }}
                            </h3>
                        </div>
                        <i class="bi bi-search fs-2 text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card rounded-0 mb-2">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted d-block">
                                Revision
                            </small>
                            <h3 class="mb-0">
                                {{ $revisionCount }}
                            </h3>
                        </div>
                        <i class="bi bi-arrow-repeat fs-2 text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card rounded-0 mb-2">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted d-block">
                                Accepted
                            </small>
                            <h3 class="mb-0">
                                {{ $acceptedCount }}
                            </h3>
                        </div>
                        <i class="bi bi-check-circle fs-2 text-success"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card rounded-0 mb-2">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted d-block">
                                Certificates
                            </small>
                            <h3 class="mb-0">
                                {{ $certificateCount }}
                            </h3>
                        </div>
                        <i class="bi bi-award fs-2 text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-2">
        <div class="col-lg-8">
            <div class="card rounded-0 mb-2">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-calendar-event me-2"></i>
                        Current Conference
                    </h3>
                    <div class="float-end">
                        <a href="{{ route('admin.conferences.index') }}"
                            class="btn btn-sm btn-outline-primary rounded-0">
                            View All
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if ($activeConference)
                        <div class="row">
                            <div class="col-md-8">
                                <h4 class="fw-bold mb-2">
                                    {{ $activeConference->name }}
                                </h4>
                                <p class="text-muted mb-3">
                                    {{ $activeConference->theme ?: 'No conference theme available.' }}
                                </p>
                                <small class="text-muted d-block">
                                    Date
                                </small>
                                <strong>
                                    {{ $activeConference->start_date->format('d M Y') }}
                                    -
                                    {{ $activeConference->end_date->format('d M Y') }}
                                </strong>
                                <small class="text-muted d-block">
                                    Location
                                </small>
                                <strong>
                                    {{ $activeConference->venue ?: '-' }}
                                    @if ($activeConference->city)
                                        , {{ $activeConference->city }}
                                    @endif
                                </strong>
                            </div>
                            <div class="col-md-4">
                                <div class="border rounded-0 p-3">
                                    <small class="text-muted d-block mb-2">
                                        Conference Status
                                    </small>
                                    @php
                                        $statusMap = [
                                            'draft' => 'secondary',
                                            'registration_open' => 'success',
                                            'submission_open' => 'primary',
                                            'review' => 'warning',
                                            'camera_ready' => 'info',
                                            'closed' => 'danger',
                                            'archived' => 'dark',
                                        ];
                                    @endphp
                                    <span
                                        class="badge text-bg-{{ $statusMap[$activeConference->status] ?? 'secondary' }} rounded-0">
                                        {{ ucwords(str_replace('_', ' ', $activeConference->status)) }}
                                    </span>
                                    @if ($activeConference->setting)
                                        <hr>
                                        <small class="text-muted d-block mb-2">
                                            Features
                                        </small>
                                        <div class="d-flex flex-column gap-2">
                                            <div>
                                                @if ($activeConference->setting->registration_enabled)
                                                    <i class="bi bi-check-circle-fill text-success me-1"></i>
                                                    Registration
                                                @else
                                                    <i class="bi bi-x-circle-fill text-danger me-1"></i>
                                                    Registration
                                                @endif
                                            </div>
                                            <div>
                                                @if ($activeConference->setting->submission_enabled)
                                                    <i class="bi bi-check-circle-fill text-success me-1"></i>
                                                    Submission
                                                @else
                                                    <i class="bi bi-x-circle-fill text-danger me-1"></i>
                                                    Submission
                                                @endif
                                            </div>
                                            <div>
                                                @if ($activeConference->setting->payment_enabled)
                                                    <i class="bi bi-check-circle-fill text-success me-1"></i>
                                                    Payment
                                                @else
                                                    <i class="bi bi-x-circle-fill text-danger me-1"></i>
                                                    Payment
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-calendar-x display-4 text-muted"></i>
                            <h5 class="mt-3">
                                No Conference Available
                            </h5>
                            <p class="text-muted">
                                Create a conference to start managing your event.
                            </p>
                            <a href="{{ route('admin.conferences.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-2"></i>
                                Create Conference
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-2">
            <div class="card rounded-0 mb-4">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-lightning-charge me-2"></i>
                        Quick Actions
                    </h3>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.conferences.create') }}" class="btn btn-primary rounded-0">
                            <i class="bi bi-calendar-plus me-2"></i>
                            Create Conference
                        </a>
                        <a href="{{ route('admin.topics.create') }}" class="btn btn-success rounded-0">
                            <i class="bi bi-plus-circle me-2"></i>
                            Add Topic
                        </a>
                        <a href="#" class="btn btn-outline-secondary rounded-0 disabled">
                            <i class="bi bi-mic me-2"></i>
                            Add Speaker
                        </a>
                        <a href="#" class="btn btn-outline-secondary rounded-0 disabled">
                            <i class="bi bi-building me-2"></i>
                            Add Partner
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-2">
        <div class="col-lg-7 mb-2">
            <div class="card rounded-0 mb-4">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-diagram-3 me-2"></i>
                        Recent Topics
                    </h3>
                    <div class="float-end">
                        <a href="{{ route('admin.topics.index') }}" class="btn btn-sm btn-outline-success rounded-0">
                            View All
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>
                                        Topic
                                    </th>
                                    <th>
                                        Conference
                                    </th>
                                    <th>
                                        Status
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($latestTopics as $topic)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if ($topic->icon)
                                                    <div class="me-2">
                                                        <i class="bi {{ $topic->icon }}"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <strong>
                                                        {{ $topic->name }}
                                                    </strong>
                                                    @if ($topic->description)
                                                        <small class="text-muted d-block">
                                                            {{ \Illuminate\Support\Str::limit($topic->description, 60) }}
                                                        </small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if ($topic->conference)
                                                {{ $topic->conference->short_name }}
                                                <small class="text-muted d-block">
                                                    {{ $topic->conference->year }}
                                                </small>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if ($topic->is_active)
                                                <span class="badge text-bg-success rounded-0">
                                                    Active
                                                </span>
                                            @else
                                                <span class="badge text-bg-secondary rounded-0">
                                                    Inactive
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-5 text-muted">
                                            <i class="bi bi-diagram-3 display-6 d-block mb-2"></i>
                                            No topics available.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5 mb-2">
            <div class="card rounded-0 mb-4">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-info-circle me-2"></i>
                        System Information
                    </h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <tbody>
                            <tr>
                                <td class="ps-3">
                                    Application
                                </td>
                                <td class="text-end pe-3 fw-semibold">
                                    ICON CMS
                                </td>
                            </tr>
                            <tr>
                                <td class="ps-3">
                                    Laravel
                                </td>
                                <td class="text-end pe-3">
                                    {{ app()->version() }}
                                </td>
                            </tr>
                            <tr>
                                <td class="ps-3">
                                    PHP
                                </td>
                                <td class="text-end pe-3">
                                    {{ PHP_VERSION }}
                                </td>
                            </tr>
                            <tr>
                                <td class="ps-3">
                                    Environment
                                </td>
                                <td class="text-end pe-3">
                                    @if (app()->environment('production'))
                                        <span class="badge text-bg-success rounded-0">
                                            Production
                                        </span>
                                    @else
                                        <span class="badge text-bg-warning rounded-0">
                                            {{ ucfirst(app()->environment()) }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="ps-3">
                                    Timezone
                                </td>
                                <td class="text-end pe-3">
                                    {{ config('app.timezone') }}
                                </td>
                            </tr>
                            <tr>
                                <td class="ps-3">
                                    Server Time
                                </td>
                                <td class="text-end pe-3">
                                    {{ now()->format('d M Y H:i') }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
