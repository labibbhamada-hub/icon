@extends('layouts.admin')

@section('title', 'Reviews')

@section('content')

    <div class="app-content-header">

        <div class="container-fluid">

            <div class="row align-items-center">

                <div class="col-sm-6">

                    <h1 class="mb-0 fs-3">
                        Reviews Management
                    </h1>

                </div>

                <div class="col-sm-6">

                    <ol class="breadcrumb float-sm-end mb-0">

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">
                                Dashboard
                            </a>
                        </li>

                        <li class="breadcrumb-item active">
                            Reviews
                        </li>

                    </ol>

                </div>

            </div>

        </div>

    </div>

    <div class="app-content">

        <div class="container-fluid">

            <div class="card">

                <div class="card-header">

                    <h3 class="card-title">
                        Reviews List
                    </h3>

                </div>

                <div class="card-body">

                    <div class="text-center py-5">

                        <i class="bi bi-file-earmark-check display-5 text-muted"></i>

                        <h5 class="mt-3">
                            Review Module
                        </h5>

                        <p class="text-muted mb-0">
                            Review management is under development.
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection
