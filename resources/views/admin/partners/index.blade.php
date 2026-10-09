@extends('layouts.admin')

@section('title', 'Partners')

@section('header')
    <div class="row">
        <div class="col-sm-6">
            <h1 class="mb-0 fs-3">
                Partners Management
            </h1>
            <p class="text-muted mb-0">Manage conference speakers and their information.</p>
        </div>
        <div class="col-sm-6">
            <a href="{{ route('admin.partners.create') }}" class="btn btn-success float-sm-end rounded-0">
                <i class="bi bi-plus-circle me-1"></i>
                Add Partner
            </a>
        </div>
    </div>
@endsection

@section('content')
    <div class="card rounded-0 overflow-hidden">
        <div class="card-header rounded-0">
            <h3 class="card-title">
                <i class="bi bi-buildings me-2"></i>
                Partners List
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
                            <th width="100">
                                Logo
                            </th>
                            <th>
                                Partner
                            </th>
                            <th>
                                Type
                            </th>
                            <th>
                                Conference
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
                        @forelse ($partners as $partner)
                            <tr>
                                <td class="align-top">
                                    {{ $partners->firstItem() + $loop->index }}
                                </td>
                                <td class="align-top">
                                    @if ($partner->logo)
                                        <img src="{{ asset('storage/' . $partner->logo) }}" alt="{{ $partner->name }}"
                                            class="img-thumbnail rounded-0"
                                            style="width: 55px; height: 55px; object-fit: contain;">
                                    @else
                                        <div class="rounded-0 bg-light border d-flex align-items-center justify-content-center"
                                            style="width: 55px; height: 55px;">
                                            <i class="bi bi-building text-muted fs-4"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="align-top">
                                    <div class="fw-semibold">
                                        {{ $partner->name }}
                                    </div>
                                    @if ($partner->description)
                                        <small class="text-muted d-block">
                                            {{ \Illuminate\Support\Str::limit($partner->description, 70) }}
                                        </small>
                                    @endif
                                </td>
                                <td class="align-top">
                                    <span class="badge text-bg-secondary rounded-0">
                                        {{ ucwords(str_replace('_', ' ', $partner->type)) }}
                                    </span>
                                </td>
                                <td class="align-top">
                                    @if ($partner->conference)
                                        <strong>
                                            {{ $partner->conference->short_name }}
                                        </strong>
                                        <small class="text-muted d-block">
                                            {{ $partner->conference->year }}
                                        </small>
                                    @else
                                        <span class="text-muted">
                                            -
                                        </span>
                                    @endif
                                </td>
                                <td class="align-top">
                                    @if ($partner->is_active)
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
                                    <form action="{{ route('admin.partners.destroy', $partner) }}" method="POST"
                                        class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <a href="{{ route('admin.partners.show', $partner) }}"
                                            class="btn btn-info rounded-0" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.partners.edit', $partner) }}"
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
                                <td colspan="7" class="text-center py-5">
                                    <div class="mb-2">
                                        <i class="bi bi-buildings display-5 text-muted"></i>
                                    </div>
                                    <h5 class="mb-1">
                                        No Partners Found
                                    </h5>
                                    <p class="text-muted mb-3">
                                        There are no partner data yet.
                                    </p>
                                    <a href="{{ route('admin.partners.create') }}" class="btn btn-success rounded-0">
                                        <i class="bi bi-plus-circle me-1"></i>
                                        Create First Partner
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($partners->hasPages())
            <div class="card-footer rounded-0">
                {{ $partners->links() }}
            </div>
        @endif
    </div>
@endsection
