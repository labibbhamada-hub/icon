@extends('layouts.reviewer')

@section('title', 'Review Detail')

@section('content')

    <div class="app-content-header">

        <div class="container-fluid">

            <div class="row align-items-center">

                <div class="col-sm-6">

                    <div class="d-flex align-items-center gap-2">

                        <a href="{{ route('reviewer.reviews.index') }}" class="btn btn-secondary btn-sm rounded-0">

                            <i class="bi bi-arrow-left"></i>

                        </a>

                        <h1 class="mb-0 fs-3">
                            Review Detail
                        </h1>

                    </div>

                    <p class="text-muted mb-0 mt-1">
                        View your submitted review.
                    </p>

                </div>

                <div class="col-sm-6">

                    <ol class="breadcrumb float-sm-end mb-0">

                        <li class="breadcrumb-item">

                            <a href="{{ route('reviewer.dashboard') }}">
                                Dashboard
                            </a>

                        </li>

                        <li class="breadcrumb-item">

                            <a href="{{ route('reviewer.reviews.index') }}">
                                My Reviews
                            </a>

                        </li>

                        <li class="breadcrumb-item active">
                            Detail
                        </li>

                    </ol>

                </div>

            </div>

        </div>

    </div>


    <div class="app-content">

        <div class="container-fluid">

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">

                    <i class="bi bi-check-circle me-2"></i>

                    {{ session('success') }}

                    <button type="button" class="btn-close" data-bs-dismiss="alert">
                    </button>

                </div>
            @endif


            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show">

                    <i class="bi bi-exclamation-circle me-2"></i>

                    {{ session('error') }}

                    <button type="button" class="btn-close" data-bs-dismiss="alert">
                    </button>

                </div>
            @endif


            <div class="row">

                <div class="col-lg-8">

                    <div class="card rounded-0">

                        <div class="card-header">

                            <h3 class="card-title">

                                <i class="bi bi-file-earmark-text me-2"></i>

                                {{ $review->submission->submission_code }}

                            </h3>

                            <div class="card-tools">

                                @if (!$review->reviewed_at)
                                    <a href="{{ route('reviewer.reviews.edit', $review) }}"
                                        class="btn btn-primary btn-sm">

                                        <i class="bi bi-pencil me-1"></i>

                                        Continue Review

                                    </a>
                                @else
                                    <span class="badge text-bg-success">

                                        Completed

                                    </span>
                                @endif

                            </div>

                        </div>


                        <div class="card-body">

                            <h4 class="fw-bold">

                                {{ $review->submission->title }}

                            </h4>


                            <div class="mt-3">

                                <span class="badge text-bg-primary rounded-0">

                                    {{ $review->submission->topic?->name ?? 'No Topic' }}

                                </span>

                            </div>


                            <div class="row mt-4 g-3">

                                <div class="col-md-4">

                                    <div class="border rounded-0 p-3 text-center">

                                        <small class="text-muted d-block">
                                            Score
                                        </small>

                                        <div class="display-6 fw-bold mt-2">

                                            {{ $review->score ?? '—' }}

                                        </div>

                                        @if ($review->score !== null)
                                            <small class="text-muted">
                                                / 100
                                            </small>
                                        @endif

                                    </div>

                                </div>


                                <div class="col-md-8">

                                    <div class="border rounded-0 p-3 h-100">

                                        <small class="text-muted d-block mb-2">
                                            Recommendation
                                        </small>

                                        @if ($review->recommendation === 'accept')
                                            <span class="badge text-bg-success rounded-0 fs-6">
                                                Accept
                                            </span>
                                        @elseif ($review->recommendation === 'minor_revision')
                                            <span class="badge text-bg-warning rounded-0 fs-6">
                                                Minor Revision
                                            </span>
                                        @elseif ($review->recommendation === 'major_revision')
                                            <span class="badge text-bg-warning rounded-0 fs-6">
                                                Major Revision
                                            </span>
                                        @elseif ($review->recommendation === 'reject')
                                            <span class="badge text-bg-danger rounded-0 fs-6">
                                                Reject
                                            </span>
                                        @else
                                            <span class="badge text-bg-secondary rounded-0 fs-6">
                                                Pending
                                            </span>
                                        @endif

                                    </div>

                                </div>

                            </div>


                            <div class="mt-4">

                                <h5 class="fw-semibold border-bottom pb-2">

                                    Review Comment

                                </h5>

                                @if ($review->comment)
                                    <div class="mt-3">

                                        {!! nl2br(e($review->comment)) !!}

                                    </div>
                                @else
                                    <p class="text-muted mt-3 mb-0">

                                        Your review has not been submitted yet.

                                    </p>
                                @endif

                            </div>

                        </div>


                        @if ($review->submission?->paper_file)
                            <div class="card-footer">

                                <a href="{{ asset('storage/' . $review->submission->paper_file) }}"
                                    target="_blank" class="btn btn-danger">

                                    <i class="bi bi-file-earmark-pdf me-1"></i>

                                    Open Paper

                                </a>

                            </div>
                        @endif

                    </div>

                </div>


                <div class="col-lg-4">

                    <div class="card rounded-0">

                        <div class="card-header">

                            <h3 class="card-title">

                                <i class="bi bi-info-circle me-2"></i>

                                Paper Information

                            </h3>

                        </div>

                        <div class="card-body">

                            <table class="table table-borderless mb-0">

                                <tbody>

                                    <tr>

                                        <th>
                                            Conference
                                        </th>

                                        <td>

                                            {{ $review->submission?->conference?->short_name ?? '—' }}

                                        </td>

                                    </tr>

                                    <tr>

                                        <th>
                                            Authors
                                        </th>

                                        <td>

                                            {{ $review->submission?->authors?->count() ?? 0 }}

                                        </td>

                                    </tr>

                                    <tr>

                                        <th>
                                            Reviewed At
                                        </th>

                                        <td>

                                            {{ $review->reviewed_at?->format('d M Y H:i') ?? '—' }}

                                        </td>

                                    </tr>

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection
