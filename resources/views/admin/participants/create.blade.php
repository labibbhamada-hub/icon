@extends('layouts.admin')

@section('title', 'Create Participant')

@section('header')
    <div class="row">
        <div class="col-sm-6">
            <div class="d-flex gap-2">
                <a href="{{ route('admin.participants.index') }}" class="btn btn-secondary rounded-0">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h1 class="mb-0 fs-3">
                    Create Participant
                </h1>
            </div>
            <p class="text-muted mb-0">
                Add a new conference participant and their registration information.
            </p>
        </div>
    </div>
@endsection

@section('content')
    <form action="{{ route('admin.participants.store') }}" method="POST" id="form-submit">
        @csrf

        <div class="card rounded-0 overflow-hidden mb-3">
            <div class="card-header rounded-0">
                <h3 class="card-title">
                    <i class="bi bi-person-plus me-2"></i>
                    Participant Information
                </h3>
            </div>

            @include('admin.participants._form')

        </div>

        <div class="text-end">
            <button type="button" class="btn btn-success rounded-0" id="btn-submit" onclick="form_submit()">
                <span id="btn-submit-text">
                    <i class="bi bi-check-circle me-1"></i>
                    Save Participant
                </span>
                <span id="btn-submit-load" class="d-none">
                    <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                    Loading...
                </span>
            </button>
        </div>

    </form>

@endsection

@push('scripts')
    <script>
        function form_submit() {
            const btnSubmit = document.getElementById('btn-submit');
            const btnSubmitText = document.getElementById('btn-submit-text');
            const btnSubmitLoad = document.getElementById('btn-submit-load');
            const formSubmit = document.getElementById('form-submit');

            btnSubmit.disabled = true;

            btnSubmitText.classList.add('d-none');
            btnSubmitLoad.classList.remove('d-none');

            formSubmit.submit();
        }
    </script>
@endpush
