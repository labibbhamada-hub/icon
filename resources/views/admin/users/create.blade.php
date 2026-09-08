@extends('layouts.admin')

@section('title', 'Create User')

@section('header')

    <div class="row">

        <div class="col-sm-6">

            <div class="d-flex align-items-center gap-2">

                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm rounded-0" title="Back">
                    <i class="bi bi-arrow-left"></i>
                </a>

                <div>

                    <h1 class="mb-0 fs-3">
                        Create User
                    </h1>

                    <p class="text-muted mb-0">
                        Create a new user account.
                    </p>

                </div>

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

                        <a href="{{ route('admin.users.index') }}">
                            Users
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

    <form action="{{ route('admin.users.store') }}" method="POST">

        @csrf

        <div class="card rounded-0">

            <div class="card-header">

                <h3 class="card-title">

                    <i class="bi bi-person-plus me-2"></i>

                    User Information

                </h3>

            </div>


            @include('admin.users._form')


            <div class="card-footer d-flex justify-content-end gap-2">

                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm rounded-0">
                    <i class="bi bi-x-circle me-1"></i>
                    Cancel
                </a>

                <button type="submit" class="btn btn-success btn-sm rounded-0">
                    <i class="bi bi-check-circle me-1"></i>
                    Create User
                </button>

            </div>

        </div>

    </form>

@endsection
