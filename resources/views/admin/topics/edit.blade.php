@extends('layouts.admin')

@section('title', 'Edit Topic')

@section('header')

    <div class="row">

        <div class="col-sm-6 d-flex align-items-center gap-2">

            <a href="{{ route('admin.topics.index') }}" class="btn btn-secondary btn-sm rounded-0" title="Back">
                <i class="bi bi-arrow-left"></i>
            </a>

            <h1 class="mb-0 fs-3">
                Edit Topic
            </h1>

        </div>

        <div class="col-sm-6">

            <nav aria-label="breadcrumb">

                <ol class="breadcrumb float-sm-end">

                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">
                            Dashboard
                        </a>
                    </li>

                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.topics.index') }}">
                            Topics
                        </a>
                    </li>

                    <li class="breadcrumb-item active" aria-current="page">
                        Edit
                    </li>

                </ol>

            </nav>

        </div>

    </div>

@endsection

@section('content')

    <form action="{{ route('admin.topics.update', $topic) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card rounded-0">

            <div class="card-header">

                <h3 class="card-title">
                    <i class="bi bi-pencil-square me-2"></i>
                    Topic Information
                </h3>

            </div>

            @include('admin.topics._form')

            <div class="card-footer d-flex justify-content-end gap-2">

                <a href="{{ route('admin.topics.index') }}" class="btn btn-secondary btn-sm rounded-0">
                    <i class="bi bi-x-circle me-1"></i>
                    Cancel
                </a>

                <button type="submit" class="btn btn-success btn-sm rounded-0">
                    <i class="bi bi-check-circle me-1"></i>
                    Save Changes
                </button>

            </div>

        </div>

    </form>

@endsection
