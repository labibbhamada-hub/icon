@extends('layouts.admin')

@section('title', 'Edit Topic')

@section('header')
    <div class="row">
        <div class="col-sm-6">
            <div class="d-flex gap-2">
                <a href="{{ route('admin.topics.index') }}" class="btn btn-secondary rounded-0" title="Back">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h1 class="mb-0 fs-3">
                    Edit Topic
                </h1>
            </div>
            <p class="text-muted mb-0">
                Update the details and information of this conference topic.
            </p>
        </div>
    </div>
@endsection

@section('content')
    <form action="{{ route('admin.topics.update', $topic) }}" method="POST" id="form-submit">
        @csrf
        @method('PUT')

        <div class="card rounded-0 overflow-hidden mb-3">

            <div class="card-header rounded-0">
                <h3 class="card-title">
                    <i class="bi bi-pencil-square me-2"></i>
                    Topic Information
                </h3>
            </div>

            @include('admin.topics._form')

        </div>
        <div class="text-end">
            <button type="button" class="btn btn-success rounded-0" id="btn-submit" onclick="form_submit()">
                <span id="btn-submit-text">
                    <i class="bi bi-check-circle me-1"></i>
                    Save Changes
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
