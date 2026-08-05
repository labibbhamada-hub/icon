@extends('layouts.admin')

@section('title', 'Create Conference')

@section('content')
    <div class="app-content">
        <div class="container-fluid">
            <form action="{{ route('admin.conference.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="page-header">
                    <div class="page-header-left">
                        <div class="page-header-icon">
                            <i class="bi bi-calendar-event"></i>
                        </div>
                        <div>
                            <h2 class="page-header-title">
                                Create Conference
                            </h2>
                            <p class="page-header-description">
                                Create and manage conference information for ICON 2026.
                            </p>
                        </div>
                    </div>
                    <div class="page-header-action">
                        <a href="{{ route('admin.conference.index') }}" class="btn btn-outline-success">
                            <i class="bi bi-arrow-left me-2"></i>
                            Back
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle me-2"></i>
                            Save Conference
                        </button>
                    </div>
                </div>
                @include('admin.conference._form')
            </form>
        </div>
    </div>
@endsection
