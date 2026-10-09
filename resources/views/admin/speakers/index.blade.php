@extends('layouts.admin')

@section('title', 'Speakers')

@section('header')
    <div class="row">
        <div class="col-sm-6">
            <h1 class="mb-0 fs-3">
                Speakers Management
            </h1>
            <p class="text-muted mb-0">Manage conference speakers and their information.</p>
        </div>
        <div class="col-sm-6">
            <a href="{{ route('admin.speakers.create') }}" class="btn btn-success float-sm-end rounded-0">
                <i class="bi bi-plus-circle me-1"></i>
                Add Speaker
            </a>
        </div>
    </div>
@endsection

@section('content')
    <div class="card rounded-0 overflow-hidden">
        <div class="card-header rounded-0">
            <h3 class="card-title">
                <i class="bi bi-mic me-2"></i>
                Speakers List
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
                                Photo
                            </th>
                            <th>
                                Speaker
                            </th>
                            <th>
                                Institution
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
                        @forelse ($speakers as $speaker)
                            <tr>
                                <td class="align-top">
                                    {{ $speakers->firstItem() + $loop->index }}
                                </td>
                                <td class="align-top">
                                    @if ($speaker->photo)
                                        <img src="{{ asset('storage/' . $speaker->photo) }}" alt="{{ $speaker->name }}"
                                            class="rounded-circle object-fit-cover" width="50" height="50">
                                    @else
                                        <div class="rounded-circle bg-secondary-subtle d-flex align-items-center justify-content-center"
                                            style="width: 50px; height: 50px;">
                                            <i class="bi bi-person fs-4 text-secondary"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="align-top">
                                    <div class="fw-semibold">
                                        {{ $speaker->name }}
                                    </div>
                                    @if ($speaker->title)
                                        <small class="text-muted d-block">
                                            {{ $speaker->title }}
                                        </small>
                                    @endif
                                    @if ($speaker->position)
                                        <small class="text-muted d-block">
                                            {{ $speaker->position }}
                                        </small>
                                    @endif
                                </td>
                                <td class="align-top">
                                    {{ $speaker->institution ?: '-' }}
                                </td>
                                <td class="align-top">
                                    @if ($speaker->conference)
                                        <strong>
                                            {{ $speaker->conference->short_name }}
                                        </strong>
                                        <small class="text-muted d-block">
                                            {{ $speaker->conference->year }}
                                        </small>
                                    @else
                                        <span class="text-muted">
                                            -
                                        </span>
                                    @endif
                                </td>
                                <td class="align-top">
                                    @if ($speaker->is_active)
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
                                    <form action="{{ route('admin.speakers.destroy', $speaker) }}" method="POST"
                                        class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <a href="{{ route('admin.speakers.show', $speaker) }}"
                                            class="btn btn-info rounded-0" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.speakers.edit', $speaker) }}"
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
                                        <i class="bi bi-mic display-5 text-muted"></i>
                                    </div>
                                    <h5 class="mb-1">
                                        No Speakers Found
                                    </h5>
                                    <p class="text-muted mb-3">
                                        There are no speaker data yet.
                                    </p>
                                    <a href="{{ route('admin.speakers.create') }}" class="btn btn-success rounded-0">
                                        <i class="bi bi-plus-circle me-1"></i>
                                        Create First Speaker
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($speakers->hasPages())
            <div class="card-footer rounded-0">
                {{ $speakers->links() }}
            </div>
        @endif
    </div>
@endsection
