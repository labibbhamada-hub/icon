@extends('layouts.admin')

@section('title', 'Create Conference')

@section('header')
    <div class="row">
        <div class="col-sm-6 d-flex align-items-center gap-2">
            <a href="{{ route('admin.conferences.index') }}" class="btn btn-secondary btn-sm rounded-0">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h1 class="mb-0 fs-3">
                Create Conference
            </h1>
        </div>
        <div class="col-sm-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.conferences.index') }}">Conference</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Create</li>
                </ol>
            </nav>
        </div>
    </div>
@endsection

@section('content')
    <form action="{{ route('admin.conferences.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card rounded-0">
            <div class="card-header">
                <h3 class="card-title">
                    Form Conference
                </h3>
            </div>

            @include('admin.conferences._form')

            <div class="card-footer text-end">
                <button class="btn btn-success btn-sm rounded-0">
                    <i class="bi bi-check-circle"></i>
                    Save Conference
                </button>
            </div>
        </div>
    </form>
@endsection
