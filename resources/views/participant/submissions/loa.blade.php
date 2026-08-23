@extends('layouts.participant')
@section('title', 'Letter of Acceptance')
@section('header')
    <div class="row align-items-center">
        <div class="col-sm-6">
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('participant.submissions.show', $submission) }}" class="btn btn-secondary btn-sm rounded-0">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h1 class="mb-0 fs-3">
                    Letter of Acceptance
                </h1>
            </div>
            <p class="text-muted mb-0">
                Official acceptance letter for your paper.
            </p>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('participant.dashboard') }}">
                        Dashboard
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('participant.submissions.index') }}">
                        My Submissions
                    </a>
                </li>
                <li class="breadcrumb-item active">
                    LOA
                </li>
            </ol>
        </div>
    </div>
@endsection
@section('content')
    <div class="card rounded-0 mb-3">
        <div class="card-header">
            <h3 class="card-title mb-0">
                <i class="bi bi-file-earmark-check me-2"></i>
                Letter of Acceptance
            </h3>
            <div class="float-end">
                <a href="{{ route('participant.submissions.loa.download', $submission) }}"
                    class="btn btn-primary btn-sm rounded-0">
                    <i class="bi bi-download me-1"></i>
                    Download LOA
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="text-center mb-4">
                @if ($submission->conference?->configuration?->logo)
                    <img src="{{ asset('storage/' . $submission->conference->configuration->logo) }}" alt="Conference Logo"
                        style="max-height: 80px;" class="mb-3">
                @endif
                <h2 class="fw-bold mb-1">
                    {{ $submission->conference?->name ?? 'ICON 2026' }}
                </h2>
                <h4 class="mb-0">
                    Letter of Acceptance
                </h4>
            </div>
            <div class="border rounded-0 p-3">
                <div class="row">
                    <div class="col-md-4 mb-2">
                        <small class="text-muted d-block">
                            Submission Code
                        </small>
                        <strong>
                            {{ $submission->submission_code }}
                        </strong>
                    </div>
                    <div class="col-md-4 mb-2">
                        <small class="text-muted d-block">
                            Status
                        </small>
                        <span class="badge bg-success rounded-0">
                            Accepted
                        </span>
                    </div>
                    <div class="col-md-4 mb-2">
                        <small class="text-muted d-block">
                            Date
                        </small>
                        <strong>
                            {{ optional($submission->updated_at)->format('d M Y') }}
                        </strong>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body border-top">
            <p>Dear Author(s),</p>
            <p>We are pleased to inform you that your paper entitled:</p>
            <div class="border-start border-primary border-3 ps-3 mb-3">
                <strong>
                    {{ $submission->title }}
                </strong>
            </div>
            <p>
                has been accepted for presentation at
                <strong>{{ $submission->conference?->name ?? 'ICON 2026' }}</strong>.
            </p>
            <p>
                The paper has passed the review process and is eligible to proceed to the next stage of the conference
                publication process.
            </p>
        </div>
        <div class="card-body border-top">
            <h5 class="fw-bold mb-2">Author(s)</h5>
            @if ($submission->authors->isNotEmpty())
                <ol>
                    @foreach ($submission->authors as $author)
                        <li>
                            <strong>{{ $author->name }}</strong>
                            @if ($author->institution)
                                — {{ $author->institution }}
                            @endif
                        </li>
                    @endforeach
                </ol>
            @else
                <p>
                    {{ $participant->user?->name ?? 'Author' }}
                </p>
            @endif
        </div>
        <div class="card-body border-top">
            <h5 class="fw-bold mb-2">Important Dates</h5>
            @if ($importantDates->isNotEmpty())
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>
                                    Event
                                </th>
                                <th>
                                    Date
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($importantDates as $importantDate)
                                <tr>
                                    <td>
                                        {{ $importantDate->title }}
                                    </td>
                                    <td>
                                        {{ $importantDate->date->format('d M Y') }}
                                        @if ($importantDate->end_date)
                                            -
                                            {{ $importantDate->end_date->format('d M Y') }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
        <div class="card-body border-top">
            <p>
                Please proceed with the required next steps through the ICON 2026 Participant Portal.
                We look forward to your participation in the conference.
            </p>
            <p class="mt-4 mb-0">
                Sincerely,
            </p>
            <p class="mb-1">
                <strong>
                    {{ $submission->conference?->configuration?->chair_name ?? 'Conference Chair' }}
                </strong>
            </p>
            @if ($submission->conference?->configuration?->chair_title)
                <p class="mb-0">
                    {{ $submission->conference->configuration->chair_title }}
                </p>
            @endif
            <p class="mb-0">
                {{ $submission->conference?->name ?? 'ICON 2026' }}
            </p>
        </div>
    </div>
@endsection
