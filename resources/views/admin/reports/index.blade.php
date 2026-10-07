@extends('layouts.admin')

@section('title', 'Reports')

@section('header')

    <div class="row align-items-center">
        <div class="col-sm-6">
            <h1 class="mb-0 fs-3 fw-bold">
                Reports </h1>
            <p class="text-muted mb-0 mt-1">
                Conference data summary and export center. </p>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end mb-0">
                <li class="breadcrumb-item"> <a href="{{ route('admin.dashboard') }}">
                        Dashboard </a> </li>
                <li class="breadcrumb-item active">
                    Reports </li>
            </ol>
        </div>
    </div>

@endsection

@section('content')

    <div class="card rounded-0 overflow-hidden mb-3">

        <div class="card-header rounded-0">

            <h3 class="card-title">
                <i class="bi bi-funnel me-2"></i>
                Report Filter
            </h3>

        </div>

        <div class="card-body">

            <form method="GET" action="{{ route('admin.reports.index') }}">

                <div class="row align-items-end">

                    <div class="col-md-6 mb-2">

                        <label class="form-label">
                            Conference
                        </label>

                        <select name="conference_id" class="form-select rounded-0" onchange="this.form.submit()">

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

                    <div class="col-md-6 mb-2">

                        <label class="form-label">
                            Topic
                        </label>

                        <select name="topic_id" class="form-select rounded-0">

                            <option value="">
                                All Topics
                            </option>

                            @foreach ($topics as $topic)
                                <option value="{{ $topic->id }}" @selected($topicId == $topic->id)>
                                    {{ $topic->name }}

                                    @if (!$conferenceId && $topic->conference)
                                        —
                                        {{ $topic->conference->short_name }}
                                        {{ $topic->conference->year }}
                                    @endif

                                </option>
                            @endforeach

                        </select>

                    </div>

                    <div class="col-12 mb-2 d-flex gap-2">

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


    <div class="card rounded-0 overflow-hidden mb-3">

        <div class="card-header rounded-0">

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


    <div class="card rounded-0 overflow-hidden mb-3">

        <div class="card-header rounded-0">

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


    <div class="card rounded-0 overflow-hidden mb-3">

        <div class="card-header rounded-0">

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

    <div class="card rounded-0 overflow-hidden mb-3">

        <div class="card-header rounded-0">

            <h3 class="card-title">
                <i class="bi bi-camera-video me-2"></i>
                Presenter Video Links
            </h3>

            <div class="float-end">

                <span class="badge text-bg-primary rounded-0">
                    {{ $presenterVideoSubmissions->count() }}
                    Video Submitted
                </span>

            </div>

        </div>

        <div class="card-body">

            @if ($topicId)

                @php
                    $selectedTopic = $topics->firstWhere('id', $topicId);
                @endphp

                @if ($selectedTopic)
                    <div class="alert alert-info rounded-0 mb-3">

                        <div class="d-flex align-items-center">

                            <i class="bi bi-diagram-3 me-2"></i>

                            <div>

                                <strong>
                                    Topic:
                                </strong>

                                {{ $selectedTopic->name }}

                            </div>

                        </div>

                    </div>
                @endif
            @else
                <div class="alert alert-secondary rounded-0 mb-3">

                    <i class="bi bi-info-circle me-2"></i>

                    Select a Topic from the filter above to view
                    presenter video submissions.

                </div>

            @endif

            @if ($presenterVideoSubmissions->isNotEmpty())

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle mb-0">

                        <thead>

                            <tr>

                                <th width="60">
                                    No
                                </th>

                                <th>
                                    Presenter
                                </th>

                                <th>
                                    Submission Code
                                </th>

                                <th>
                                    Paper Title
                                </th>

                                <th>
                                    Video Submitted
                                </th>

                                <th width="180">
                                    Action
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach ($presenterVideoSubmissions as $submission)
                                <tr>

                                    <td>
                                        {{ $loop->iteration }}
                                    </td>

                                    <td>

                                        <div class="fw-semibold">
                                            {{ $submission->participant?->full_name ?? '—' }}
                                        </div>

                                        @if ($submission->participant?->registration_number)
                                            <small class="text-muted">
                                                {{ $submission->participant->registration_number }}
                                            </small>
                                        @endif

                                    </td>

                                    <td>
                                        {{ $submission->submission_code }}
                                    </td>

                                    <td>
                                        {{ $submission->title }}
                                    </td>

                                    <td>

                                        @if ($submission->video_submitted_at)
                                            {{ $submission->video_submitted_at->format('d M Y, H:i') }}
                                        @else
                                            —
                                        @endif

                                    </td>

                                    <td>

                                        <div class="d-flex gap-1">

                                            <a href="{{ $submission->video_url }}" target="_blank"
                                                rel="noopener noreferrer" class="btn btn-primary btn-sm rounded-0">
                                                <i class="bi bi-box-arrow-up-right me-1"></i>
                                                Open
                                            </a>

                                            <button type="button" class="btn btn-secondary btn-sm rounded-0"
                                                onclick="copyVideoLink(@js($submission->video_url), this)">
                                                <i class="bi bi-copy me-1"></i>
                                                Copy
                                            </button>

                                        </div>

                                    </td>

                                </tr>
                            @endforeach

                        </tbody>

                    </table>

                </div>
            @else
                <div class="text-center py-5 text-muted">

                    <i class="bi bi-camera-video fs-1 d-block mb-3"></i>

                    @if ($topicId)
                        <h5>
                            No Presenter Video Submission Found
                        </h5>

                        <p class="mb-0">
                            No confirmed presenter has submitted a video
                            for the selected topic yet.
                        </p>
                    @else
                        <h5>
                            Select a Topic
                        </h5>

                        <p class="mb-0">
                            Choose a topic above to view presenter video links.
                        </p>
                    @endif

                </div>

            @endif

        </div>

    </div>

    <div class="card rounded-0 overflow-hidden mb-3">

        <div class="card-header rounded-0">

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


    <div class="card rounded-0 overflow-hidden mb-3">

        <div class="card-header rounded-0">

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

@push('scripts')
    <script>
        function copyVideoLink(url, button) {

            navigator.clipboard.writeText(url)
                .then(function() {

                    const originalHtml = button.innerHTML;

                    button.innerHTML =
                        '<i class="bi bi-check2 me-1"></i> Copied';

                    setTimeout(function() {
                        button.innerHTML = originalHtml;
                    }, 1500);

                })
                .catch(function() {

                    alert('Failed to copy video link.');

                });

        }
    </script>
@endpush
