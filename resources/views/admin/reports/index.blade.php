@extends('layouts.admin')

@section('title', 'Reports')

@section('header')
    <div class="row align-items-center">
        <div class="col-sm-6">
            <h1 class="mb-0 fs-3 fw-bold">
                Reports
            </h1>
            <p class="text-muted mb-0 mt-1">
                Conference data summary and export center.
            </p>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.dashboard') }}">
                        Dashboard
                    </a>
                </li>
                <li class="breadcrumb-item active">
                    Reports
                </li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="card rounded-0 mb-3">
        <div class="card-header">
            <h3 class="card-title">
                <i class="bi bi-funnel me-2"></i>
                Report Filter
            </h3>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.reports.index') }}">
                <div class="row align-items-end">
                    <div class="col-md-8 mb-2">
                        <label class="form-label">
                            Conference
                        </label>
                        <select name="conference_id" class="form-select rounded-0">
                            <option value="">
                                All Conferences
                            </option>
                            @foreach ($conferences as $conference)
                                <option value="{{ $conference->id }}" @selected($conferenceId == $conference->id)>
                                    {{ $conference->short_name }}
                                    —
                                    {{ $conference->year }}
                                    —
                                    {{ $conference->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-2 gap-2 d-flex">
                        <button type="submit" class="btn btn-primary rounded-0">
                            <i class="bi bi-funnel me-1"></i>
                            Apply Filter
                        </button>
                        <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary rounded-0">
                            <i class="bi bi-arrow-counterclockwise me-1"></i>
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card rounded-0 mb-3">
        <div class="card-header">
            <h3 class="card-title">
                <i class="bi bi-people me-2"></i>
                Participants
            </h3>
            <div class="float-end">
                <a href="{{ route('admin.participants.export', ['conference_id' => $conferenceId]) }}"
                    class="btn btn-dark btn-sm rounded-0">
                    <i class="bi bi-file-earmark-excel me-1"></i>
                    Export Excel
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-2">
                    <div class="border rounded-0 p-3">
                        <small class="text-muted d-block">
                            Total Participants
                        </small>
                        <h3 class="mb-0">
                            {{ $statistics['participants'] }}
                        </h3>
                    </div>
                </div>
                <div class="col-md-6 mb-2">
                    <div class="border rounded-0 p-3">
                        <small class="text-muted d-block">
                            Confirmed
                        </small>
                        <h3 class="mb-0 text-success">
                            {{ $statistics['confirmed_participants'] }}
                        </h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card rounded-0 mb-3">
        <div class="card-header">
            <h3 class="card-title">
                <i class="bi bi-credit-card me-2"></i>
                Payments
            </h3>
            <div class="float-end">
                <a href="{{ route('admin.payments.export', ['conference_id' => $conferenceId]) }}"
                    class="btn btn-dark btn-sm rounded-0">
                    <i class="bi bi-file-earmark-excel me-1"></i>
                    Export Excel
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-2">
                    <div class="border rounded-0 p-3">
                        <small class="text-muted d-block">
                            Pending
                        </small>
                        <h3 class="mb-0 text-warning">
                            {{ $statistics['pending_payments'] }}
                        </h3>
                    </div>
                </div>
                <div class="col-md-6 mb-2">
                    <div class="border rounded-0 p-3">
                        <small class="text-muted d-block">
                            Verified
                        </small>
                        <h3 class="mb-0 text-success">
                            {{ $statistics['verified_payments'] }}
                        </h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card rounded-0 mb-3">
        <div class="card-header">
            <h3 class="card-title">
                <i class="bi bi-file-earmark-text me-2"></i>
                Submissions
            </h3>
            <div class="float-end">
                <a href="{{ route('admin.submissions.export', ['conference_id' => $conferenceId]) }}"
                    class="btn btn-dark btn-sm rounded-0">
                    <i class="bi bi-file-earmark-excel me-1"></i>
                    Export Excel
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-3 col-md-6 mb-2">
                    <div class="border rounded-0 p-3">
                        <small class="text-muted d-block">
                            Total
                        </small>
                        <h3 class="mb-0">
                            {{ $statistics['submissions'] }}
                        </h3>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-2">
                    <div class="border rounded-0 p-3">
                        <small class="text-muted d-block">
                            Under Review
                        </small>
                        <h3 class="mb-0 text-warning">
                            {{ $statistics['under_review'] }}
                        </h3>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-2">
                    <div class="border rounded-0 p-3">
                        <small class="text-muted d-block">
                            Revision
                        </small>
                        <h3 class="mb-0 text-warning">
                            {{ $statistics['revision'] }}
                        </h3>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-2">
                    <div class="border rounded-0 p-3">
                        <small class="text-muted d-block">
                            Published
                        </small>
                        <h3 class="mb-0 text-success">
                            {{ $statistics['published'] }}
                        </h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card rounded-0 mb-3">
        <div class="card-header">
            <h3 class="card-title">
                <i class="bi bi-clipboard-check me-2"></i>
                Reviews
            </h3>
            <div class="float-end">
                <a href="{{ route('admin.reviews.export', ['conference_id' => $conferenceId]) }}"
                    class="btn btn-dark btn-sm rounded-0">
                    <i class="bi bi-file-earmark-excel me-1"></i>
                    Export Excel
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-2">
                    <div class="border rounded-0 p-3">
                        <small class="text-muted d-block">
                            Total Review Records
                        </small>
                        <h3 class="mb-0">
                            {{ $statistics['reviews'] }}
                        </h3>
                    </div>
                </div>
                <div class="col-md-6 mb-2">
                    <div class="border rounded-0 p-3">
                        <small class="text-muted d-block">
                            Completed Reviews
                        </small>
                        <h3 class="mb-0 text-success">
                            {{ $statistics['completed_reviews'] }}
                        </h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card rounded-0 mb-3">
        <div class="card-header">
            <h3 class="card-title">
                <i class="bi bi-award me-2"></i>
                Certificates
            </h3>
            <div class="float-end">
                <a href="{{ route('admin.certificates.export', ['conference_id' => $conferenceId]) }}"
                    class="btn btn-dark btn-sm rounded-0">
                    <i class="bi bi-file-earmark-excel me-1"></i>
                    Export Excel
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="border rounded-0 p-3">
                <small class="text-muted d-block">
                    Total Certificates
                </small>
                <h3 class="mb-0">
                    {{ $statistics['certificates'] }}
                </h3>
            </div>
        </div>
    </div>
@endsection
