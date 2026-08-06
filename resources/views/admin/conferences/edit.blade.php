@extends('layouts.admin')

@section('title', 'Edit Conference')

@section('content')
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">
                        Edit Conference
                    </h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">
                                Dashboard
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.conferences.index') }}">
                                Conference
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            Create
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="app-content">
        <div class="container-fluid">
            <form action="{{ route('admin.conferences.update', $conference) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            Create Conference
                        </h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.conferences.index') }}" class="btn btn-secondary btn-sm">
                                <i class="bi bi-arrow-left"></i>
                                Back
                            </a>
                            <button class="btn btn-warning btn-sm">
                                <i class="bi bi-check-circle"></i>
                                Update Conference
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        @include('admin.conferences._form')
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
