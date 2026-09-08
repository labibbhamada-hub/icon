@extends('layouts.admin')

@section('title', 'Submission Detail')

@section('header')

    <div class="row">

        <div class="col-sm-6 d-flex align-items-center gap-2">

            <a href="{{ route('admin.submissions.index') }}" class="btn btn-secondary btn-sm rounded-0" title="Back">
                <i class="bi bi-arrow-left"></i>
            </a>

            <div>

                <h1 class="mb-0 fs-3">
                    Submission Detail
                </h1>

                <p class="text-muted mb-0">
                    Review submission information and workflow status.
                </p>

            </div>

        </div>

        <div class="col-sm-6">

            <nav aria-label="breadcrumb">

                <ol class="breadcrumb float-sm-end mb-0">

                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">
                            Dashboard
                        </a>
                    </li>

                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.submissions.index') }}">
                            Submissions
                        </a>
                    </li>

                    <li class="breadcrumb-item active" aria-current="page">
                        Detail
                    </li>

                </ol>

            </nav>

        </div>

    </div>

@endsection


@section('content')

    @php

        $statusLabels = [
            'draft' => 'Draft',
            'submitted' => 'Submitted',
            'under_review' => 'Under Review',
            'revision' => 'Revision Required',
            'accepted' => 'Accepted',
            'rejected' => 'Rejected',
            'camera_ready' => 'Camera Ready',
            'published' => 'Published',
        ];

        $statusClasses = [
            'draft' => 'secondary',
            'submitted' => 'primary',
            'under_review' => 'warning',
            'revision' => 'warning',
            'accepted' => 'success',
            'rejected' => 'danger',
            'camera_ready' => 'info',
            'published' => 'dark',
        ];

        $statusLabel = $statusLabels[$submission->status] ?? ucfirst(str_replace('_', ' ', $submission->status));

        $statusClass = $statusClasses[$submission->status] ?? 'secondary';

    @endphp


    {{-- ============================================================
        SUBMISSION INFORMATION
    ============================================================= --}}

    <div class="card rounded-0 mb-3">

        <div class="card-header">

            <h3 class="card-title">
                <i class="bi bi-file-earmark-text me-2"></i>
                Submission Information
            </h3>

            @if ($submission->status !== 'published')
                <div class="float-end">

                    <a href="{{ route('admin.submissions.edit', $submission) }}" class="btn btn-warning btn-sm rounded-0">
                        <i class="bi bi-pencil me-1"></i>
                        Edit Submission
                    </a>

                </div>
            @endif

        </div>

        <div class="card-body">

            <div class="mb-3">

                <span class="badge text-bg-{{ $statusClass }} rounded-0">

                    @if ($submission->status === 'published')
                        <i class="bi bi-check-circle me-1"></i>
                    @elseif ($submission->status === 'accepted')
                        <i class="bi bi-check-circle me-1"></i>
                    @elseif ($submission->status === 'rejected')
                        <i class="bi bi-x-circle me-1"></i>
                    @elseif ($submission->status === 'camera_ready')
                        <i class="bi bi-file-earmark-check me-1"></i>
                    @else
                        <i class="bi bi-circle-fill me-1"></i>
                    @endif

                    {{ $statusLabel }}

                </span>

            </div>


            <div class="row">

                {{-- Main Information --}}
                <div class="col-lg-8">

                    <div class="mb-4">

                        <h4 class="fw-bold mb-0">
                            {{ $submission->title }}
                        </h4>

                    </div>


                    <div class="row mb-2">

                        <div class="col-md-4">
                            <strong>
                                Submission Code
                            </strong>
                        </div>

                        <div class="col-md-8">
                            {{ $submission->submission_code }}
                        </div>

                    </div>


                    <div class="row mb-2">

                        <div class="col-md-4">
                            <strong>
                                Conference
                            </strong>
                        </div>

                        <div class="col-md-8">

                            @if ($submission->conference)
                                <strong>
                                    {{ $submission->conference->name }}
                                </strong>

                                <small class="text-muted d-block">
                                    {{ $submission->conference->short_name }}
                                    ({{ $submission->conference->year }})
                                </small>
                            @else
                                <span class="text-muted">
                                    -
                                </span>
                            @endif

                        </div>

                    </div>


                    <div class="row mb-2">

                        <div class="col-md-4">
                            <strong>
                                Participant
                            </strong>
                        </div>

                        <div class="col-md-8">

                            @if ($submission->participant)
                                <strong>
                                    {{ $submission->participant->full_name }}
                                </strong>

                                <small class="text-muted d-block">
                                    {{ $submission->participant->registration_number }}
                                </small>
                            @else
                                <span class="text-muted">
                                    -
                                </span>
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

                            @if ($submission->topic)
                                {{ $submission->topic->name }}
                            @else
                                <span class="text-muted">
                                    -
                                </span>
                            @endif

                        </div>

                    </div>


                    <div class="row mb-2">

                        <div class="col-md-4">
                            <strong>
                                Presentation
                            </strong>
                        </div>

                        <div class="col-md-8">

                            @if ($submission->presentation_type)
                                <strong>
                                    {{ ucfirst($submission->presentation_type) }}
                                </strong>
                            @else
                                <span class="text-muted">
                                    Not set
                                </span>
                            @endif

                            @if ($submission->presentation_mode)
                                <small class="text-muted ms-2">
                                    {{ ucfirst($submission->presentation_mode) }}
                                </small>
                            @endif

                        </div>

                    </div>


                    <div class="row mb-2">

                        <div class="col-md-4">
                            <strong>
                                Submitted At
                            </strong>
                        </div>

                        <div class="col-md-8">

                            {{ $submission->submitted_at?->format('d F Y H:i') ?? '-' }}

                        </div>

                    </div>

                </div>


                {{-- Workflow --}}
                <div class="col-lg-4">

                    <div class="border rounded-0 bg-light p-3 h-100">

                        <h5 class="fw-bold mb-3">
                            Workflow Status
                        </h5>

                        @php

                            if ($submission->submission_stage === 'abstract') {
                                $workflowSteps = [
                                    'submitted' => 'Submitted',
                                    'under_review' => 'Under Review',
                                    'revision' => 'Revision',
                                    'accepted' => 'Accepted',
                                    'rejected' => 'Rejected',
                                ];

                                $workflowOrder = [
                                    'submitted' => 1,
                                    'under_review' => 2,
                                    'revision' => 3,
                                    'accepted' => 4,
                                    'rejected' => 5,
                                ];
                            } else {
                                $workflowSteps = [
                                    'submitted' => 'Submitted',
                                    'under_review' => 'Under Review',
                                    'revision' => 'Revision',
                                    'accepted' => 'Accepted',
                                    'camera_ready' => 'Camera Ready',
                                    'published' => 'Published',
                                ];

                                $workflowOrder = [
                                    'submitted' => 1,
                                    'under_review' => 2,
                                    'revision' => 3,
                                    'accepted' => 4,
                                    'camera_ready' => 5,
                                    'published' => 6,
                                ];
                            }

                            $currentStep = $workflowOrder[$submission->status] ?? 0;

                        @endphp

                        <div class="d-flex flex-column gap-2">

                            @foreach ($workflowSteps as $workflowStatus => $workflowLabel)
                                @php

                                    $stepNumber = $workflowOrder[$workflowStatus];

                                    $isCurrent = $workflowStatus === $submission->status;

                                    $isCompleted = $stepNumber < $currentStep;

                                @endphp

                                <div class="d-flex align-items-center gap-2">

                                    @if ($isCurrent)
                                        <span
                                            class="badge text-bg-primary rounded-circle d-flex align-items-center justify-content-center"
                                            style="width: 18px; height: 18px;">
                                            {{ $stepNumber }}
                                        </span>

                                        <strong>
                                            {{ $workflowLabel }}
                                        </strong>
                                    @elseif ($isCompleted)
                                        <span
                                            class="badge text-bg-success rounded-circle d-flex align-items-center justify-content-center"
                                            style="width: 18px; height: 18px;">
                                            <i class="bi bi-check"></i>
                                        </span>

                                        <span>
                                            {{ $workflowLabel }}
                                        </span>
                                    @else
                                        <span
                                            class="badge text-bg-light border rounded-circle d-flex align-items-center justify-content-center"
                                            style="width: 18px; height: 18px;">
                                            {{ $stepNumber }}
                                        </span>

                                        <span class="text-muted">
                                            {{ $workflowLabel }}
                                        </span>
                                    @endif

                                </div>
                            @endforeach

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
        MANUSCRIPT INFORMATION
    ============================================================= --}}

    <div class="card rounded-0 mb-3">

        <div class="card-header">

            <h3 class="card-title">
                <i class="bi bi-file-text me-2"></i>
                Manuscript Information
            </h3>

        </div>


        <div class="card-body">

            <h5 class="fw-bold mb-2">
                Abstract
            </h5>

            @if ($submission->abstract)
                <div>
                    {!! nl2br(e($submission->abstract)) !!}
                </div>
            @else
                <p class="text-muted mb-0">
                    No abstract available.
                </p>
            @endif

        </div>


        <div class="card-body border-top">

            <h5 class="fw-bold mb-2">
                Keywords
            </h5>

            @if ($submission->keywords)

                <div class="d-flex flex-wrap gap-1">

                    @foreach (preg_split('/[,;]+/', $submission->keywords) as $keyword)
                        @if (trim($keyword))
                            <span class="badge text-bg-light border rounded-0">
                                {{ trim($keyword) }}
                            </span>
                        @endif
                    @endforeach

                </div>
            @else
                <p class="text-muted mb-0">
                    No keywords available.
                </p>

            @endif

        </div>

    </div>


    {{-- ============================================================
        AUTHORS
    ============================================================= --}}

    <div class="card rounded-0 mb-3">

        <div class="card-header">

            <h3 class="card-title">
                <i class="bi bi-people me-2"></i>
                Authors
            </h3>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead>

                        <tr>

                            <th width="50">
                                No
                            </th>

                            <th>
                                Author
                            </th>

                            <th>
                                Email
                            </th>

                            <th>
                                Institution
                            </th>

                            <th>
                                Department
                            </th>

                            <th>
                                Role
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse ($submission->authors as $author)
                            <tr>

                                <td>
                                    {{ $loop->iteration }}
                                </td>

                                <td>
                                    <strong>
                                        {{ $author->name }}
                                    </strong>
                                </td>

                                <td>

                                    @if ($author->email)
                                        <a href="mailto:{{ $author->email }}">
                                            {{ $author->email }}
                                        </a>
                                    @else
                                        <span class="text-muted">
                                            -
                                        </span>
                                    @endif

                                </td>

                                <td>
                                    {{ $author->institution ?: '-' }}
                                </td>

                                <td>
                                    {{ $author->department ?: '-' }}
                                </td>

                                <td>

                                    @if ($author->is_corresponding)
                                        <span class="badge text-bg-success rounded-0">
                                            Corresponding Author
                                        </span>
                                    @else
                                        <span class="badge text-bg-secondary rounded-0">
                                            Author
                                        </span>
                                    @endif


                                    @if ($submission->presenter_author_id == $author->id)
                                        <span class="badge text-bg-primary rounded-0">
                                            Presenter
                                        </span>
                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="6" class="text-center text-muted py-4">
                                    No authors available.
                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- ============================================================
        REVIEWERS
    ============================================================= --}}

    <div class="card rounded-0 mb-3">

        <div class="card-header">

            <h3 class="card-title">
                <i class="bi bi-clipboard-check me-2"></i>
                Reviewers
            </h3>

            <div class="float-end">

                <a href="{{ route('admin.submissions.reviews.create', $submission) }}"
                    class="btn btn-success btn-sm rounded-0">
                    <i class="bi bi-person-plus me-1"></i>
                    Assign Reviewer
                </a>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead>

                        <tr>

                            <th>
                                Reviewer
                            </th>

                            <th>
                                Stage
                            </th>

                            <th>
                                Round
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Score
                            </th>

                            <th>
                                Recommendation
                            </th>

                            <th width="80">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse ($submission->reviews as $review)
                            <tr>

                                <td>

                                    @if ($review->reviewer?->user)
                                        <strong>
                                            {{ $review->reviewer->user->name }}
                                        </strong>

                                        <small class="text-muted d-block">
                                            {{ $review->reviewer->institution ?: $review->reviewer->user->email }}
                                        </small>
                                    @else
                                        <span class="text-muted">
                                            -
                                        </span>
                                    @endif

                                </td>

                                <td>
                                    @if ($review->review_stage === 'abstract')
                                        <span class="badge text-bg-secondary rounded-0">
                                            Abstract
                                        </span>
                                    @elseif ($review->review_stage === 'full_paper')
                                        <span class="badge text-bg-primary rounded-0">
                                            Full Paper
                                        </span>
                                    @else
                                        <span class="badge text-bg-light border rounded-0">
                                            {{ ucfirst(str_replace('_', ' ', $review->review_stage)) }}
                                        </span>
                                    @endif
                                </td>

                                <td>

                                    <span class="badge text-bg-secondary rounded-0">
                                        Round {{ $review->review_round }}
                                    </span>

                                </td>


                                <td>

                                    @if ($review->reviewed_at)
                                        <span class="badge text-bg-success rounded-0">
                                            Completed
                                        </span>
                                    @else
                                        <span class="badge text-bg-warning rounded-0">
                                            Pending
                                        </span>
                                    @endif

                                </td>


                                <td>

                                    {{ $review->score ?? '-' }}

                                </td>


                                <td>

                                    @if ($review->recommendation)
                                        {{ ucwords(str_replace('_', ' ', $review->recommendation)) }}
                                    @else
                                        <span class="text-muted">
                                            -
                                        </span>
                                    @endif

                                </td>


                                <td>

                                    <div class="btn-group gap-1">

                                        @if ($review->reviewed_at)
                                            <a href="{{ route('admin.reviews.show', $review) }}"
                                                class="btn btn-info btn-sm rounded-0" title="View Review">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        @else
                                            <a href="{{ route('admin.reviews.edit', $review) }}"
                                                class="btn btn-primary btn-sm rounded-0" title="Review">
                                                <i class="bi bi-clipboard-check"></i>
                                            </a>

                                            <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST"
                                                class="d-inline delete-review-form">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="btn btn-danger btn-sm rounded-0"
                                                    title="Remove Reviewer">
                                                    <i class="bi bi-person-dash"></i>
                                                </button>

                                            </form>
                                        @endif

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="7" class="text-center py-4">

                                    <i class="bi bi-person-x display-6 text-muted"></i>

                                    <div class="text-muted mt-2">
                                        No reviewers assigned yet.
                                    </div>

                                    <a href="{{ route('admin.submissions.reviews.create', $submission) }}"
                                        class="btn btn-success btn-sm rounded-0 mt-3">
                                        <i class="bi bi-person-plus me-1"></i>
                                        Assign First Reviewer
                                    </a>

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- ============================================================
        SUBMISSION FILES 
    ============================================================= --}}

    <div class="card rounded-0 mb-3">

        <div class="card-header">

            <h3 class="card-title">
                <i class="bi bi-files me-2"></i>
                Submission Files
            </h3>

        </div>


        <div class="card-body">

            <div class="row g-3">

                {{-- Original Paper --}}
                <div class="col-md-4">

                    <div class="border rounded-0 p-3 h-100">

                        <small class="text-muted d-block">
                            Original Paper
                        </small>

                        <strong class="d-block mb-3">
                            Manuscript
                        </strong>

                        @if ($submission->paper_file)
                            <div class="alert alert-light border rounded-0 mb-3">

                                <i class="bi bi-file-earmark-pdf text-danger me-2"></i>

                                Original paper is available.

                            </div>

                            <a href="{{ route('admin.submissions.paper.download', $submission) }}"
                                class="btn btn-outline-danger btn-sm rounded-0">
                                <i class="bi bi-download me-1"></i>
                                Download Paper
                            </a>
                        @else
                            <span class="text-muted">
                                No file available.
                            </span>
                        @endif

                    </div>

                </div>


                {{-- Revised Paper --}}
                <div class="col-md-4">

                    <div class="border rounded-0 p-3 h-100">

                        <small class="text-muted d-block">
                            Revised Paper
                        </small>

                        <strong class="d-block mb-3">
                            Revision Manuscript
                        </strong>

                        @if ($submission->revised_file)
                            <div class="alert alert-light border rounded-0 mb-3">

                                <i class="bi bi-file-earmark-pdf text-warning me-2"></i>

                                Revised paper is available.

                            </div>

                            <a href="{{ route('admin.submissions.revised-paper.download', $submission) }}"
                                class="btn btn-outline-warning btn-sm rounded-0">
                                <i class="bi bi-download me-1"></i>
                                Download Revised Paper
                            </a>
                        @else
                            <span class="text-muted">
                                No revised file.
                            </span>
                        @endif

                    </div>

                </div>


                {{-- Camera Ready --}}
                <div class="col-md-4">

                    <div class="border rounded-0 p-3 h-100">

                        <small class="text-muted d-block">
                            Camera Ready
                        </small>

                        <strong class="d-block mb-3">
                            Final Paper
                        </strong>

                        @if ($submission->camera_ready_file)
                            <div class="alert alert-light border rounded-0 mb-3">

                                <i class="bi bi-file-earmark-check text-info me-2"></i>

                                Camera-ready paper is available.

                            </div>

                            <a href="{{ route('admin.submissions.camera-ready.download', $submission) }}" target="_blank"
                                class="btn btn-outline-info btn-sm rounded-0">
                                <i class="bi bi-file-earmark-pdf me-1"></i>
                                Open Camera Ready
                            </a>
                        @else
                            <span class="text-muted">
                                No camera-ready file.
                            </span>
                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
        CAMERA READY APPROVAL
    ============================================================= --}}

    @if ($submission->status === 'camera_ready')

        <div class="card rounded-0 mb-3">

            <div class="card-header">

                <h3 class="card-title">
                    <i class="bi bi-file-earmark-check me-2"></i>
                    Camera-Ready Approval
                </h3>

            </div>


            <div class="card-body">

                <div class="alert alert-info rounded-0">

                    <i class="bi bi-info-circle me-2"></i>

                    The participant has uploaded the final
                    camera-ready paper. Please review the file
                    before publishing the submission.

                </div>


                <div class="row g-3">

                    <div class="col-md-6">

                        <div class="border rounded-0 p-3 h-100">

                            <small class="text-muted d-block">
                                Camera-Ready File
                            </small>

                            <strong class="d-block mt-1">
                                Final Manuscript
                            </strong>

                            @if ($submission->camera_ready_file)
                                <a href="{{ route('admin.submissions.camera-ready.download', $submission) }}"
                                    target="_blank" class="btn btn-outline-danger btn-sm rounded-0 mt-3">
                                    <i class="bi bi-file-earmark-pdf me-1"></i>
                                    Open Camera-Ready PDF
                                </a>
                            @endif

                        </div>

                    </div>


                    <div class="col-md-6">

                        <div class="border rounded-0 p-3 h-100">

                            <small class="text-muted d-block">
                                Approval
                            </small>

                            <div class="d-flex flex-wrap gap-2 mt-3">

                                <form action="{{ route('admin.submissions.camera-ready.approve', $submission) }}"
                                    method="POST" class="approve-camera-ready-form">

                                    @csrf
                                    @method('PATCH')

                                    <button type="submit" class="btn btn-success btn-sm rounded-0">
                                        <i class="bi bi-check-circle me-1"></i>
                                        Approve & Publish
                                    </button>

                                </form>


                                <form action="{{ route('admin.submissions.camera-ready.correction', $submission) }}"
                                    method="POST" class="correction-camera-ready-form">

                                    @csrf
                                    @method('PATCH')

                                    <input type="hidden" name="correction_reason" class="correction-reason-input">

                                    <button type="submit" class="btn btn-warning btn-sm rounded-0">
                                        <i class="bi bi-arrow-repeat me-1"></i>
                                        Request Correction
                                    </button>

                                </form>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    @endif


    {{-- ============================================================
        CAMERA READY CORRECTION REASON
    ============================================================= --}}

    @if ($submission->camera_ready_correction_reason && $submission->status === 'accepted')
        <div class="card rounded-0 mb-3">

            <div class="card-header">

                <h3 class="card-title">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Camera-Ready Correction Reason
                </h3>

            </div>

            <div class="card-body">

                <div class="alert alert-warning rounded-0 mb-0">

                    {!! nl2br(e($submission->camera_ready_correction_reason)) !!}

                </div>

            </div>

        </div>
    @endif

@endsection


@push('scripts')
    <script>
        /*
                            |--------------------------------------------------------------------------
                            | Remove Reviewer
                            |--------------------------------------------------------------------------
                            */

        document
            .querySelectorAll('.delete-review-form')
            .forEach(function(form) {

                form.addEventListener('submit', function(event) {

                    event.preventDefault();

                    Swal.fire({
                        title: 'Remove Reviewer?',
                        text: 'This will remove the reviewer assignment from this submission.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, remove',
                        cancelButtonText: 'Cancel',
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                    }).then(function(result) {

                        if (result.isConfirmed) {
                            form.submit();
                        }

                    });

                });

            });


        /*
        |--------------------------------------------------------------------------
        | Approve Camera Ready
        |--------------------------------------------------------------------------
        */

        document
            .querySelectorAll('.approve-camera-ready-form')
            .forEach(function(form) {

                form.addEventListener('submit', function(event) {

                    event.preventDefault();

                    Swal.fire({
                        title: 'Approve Camera-Ready Paper?',
                        text: 'The submission will be marked as Published and the presenter certificate will be generated.',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, approve',
                        cancelButtonText: 'Cancel',
                        confirmButtonColor: '#198754',
                        cancelButtonColor: '#6c757d',
                    }).then(function(result) {

                        if (result.isConfirmed) {
                            form.submit();
                        }

                    });

                });

            });


        /*
        |--------------------------------------------------------------------------
        | Camera Ready Correction
        |--------------------------------------------------------------------------
        */

        document
            .querySelectorAll('.correction-camera-ready-form')
            .forEach(function(form) {

                form.addEventListener('submit', function(event) {

                    event.preventDefault();

                    Swal.fire({
                        title: 'Request Camera-Ready Correction',
                        input: 'textarea',
                        inputLabel: 'Correction Reason',
                        inputPlaceholder: 'Explain what the participant needs to correct...',
                        inputAttributes: {
                            'aria-label': 'Correction Reason',
                        },
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Send Correction Request',
                        cancelButtonText: 'Cancel',
                        confirmButtonColor: '#f0ad4e',
                        cancelButtonColor: '#6c757d',
                        inputValidator: function(value) {

                            if (!value || !value.trim()) {
                                return 'Correction reason is required.';
                            }

                        },
                    }).then(function(result) {

                        if (result.isConfirmed) {

                            form
                                .querySelector('.correction-reason-input')
                                .value = result.value.trim();

                            form.submit();

                        }

                    });

                });

            });
    </script>
@endpush
