@extends('layouts.admin')

@section('title', 'Topics')

@section('header')
    <div class="row align-items-top">
        <div class="col-sm-6">
            <h1 class="mb-0 fs-3">
                Topics Management
            </h1>
            <p class="text-muted mb-0">Manage conference topics and submission categories.</p>
        </div>
        <div class="col-sm-6">
            <a href="{{ route('admin.topics.create') }}" class="btn btn-success float-sm-end rounded-0">
                <i class="bi bi-plus-circle me-1"></i>
                Add Topic
            </a>
        </div>
    </div>
@endsection

@section('content')
    <div class="card rounded-0 overflow-hidden">

        <div class="card-header rounded-0">
            <h3 class="card-title">
                <i class="bi bi-diagram-3 me-2"></i>
                Topics List
            </h3>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive rounded-0">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th width="50">No</th>
                            <th>Topic</th>
                            <th>Conference</th>
                            <th>Color</th>
                            <th>Status</th>
                            <th width="170">Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse ($topics as $topic)
                            <tr>
                                <td class="align-top">
                                    {{ $loop->iteration + ($topics->firstItem() ?? 0) - 1 }}
                                </td>
                                <td class="align-top">
                                    <div class="d-flex gap-2">
                                        @if ($topic->icon)
                                            <i class="bi {{ $topic->icon }}"></i>
                                        @endif
                                        <div>
                                            <strong>
                                                {{ $topic->name }}
                                            </strong>
                                            @if ($topic->description)
                                                <small class="text-muted d-block mt-1">
                                                    {{ \Illuminate\Support\Str::limit($topic->description, 80) }}
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="align-top">
                                    <strong>
                                        {{ $topic->conference->short_name }}
                                    </strong>
                                    <small class="text-muted d-block">
                                        {{ $topic->conference->year }}
                                    </small>
                                </td>
                                <td class="align-top">
                                    <span class="badge text-bg-{{ $topic->color }} rounded-0">
                                        {{ ucfirst($topic->color) }}
                                    </span>
                                </td>
                                <td class="align-top">
                                    @if ($topic->is_active)
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
                                    <form action="{{ route('admin.topics.destroy', $topic) }}" method="POST"
                                        class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <a href="{{ route('admin.topics.show', $topic) }}" class="btn btn-info rounded-0"
                                            title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.topics.edit', $topic) }}"
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

                                <td colspan="6" class="text-center py-5">

                                    <div class="mb-2">
                                        <i class="bi bi-diagram-3 display-5 text-secondary"></i>
                                    </div>

                                    <h5 class="mb-1">
                                        No Topics Found
                                    </h5>

                                    <p class="text-muted mb-3">
                                        There are no topic data yet.
                                    </p>

                                    <a href="{{ route('admin.topics.create') }}" class="btn btn-success rounded-0">
                                        <i class="bi bi-plus-circle me-1"></i>
                                        Create First Topic
                                    </a>

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

        @if ($topics->hasPages())
            <div class="card-footer rounded-0 clearfix">
                {{ $topics->links() }}
            </div>
        @endif

    </div>

@endsection
