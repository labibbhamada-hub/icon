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
                View your submitted conference paper.
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

            <div class="card-tools">

                @if ($submission->paper_file)
                    <a href="{{ asset('storage/' . $submission->paper_file) }}" target="_blank"
                        class="btn btn-danger btn-sm">

                        <i class="bi bi-file-earmark-pdf me-1"></i>

                        Open Paper

                    </a>
                @endif

            </div>

        </div>


        <div class="card-body">

            <h3 class="fw-bold">
                {{ $submission->title }}
            </h3>


            <div class="mt-3">

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


            <table class="table table-borderless align-middle mt-4">

                <tbody>

                    <tr>

                        <th width="180">
                            Conference
                        </th>

                        <td>
                            {{ $submission->conference?->name ?? '—' }}
                        </td>

                    </tr>


                    <tr>

                        <th>
                            Topic
                        </th>

                        <td>
                            {{ $submission->topic?->name ?? '—' }}
                        </td>

                    </tr>


                    <tr>

                        <th>
                            Submitted At
                        </th>

                        <td>

                            {{ $submission->submitted_at?->format('d F Y H:i') ?? '—' }}

                        </td>

                    </tr>

                </tbody>

            </table>


            <div class="mt-4">

                <h5 class="fw-semibold border-bottom pb-2">
                    Abstract
                </h5>

                <div class="mt-3">

                    {!! nl2br(e($submission->abstract)) !!}

                </div>

            </div>


            <div class="mt-4">

                <h5 class="fw-semibold border-bottom pb-2">
                    Keywords
                </h5>

                <p class="mt-3">

                    {{ $submission->keywords }}

                </p>

            </div>


            <div class="mt-4">

                <h5 class="fw-semibold border-bottom pb-2">
                    Authors
                </h5>

                <ol class="mt-3">

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

    </div>
@endsection
