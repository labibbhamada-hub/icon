@extends('layouts.admin')

@section('title', 'Registration Types')

@section('header')

    <div class="row">

        <div class="col-sm-6">
            <h1 class="mb-0 fs-3">
                Registration Types
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

                    <li class="breadcrumb-item active" aria-current="page">
                        Registration Types
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
                <i class="bi bi-tags me-2"></i>
                Conference Registration Types
            </h3>

            <div class="float-end">

                <a href="{{ route('admin.registration-types.create') }}" class="btn btn-success btn-sm rounded-0">
                    <i class="bi bi-plus-circle me-1"></i>
                    Create Registration Type
                </a>

            </div>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead>

                        <tr>

                            <th width="60">
                                No
                            </th>

                            <th>
                                Conference
                            </th>

                            <th>
                                Name
                            </th>

                            <th>
                                Category
                            </th>

                            <th>
                                Pricing
                            </th>

                            <th>
                                Currency
                            </th>

                            <th>
                                Status
                            </th>

                            <th width="140">
                                Action
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse ($registrationTypes as $registrationType)

                            <tr>

                                <td>
                                    {{ $registrationTypes->firstItem() + $loop->index }}
                                </td>

                                <td>

                                    @if ($registrationType->conference)
                                        <strong>
                                            {{ $registrationType->conference->short_name }}
                                        </strong>

                                        <small class="text-muted d-block">
                                            {{ $registrationType->conference->year }}
                                        </small>
                                    @else
                                        <span class="text-muted">
                                            -
                                        </span>
                                    @endif

                                </td>

                                <td>

                                    <div class="fw-semibold">
                                        {{ $registrationType->name }}
                                    </div>

                                    <small class="text-muted d-block">
                                        {{ $registrationType->code }}
                                    </small>

                                </td>

                                <td>

                                    @if ($registrationType->category === 'presenter')
                                        <span class="badge text-bg-primary rounded-0">
                                            Presenter
                                        </span>
                                    @else
                                        <span class="badge text-bg-secondary rounded-0">
                                            Participant
                                        </span>
                                    @endif

                                </td>

                                <td>

                                    @if ($registrationType->category === 'presenter')
                                        @php
                                            $oralPrice = $registrationType->presentationPrices->firstWhere(
                                                'presentation_type',
                                                'oral',
                                            );

                                            $posterPrice = $registrationType->presentationPrices->firstWhere(
                                                'presentation_type',
                                                'poster',
                                            );
                                        @endphp

                                        @if ($oralPrice || $posterPrice)
                                            <div class="small">

                                                @if ($oralPrice)
                                                    <div>
                                                        <span class="text-muted">
                                                            Oral:
                                                        </span>

                                                        <strong>
                                                            {{ $oralPrice->currency }}
                                                            {{ number_format($oralPrice->fee, 0, ',', '.') }}
                                                        </strong>
                                                    </div>
                                                @endif

                                                @if ($posterPrice)
                                                    <div class="mt-1">
                                                        <span class="text-muted">
                                                            Poster:
                                                        </span>

                                                        <strong>
                                                            {{ $posterPrice->currency }}
                                                            {{ number_format($posterPrice->fee, 0, ',', '.') }}
                                                        </strong>
                                                    </div>
                                                @endif

                                            </div>
                                        @else
                                            <span class="text-muted">
                                                Not configured
                                            </span>
                                        @endif
                                    @else
                                        <strong>
                                            {{ $registrationType->currency }}
                                            {{ number_format($registrationType->fee, 0, ',', '.') }}
                                        </strong>
                                    @endif

                                </td>

                                <td>
                                    {{ $registrationType->currency }}
                                </td>

                                <td>

                                    @if ($registrationType->is_active)
                                        <span class="badge text-bg-success rounded-0">
                                            Active
                                        </span>
                                    @else
                                        <span class="badge text-bg-secondary rounded-0">
                                            Inactive
                                        </span>
                                    @endif

                                </td>

                                <td>

                                    <div class="btn-group gap-1">

                                        <a href="{{ route('admin.registration-types.show', $registrationType) }}"
                                            class="btn btn-info btn-sm rounded-0" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <a href="{{ route('admin.registration-types.edit', $registrationType) }}"
                                            class="btn btn-warning btn-sm rounded-0" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <form action="{{ route('admin.registration-types.destroy', $registrationType) }}"
                                            method="POST" class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-danger btn-sm rounded-0" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="8" class="text-center py-5">

                                    <div class="mb-2">
                                        <i class="bi bi-tags display-5 text-muted"></i>
                                    </div>

                                    <h5 class="mb-1">
                                        No Registration Types Found
                                    </h5>

                                    <p class="text-muted mb-3">
                                        Create registration types for your conference.
                                    </p>

                                    <a href="{{ route('admin.registration-types.create') }}"
                                        class="btn btn-success rounded-0">
                                        <i class="bi bi-plus-circle me-1"></i>
                                        Create Registration Type
                                    </a>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

        @if ($registrationTypes->hasPages())
            <div class="card-footer">
                {{ $registrationTypes->links() }}
            </div>
        @endif

    </div>

@endsection

@push('scripts')
    <script>
        document.querySelectorAll('.delete-form').forEach(function(form) {

            form.addEventListener('submit', function(event) {

                event.preventDefault();

                Swal.fire({
                    title: 'Delete Registration Type?',
                    text: 'This action cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                }).then(function(result) {

                    if (result.isConfirmed) {
                        form.submit();
                    }

                });

            });

        });
    </script>
@endpush
