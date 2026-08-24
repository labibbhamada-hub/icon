@extends('layouts.admin')

@section('title', 'Create Registration Type')

@section('header')
    <div class="row">
        <div class="col-sm-6 d-flex align-items-center gap-2">
            <a href="{{ route('admin.registration-types.index') }}" class="btn btn-secondary btn-sm rounded-0">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h1 class="mb-0 fs-3">
                Create Registration Type
            </h1>
        </div>
        <div class="col-sm-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.registration-types.index') }}">Registration Types</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Create</li>
                </ol>
            </nav>
        </div>
    </div>
@endsection

@section('content')
    <form action="{{ route('admin.registration-types.store') }}" method="POST">
        @csrf
        <div class="card rounded-0">
            <div class="card-header">
                <h3 class="card-title">
                    Form Registration Type
                </h3>
            </div>
            @include('admin.registration-types._form')
            <div class="card-footer text-end">
                <button class="btn btn-success btn-sm rounded-0">
                    <i class="bi bi-check-circle"></i>
                    Save Registration Type
                </button>
            </div>
        </div>
    </form>
@endsection
