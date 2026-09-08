@extends('layouts.participant')

@section('title', 'Submission Detail')

@section('header')
    <div class="row align-items-top">
        <div class="col-sm-6">
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('participant.submissions.index') }}" class="btn btn-secondary btn-sm rounded-0">
                    <i class="bi bi-arrow-left"></i>
                </a>

                <h1 class="mb-0 fs-3">
                    Submission Detail
                </h1>
            </div>

            <p class="text-muted mb-0">
                View your conference submission details.
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
                    Detail
                </li>
            </ol>
        </div>
    </div>
@endsection

@section('content')

    <div class="card rounded-0">

        <div class="card-header">

            <h3 class="card-title">
                <i class="bi bi-file-earmark-text me-2"></i>
                {{ $submission->submission_code }}
            </h3>

            <div class="float-end">

                {{-- Original Paper --}}
                @if ($submission->paper_file)
                    <a href="{{ route('participant.submissions.paper.download', $submission) }}"
                        class="btn btn-outline-danger btn-sm rounded-0">
                        <i class="bi bi-file-earmark-pdf me-1"></i>
                        Full Paper
                    </a>
                @endif

                {{-- Revised File --}}
                @if ($submission->revised_file)
                    <a href="{{ route('participant.submissions.revision.download', $submission) }}"
                        class="btn btn-outline-warning btn-sm rounded-0">
                        <i class="bi bi-file-earmark-pdf me-1"></i>
                        Revised Paper
                    </a>
                @endif

                {{-- Camera Ready --}}
                @if ($submission->camera_ready_file)
                    <a href="{{ route('participant.submissions.camera-ready.download', $submission) }}"
                        class="btn btn-outline-success btn-sm rounded-0">
                        <i class="bi bi-file-earmark-pdf me-1"></i>
                        Camera Ready
                    </a>
                @endif

                {{-- LOA is only available for accepted FULL PAPER --}}
                @if (
                    $submission->submission_stage === 'full_paper' &&
                        in_array($submission->status, ['accepted', 'camera_ready', 'published'], true))
                    <a href="{{ route('participant.submissions.loa', $submission) }}"
                        class="btn btn-success btn-sm rounded-0">
                        <i class="bi bi-file-earmark-check me-1"></i>
                        View LOA
                    </a>
                @endif

            </div>
        </div>

        <div class="card-body">

            <h3 class="fw-bold mb-2">
                {{ $submission->title }}
            </h3>

            {{-- Stage --}}
            <div class="mb-2">

                @if ($submission->submission_stage === 'abstract')
                    <span class="badge text-bg-secondary rounded-0">
                        Abstract Stage
                    </span>
                @elseif ($submission->submission_stage === 'full_paper')
                    <span class="badge text-bg-primary rounded-0">
                        Full Paper Stage
                    </span>
                @endif

            </div>

            {{-- Status --}}
            <div class="mb-2">

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
                @endif

            </div>

        </div>

        {{-- Workflow Messages --}}
        @if (
            $submission->status === 'revision' ||
                ($submission->submission_stage === 'abstract' && $submission->status === 'accepted') ||
                ($submission->submission_stage === 'full_paper' &&
                    ($submission->status === 'accepted' ||
                        $submission->status === 'camera_ready' ||
                        $submission->status === 'published')))

            <div class="card-body border-top">

                {{-- Revision --}}
                @if ($submission->status === 'revision')
                    <div class="alert alert-warning rounded-0 mb-2">

                        <div class="d-flex justify-content-between align-items-center gap-3">

                            <div>

                                <strong>
                                    Revision Required
                                </strong>

                                <div class="small">
                                    Your
                                    {{ $submission->submission_stage === 'abstract' ? 'abstract' : 'paper' }}
                                    requires revision based on the review result.
                                </div>

                            </div>

                            <a href="{{ route('participant.submissions.revision', $submission) }}"
                                class="btn btn-warning btn-sm rounded-0">
                                <i class="bi bi-arrow-repeat me-1"></i>
                                Submit Revision
                            </a>

                        </div>

                    </div>
                @endif

                {{-- Abstract Accepted --}}
                @if ($submission->submission_stage === 'abstract' && $submission->status === 'accepted')
                    <div class="alert alert-success rounded-0 mb-2">

                        <div class="d-flex justify-content-between align-items-center gap-3">

                            <div>

                                <strong>
                                    Abstract Accepted
                                </strong>

                                <div class="small">
                                    Your abstract has been accepted. Please submit the full paper for the next review stage.
                                </div>

                            </div>

                            <a href="{{ route('participant.submissions.full-paper', $submission) }}"
                                class="btn btn-primary btn-sm rounded-0 text-nowrap">
                                <i class="bi bi-upload me-1"></i>
                                Submit Full Paper
                            </a>

                        </div>

                    </div>
                @endif

                {{-- Full Paper Accepted --}}
                @if ($submission->submission_stage === 'full_paper' && $submission->status === 'accepted')

                    @if (!$submission->presentation_completed)
                        <div class="alert alert-success rounded-0 mb-2">

                            <div class="d-flex justify-content-between align-items-center gap-3">

                                <div>

                                    <strong>
                                        Full Paper Accepted
                                    </strong>

                                    <div class="small">
                                        Your full paper has been accepted. Please complete your presentation details before
                                        proceeding to the next stage.
                                    </div>

                                </div>

                                <a href="{{ route('participant.submissions.presentation.edit', $submission) }}"
                                    class="btn btn-primary btn-sm rounded-0 text-nowrap">
                                    <i class="bi bi-easel2 me-1"></i>
                                    Presentation Details
                                </a>

                            </div>

                        </div>
                    @elseif (!$payment)
                        <div class="alert alert-warning rounded-0 mb-2">

                            <div class="d-flex justify-content-between align-items-center gap-3">

                                <div>

                                    <strong>
                                        Payment Required
                                    </strong>

                                    <div class="small">
                                        Your presentation details have been completed. Please proceed with payment to
                                        continue
                                        to the next stage.
                                    </div>

                                </div>

                                <a href="{{ route('participant.payments.create') }}"
                                    class="btn btn-warning btn-sm rounded-0 text-nowrap">
                                    <i class="bi bi-credit-card me-1"></i>
                                    Submit Payment
                                </a>

                            </div>

                        </div>
                    @elseif ($payment->status === 'pending')
                        <div class="alert alert-warning rounded-0 mb-2">

                            <div class="d-flex justify-content-between align-items-center gap-3">

                                <div>

                                    <strong>
                                        Payment Verification
                                    </strong>

                                    <div class="small">
                                        Your payment has been submitted and is currently waiting for administrative
                                        verification.
                                    </div>

                                </div>

                                <a href="{{ route('participant.payments.index') }}"
                                    class="btn btn-warning btn-sm rounded-0 text-nowrap">
                                    <i class="bi bi-credit-card me-1"></i>
                                    View Payment
                                </a>

                            </div>

                        </div>
                    @elseif ($payment->status === 'rejected')
                        <div class="alert alert-danger rounded-0 mb-2">

                            <div class="d-flex justify-content-between align-items-center gap-3">

                                <div>

                                    <strong>
                                        Payment Rejected
                                    </strong>

                                    <div class="small">
                                        Your payment was rejected. Please review the payment information and submit a new
                                        payment.
                                    </div>

                                </div>

                                <a href="{{ route('participant.payments.create') }}"
                                    class="btn btn-danger btn-sm rounded-0 text-nowrap">
                                    <i class="bi bi-credit-card me-1"></i>
                                    Submit Payment
                                </a>

                            </div>

                        </div>
                    @elseif ($payment->status === 'verified')
                        <div class="alert alert-success rounded-0 mb-2">

                            <div class="d-flex justify-content-between align-items-center gap-3">

                                <div>

                                    <strong>
                                        Payment Verified
                                    </strong>

                                    <div class="small">
                                        Your payment has been verified successfully. You may now submit your camera-ready
                                        paper.
                                    </div>

                                </div>

                                <a href="{{ route('participant.submissions.camera-ready', $submission) }}"
                                    class="btn btn-success btn-sm rounded-0 text-nowrap">
                                    <i class="bi bi-upload me-1"></i>
                                    Submit Camera Ready
                                </a>

                            </div>

                        </div>
                    @endif

                @endif

                {{-- Camera Ready --}}
                @if ($submission->submission_stage === 'full_paper' && $submission->status === 'camera_ready')
                    <div class="alert alert-info rounded-0 mb-2">

                        <i class="bi bi-hourglass-split me-2"></i>

                        Your camera-ready paper has been submitted and is currently
                        waiting for administrative approval.

                    </div>
                @endif

                {{-- Published --}}
                @if ($submission->submission_stage === 'full_paper' && $submission->status === 'published')
                    <div class="alert alert-success rounded-0 mb-2">

                        <i class="bi bi-check-circle me-2"></i>

                        Your paper has been approved and published successfully.

                    </div>
                @endif

            </div>

        @endif

        {{-- Basic Information --}}
        <div class="card-body border-top">

            <div class="row mb-2">

                <div class="col-md-4">
                    <strong>
                        Conference
                    </strong>
                </div>

                <div class="col-md-8">
                    {{ $submission->conference?->name ?? '—' }}
                </div>

            </div>

            <div class="row mb-2">

                <div class="col-md-4">
                    <strong>
                        Submission Stage
                    </strong>
                </div>

                <div class="col-md-8">

                    @if ($submission->submission_stage === 'abstract')
                        Abstract
                    @elseif ($submission->submission_stage === 'full_paper')
                        Full Paper
                    @else
                        —
                    @endif

                </div>

            </div>

            <div class="row mb-2">

                <div class="col-md-4">
                    <strong>
                        Topic
                    </strong>
                </div>

                <div class="col-md-8">
                    {{ $submission->topic?->name ?? '—' }}
                </div>

            </div>

            <div class="row mb-2">

                <div class="col-md-4">
                    <strong>
                        Submitted At
                    </strong>
                </div>

                <div class="col-md-8">
                    {{ $submission->submitted_at?->format('d F Y H:i') ?? '—' }}
                </div>

            </div>

        </div>

        {{-- Abstract --}}
        <div class="card-body border-top">

            <h5 class="fw-semibold mb-2">
                Abstract
            </h5>

            <div class="mb-2">
                {!! nl2br(e($submission->abstract)) !!}
            </div>

        </div>

        {{-- Keywords --}}
        <div class="card-body border-top">

            <h5 class="fw-semibold mb-2">
                Keywords
            </h5>

            <p class="mb-2">
                {{ $submission->keywords }}
            </p>

        </div>

        {{-- Authors --}}
        <div class="card-body border-top">

            <h5 class="fw-semibold mb-2">
                Authors
            </h5>

            <ol class="mb-2">

                @foreach ($submission->authors as $author)
                    <li class="mb-2">

                        <strong>
                            {{ $author->name }}
                        </strong>

                        @if ($author->is_corresponding)
                            <span class="badge text-bg-success rounded-0 ms-1">
                                Corresponding
                            </span>
                        @endif

                        @if ($author->institution)
                            <small class="text-muted d-block">
                                {{ $author->institution }}
                            </small>
                        @endif

                    </li>
                @endforeach

            </ol>

        </div>

    </div>

@endsection
