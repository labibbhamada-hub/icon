@extends('layouts.admin')

@section('title', 'Registration Types')

@section('header')
    <div class="row">
        <div class="col-sm-8">
            <h1 class="mb-0 fs-3">
                Registration Types
            </h1>
            <p class="text-muted mb-0">
                Manage registration types, fees, and benefits for conference participants.
            </p>
        </div>
        <div class="col-sm-4">
            <a href="{{ route('admin.registration-types.create') }}" class="btn btn-success float-sm-end rounded-0">
                <i class="bi bi-plus-circle me-1"></i>
                Create Registration Type
            </a>
        </div>
    </div>
@endsection

@section('content')
    <div class="card rounded-0 overflow-hidden">
        <div class="card-header rounded-0">
            <h3 class="card-title">
                <i class="bi bi-tags me-2"></i>
                Conference Registration Types
            </h3>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive rounded-0">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th width="50">
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
                            <th width="170">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($registrationTypes as $registrationType)
                            <tr>
                                <td class="align-top">
                                    {{ $registrationTypes->firstItem() + $loop->index }}
                                </td>
                                <td class="align-top">
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
                                <td class="align-top">
                                    <div class="fw-semibold">
                                        {{ $registrationType->name }}
                                    </div>
                                    <small class="text-muted d-block">
                                        {{ $registrationType->code }}
                                    </small>
                                </td>
                                <td class="align-top">
                                    <strong>
                                        {{ $registrationType->currency }}
                                        {{ number_format($registrationType->fee, 0, ',', '.') }}
                                    </strong>
                                </td>
                                <td class="align-top">
                                    <strong>
                                        {{ $registrationType->currency }}
                                        {{ number_format($registrationType->fee, 0, ',', '.') }}
                                    </strong>
                                </td>
                                <td class="align-top">
                                    {{ $registrationType->currency }}
                                </td>
                                <td class="align-top">
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
                                <td class="align-top">
                                    <form action="{{ route('admin.registration-types.destroy', $registrationType) }}"
                                        method="POST" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <a href="{{ route('admin.registration-types.show', $registrationType) }}"
                                            class="btn btn-info rounded-0" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.registration-types.edit', $registrationType) }}"
                                            class="btn btn-warning rounded-0" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="submit" class="btn btn-danger rounded-0" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
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
            <div class="card-footer rounded-0">
                {{ $registrationTypes->links() }}
            </div>
        @endif
    </div>
@endsection
