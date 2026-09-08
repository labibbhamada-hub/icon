@extends('layouts.admin')

@section('title', 'Create Submission')

@section('header')

    <div class="row">

        <div class="col-sm-6 d-flex align-items-center gap-2">

            <a href="{{ route('admin.submissions.index') }}" class="btn btn-secondary btn-sm rounded-0" title="Back">
                <i class="bi bi-arrow-left"></i>
            </a>

            <h1 class="mb-0 fs-3">
                Create Submission
            </h1>

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
                        Create
                    </li>

                </ol>

            </nav>

        </div>

    </div>

@endsection


@section('content')

    <form action="{{ route('admin.submissions.store') }}" method="POST" enctype="multipart/form-data">

        @csrf

        <div class="card rounded-0">

            <div class="card-header">

                <h3 class="card-title">
                    <i class="bi bi-file-earmark-plus me-2"></i>
                    Submission Information
                </h3>

            </div>

            @include('admin.submissions._form')

            <div class="card-footer d-flex justify-content-end gap-2">

                <a href="{{ route('admin.submissions.index') }}" class="btn btn-secondary btn-sm rounded-0">
                    <i class="bi bi-x-circle me-1"></i>
                    Cancel
                </a>

                <button type="submit" class="btn btn-success btn-sm rounded-0">
                    <i class="bi bi-check-circle me-1"></i>
                    Save Submission
                </button>

            </div>

        </div>

    </form>

@endsection
