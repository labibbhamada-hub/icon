@extends('layouts.admin')

@section('title', 'Edit Submission')

@section('header')
    <div class="row">
        <div class="col-sm-6 d-flex align-items-center gap-2">
            <a href="{{ route('admin.submissions.index') }}" class="btn btn-secondary btn-sm rounded-0">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h1 class="mb-0 fs-3">
                Edit Submission
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
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
        </div>
    </div>
@endsection

@section('content')
    <form action="{{ route('admin.submissions.update', $submission) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="card rounded-0">
            <div class="card-header">
                <h3 class="card-title">
                    Form Submission
                </h3>
            </div>

            @include('admin.submissions._form')

            <div class="card-footer text-end">
                <button class="btn btn-success btn-sm rounded-0">
                    <i class="bi bi-check-circle"></i>
                    Save Submission
                </button>
            </div>
        </div>
    </form>
@endsection
