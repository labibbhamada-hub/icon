@extends('layouts.participant')

@section('title', 'My Profile')

@section('header')
    <div class="row">
        <div class="col-sm-6">
            <h1 class="mb-0 fs-3">
                My Profile
            </h1>
            <p class="text-muted mb-0">
                Manage your personal and conference information.
            </p>
        </div>
    </div>
@endsection

@section('content')
    <form action="{{ route('participant.profile.update') }}" method="POST" id="form-submit">
        @csrf
        @method('PUT')

        <div class="card rounded-0 overflow-hidden mb-3">

            <div class="card-header rounded-0">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                    <h3 class="card-title mb-0">
                        <i class="bi bi-person-vcard me-2"></i>
                        Personal Information
                    </h3>

                    <div class="d-flex align-items-center gap-2 flex-wrap">

                        <span class="small text-muted">
                            {{ $participant->registration_number }}
                        </span>

                        @if ($participant->registration_status === 'confirmed')
                            <span class="badge text-bg-success rounded-0">
                                Confirmed
                            </span>
                        @elseif ($participant->registration_status === 'cancelled')
                            <span class="badge text-bg-danger rounded-0">
                                Cancelled
                            </span>
                        @else
                            <span class="badge text-bg-warning rounded-0">
                                Pending
                            </span>
                        @endif

                    </div>

                </div>
            </div>


            <div class="card-body">

                <div class="border rounded-0 p-3 bg-light">

                    <div class="row align-items-center">

                        <div class="col-lg-6 mb-2 mb-lg-0">

                            <small class="text-muted d-block">
                                Participant
                            </small>

                            <div class="fw-bold fs-5">
                                {{ $participant->full_name }}
                            </div>

                        </div>

                        <div class="col-lg-6">

                            <small class="text-muted d-block">
                                Email
                            </small>

                            <div class="fw-semibold text-break">
                                {{ $participant->email }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <div class="card-body border-top">

                <div class="row">

                    {{-- Title Prefix --}}
                    <div class="col-md-3 mb-2">
                        <label for="title_prefix" class="form-label">
                            Title Prefix
                        </label>

                        <input type="text" id="title_prefix" name="title_prefix"
                            value="{{ old('title_prefix', $participant->title_prefix) }}"
                            class="form-control @error('title_prefix') is-invalid @enderror rounded-0"
                            placeholder="e.g. Dr.">

                        @error('title_prefix')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>


                    {{-- Full Name --}}
                    <div class="col-md-6 mb-2">
                        <label for="full_name" class="form-label">
                            Full Name
                            <span class="text-danger">*</span>
                        </label>

                        <input type="text" id="full_name" name="full_name"
                            value="{{ old('full_name', $participant->full_name) }}"
                            class="form-control @error('full_name') is-invalid @enderror rounded-0">

                        @error('full_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>


                    {{-- Title Suffix --}}
                    <div class="col-md-3 mb-2">
                        <label for="title_suffix" class="form-label">
                            Title Suffix
                        </label>

                        <input type="text" id="title_suffix" name="title_suffix"
                            value="{{ old('title_suffix', $participant->title_suffix) }}"
                            class="form-control @error('title_suffix') is-invalid @enderror rounded-0"
                            placeholder="e.g. S.Kom., M.Kom.">

                        @error('title_suffix')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>


                    {{-- Email --}}
                    <div class="col-md-6 mb-2">
                        <label for="email" class="form-label">
                            Email
                        </label>

                        <input type="email" id="email" value="{{ $participant->email }}"
                            class="form-control rounded-0" readonly>

                        <div class="form-text">
                            Email is managed through your account.
                        </div>
                    </div>


                    {{-- ORCID --}}
                    <div class="col-md-6 mb-2">
                        <label for="orcid" class="form-label">
                            ORCID
                        </label>

                        <input type="text" id="orcid" name="orcid" value="{{ old('orcid', $participant->orcid) }}"
                            class="form-control @error('orcid') is-invalid @enderror rounded-0"
                            placeholder="e.g. 0000-0002-1825-0097" maxlength="19">

                        @error('orcid')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                        <div class="form-text">
                            Optional. Format: 0000-0002-1825-0097
                        </div>
                    </div>


                    {{-- Phone --}}
                    <div class="col-md-6 mb-2">
                        <label for="phone" class="form-label">
                            Phone Number
                            <span class="text-danger">*</span>
                        </label>

                        <input type="text" id="phone" name="phone" value="{{ old('phone', $participant->phone) }}"
                            class="form-control @error('phone') is-invalid @enderror rounded-0" required>

                        @error('phone')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>


                    {{-- Country --}}
                    <div class="col-md-3 mb-2">
                        <label for="country" class="form-label">
                            Country
                            <span class="text-danger">*</span>
                        </label>

                        <input type="text" id="country" name="country"
                            value="{{ old('country', $participant->country) }}"
                            class="form-control @error('country') is-invalid @enderror rounded-0">

                        @error('country')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>


                    {{-- City --}}
                    <div class="col-md-3 mb-2">
                        <label for="city" class="form-label">
                            City
                        </label>

                        <input type="text" id="city" name="city"
                            value="{{ old('city', $participant->city) }}"
                            class="form-control @error('city') is-invalid @enderror rounded-0">

                        @error('city')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>


                    {{-- Institution --}}
                    <div class="col-md-6 mb-2">
                        <label for="institution" class="form-label">
                            Institution
                            <span class="text-danger">*</span>
                        </label>

                        <input type="text" id="institution" name="institution"
                            value="{{ old('institution', $participant->institution) }}"
                            class="form-control @error('institution') is-invalid @enderror rounded-0" required>

                        @error('institution')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>


                    {{-- Department --}}
                    <div class="col-md-6 mb-2">
                        <label for="department" class="form-label">
                            Department
                        </label>

                        <input type="text" id="department" name="department"
                            value="{{ old('department', $participant->department) }}"
                            class="form-control @error('department') is-invalid @enderror rounded-0">

                        @error('department')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                </div>

            </div>


            <div class="card-footer rounded-0">

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                    <div class="text-muted small">
                        <i class="bi bi-info-circle me-1"></i>
                        Keep your information up to date for conference communication and certification.
                    </div>

                </div>

            </div>

        </div>

        <div class="text-end">
            <button type="button" class="btn btn-success rounded-0" id="btn-submit" onclick="form_submit()">
                <span id="btn-submit-text">
                    <i class="bi bi-check-circle me-1"></i>
                    Save Changes
                </span>
                <span id="btn-submit-load" class="d-none">
                    <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                    Memproses...
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
