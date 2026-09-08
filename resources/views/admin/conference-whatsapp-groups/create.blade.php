@extends('layouts.admin')

@section('title', 'Create WhatsApp Group')

@section('header')
    <div class="row">
        <div class="col-sm-6">
            <h1 class="mb-0 fs-3">
                Create WhatsApp Group
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
                        <a href="{{ route('admin.conference-whatsapp-groups.index') }}">
                            WhatsApp Groups
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

    <div class="card rounded-0">

        <div class="card-header">
            <h3 class="card-title">
                <i class="bi bi-whatsapp me-2"></i>
                WhatsApp Group Information
            </h3>
        </div>

        <form action="{{ route('admin.conference-whatsapp-groups.store') }}" method="POST">

            @csrf

            @include('admin.conference-whatsapp-groups._form')

            <div class="card-footer">

                <a href="{{ route('admin.conference-whatsapp-groups.index') }}" class="btn btn-secondary rounded-0">
                    <i class="bi bi-arrow-left me-1"></i>
                    Back
                </a>

                <button type="submit" class="btn btn-success rounded-0">
                    <i class="bi bi-check-circle me-1"></i>
                    Save WhatsApp Group
                </button>

            </div>

        </form>

    </div>

@endsection
