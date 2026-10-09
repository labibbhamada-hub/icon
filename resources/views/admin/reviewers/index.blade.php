@extends('layouts.admin')

@section('title', 'Reviewers Management')

@section('header')
    <div class="row">
        <div class="col-sm-8">
            <h1 class="mb-0 fs-3">
                Reviewers Management
            </h1>
            <p class="text-muted mb-0">
                Manage conference reviewers and their information.
            </p>
        </div>
        <div class="col-sm-4">
            <a href="{{ route('admin.reviewers.create') }}" class="btn btn-success float-sm-end rounded-0">
                <i class="bi bi-person-plus me-1"></i>
                Add Reviewer
            </a>
        </div>
    </div>
@endsection

@section('content')
    <div class="card rounded-0 overflow-hidden">
        <div class="card-header rounded-0">
            <h3 class="card-title">
                <i class="bi bi-person-check me-2"></i>
                Reviewers List
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
                                Reviewer
                            </th>
                            <th>
                                Conference
                            </th>
                            <th>
                                Institution
                            </th>
                            <th>
                                Expertise
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
                        @forelse ($reviewers as $reviewer)
                            <tr>
                                {{-- Number --}}
                                <td class="align-top">
                                    {{ $reviewers->firstItem() + $loop->index }}
                                </td>
                                {{-- Reviewer --}}
                                <td class="align-top">
                                    @if ($reviewer->user)
                                        <div class="fw-semibold">
                                            {{ $reviewer->user->name }}
                                        </div>
                                        <small class="text-muted d-block">
                                            {{ $reviewer->user->email }}
                                        </small>
                                    @else
                                        <span class="text-muted">
                                            -
                                        </span>
                                    @endif
                                </td>
                                {{-- Conference --}}
                                <td class="align-top">
                                    @if ($reviewer->conference)
                                        <strong>
                                            {{ $reviewer->conference->short_name }}
                                        </strong>
                                        <small class="text-muted d-block">
                                            {{ $reviewer->conference->year }}
                                        </small>
                                    @else
                                        <span class="text-muted">
                                            -
                                        </span>
                                    @endif
                                </td>
                                {{-- Institution --}}
                                <td class="align-top">
                                    @if ($reviewer->institution)
                                        {{ $reviewer->institution }}
                                    @else
                                        <span class="text-muted">
                                            -
                                        </span>
                                    @endif
                                </td>
                                {{-- Expertise --}}
                                <td class="align-top">
                                    @if ($reviewer->expertise)
                                        <span>
                                            {{ \Illuminate\Support\Str::limit($reviewer->expertise, 100) }}
                                        </span>
                                    @else
                                        <span class="text-muted">
                                            -
                                        </span>
                                    @endif
                                </td>
                                {{-- Status --}}
                                <td class="align-top">
                                    @if ($reviewer->is_active)
                                        <span class="badge text-bg-success rounded-0">
                                            <i class="bi bi-check-circle me-1"></i>
                                            Active
                                        </span>
                                    @else
                                        <span class="badge text-bg-secondary rounded-0">
                                            <i class="bi bi-pause-circle me-1"></i>
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                {{-- Actions --}}
                                <td class="align-top">
                                    <form action="{{ route('admin.reviewers.destroy', $reviewer) }}" method="POST"
                                        class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <a href="{{ route('admin.reviewers.show', $reviewer) }}"
                                            class="btn btn-info rounded-0" title="View Reviewer">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.reviewers.edit', $reviewer) }}"
                                            class="btn btn-warning rounded-0" title="Edit Reviewer">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="submit" class="btn btn-danger rounded-0" title="Delete Reviewer">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="mb-2">
                                        <i class="bi bi-person-check display-5 text-muted"></i>
                                    </div>
                                    <h5 class="mb-1">
                                        No Reviewers Found
                                    </h5>
                                    <p class="text-muted mb-3">
                                        There are no reviewers registered yet.
                                    </p>
                                    <a href="{{ route('admin.reviewers.create') }}" class="btn btn-success rounded-0">
                                        <i class="bi bi-person-plus me-1"></i>
                                        Add Reviewer
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($reviewers->hasPages())
            <div class="card-footer rounded-0">
                {{ $reviewers->links() }}
            </div>
        @endif

    </div>
@endsection
