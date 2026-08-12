@extends('layouts.admin')

@section('title', 'Submission Detail')

@section('header')
    <div class="row">
        <div class="col-sm-6 d-flex align-items-center gap-2">
            <a href="{{ route('admin.submissions.index') }}" class="btn btn-secondary btn-sm rounded-0">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h1 class="mb-0 fs-3">
                Submission Detail
            </h1>
        </div>
        <div class="col-sm-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.submissions.index') }}">Submission</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Detail</li>
                </ol>
            </nav>
        </div>
    </div>
@endsection

@section('content')
    <div class="card rounded-0">
        <div class="card-header">
            <h3 class="card-title">
                Submission Information
            </h3>
        </div>
        <div class="card-body">
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
                @else
                    <span class="badge text-bg-dark rounded-0">
                        Published
                    </span>
                @endif
            </div>
            <div class="row">
                <div class="col-lg-8">
                    <div class="mb-4">
                        <h4 class="fw-bold">{{ $submission->title }}</h4>
                    </div>
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
                <div class="col-lg-4">
                    <div class="border rounded-0 bg-light p-3">
                        <h6 class="fw-bold">
                            Paper File
                        </h6>
                        <p class="text-muted small">
                            Current manuscript file.
                        </p>
                        @if ($submission->paper_file)
                            <a href="{{ asset('storage/' . $submission->paper_file) }}" target="_blank"
                                class="btn btn-danger btn-sm rounded-0">
                                <i class="bi bi-file-earmark-pdf me-1"></i>
                                Open Paper
                            </a>
                        @else
                            <span class="text-muted">
                                No file available.
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body border-top">
            <h5 class="fw-bold mb-2">Abstract</h5>
            <div>{!! nl2br(e($submission->abstract)) !!}</div>
        </div>
        <div class="card-body border-top">
            <h5 class="fw-bold mb-2">Keywords</h5>
            <p>{{ $submission->keywords }}</p>
        </div>
        <div class="card-body border-top">
            <h5 class="fw-bold mb-2">Authors</h5>
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th width="40">No</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Institution</th>
                            <th>Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($submission->authors as $author)
                            <tr>
                                <td class="align-top">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="align-top">
                                    <strong>
                                        {{ $author->name }}
                                    </strong>
                                </td>
                                <td class="align-top">
                                    {{ $author->email ?: '—' }}
                                </td>
                                <td class="align-top">
                                    {{ $author->institution ?: '—' }}
                                    @if ($author->department)
                                        <small class="text-muted d-block">
                                            {{ $author->department }}
                                        </small>
                                    @endif
                                </td>
                                <td class="align-top">
                                    @if ($author->is_corresponding)
                                        <span class="badge text-bg-success rounded-0">
                                            Corresponding Author
                                        </span>
                                    @else
                                        <span class="badge text-bg-secondary rounded-0">
                                            Author
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    No authors available.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
