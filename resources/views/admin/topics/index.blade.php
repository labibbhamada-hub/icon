@extends('layouts.admin')

@section('title', 'Topics')

@section('header')
    <div class="row">
        <div class="col-sm-6">
            <h1 class="mb-0 fs-3">Topics Management</h1>
        </div>
        <div class="col-sm-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Topics</li>
                </ol>
            </nav>
        </div>
    </div>
@endsection

@section('content')
    <div class="card rounded-0">
        <div class="card-header">
            <h3 class="card-title">
                Topics List
            </h3>
            <div class="float-end">
                <a href="{{ route('admin.topics.create') }}" class="btn btn-success btn-sm rounded-0">
                    <i class="bi bi-plus-circle"></i>
                    Add Topic
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
                                Topic
                            </th>
                            <th width="220">
                                Conference
                            </th>
                            <th width="120">
                                Color
                            </th>
                            <th width="100">
                                Status
                            </th>
                            <th width="170">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topics as $topic)
                            <tr>
                                <td>
                                    {{ $loop->iteration + ($topics->firstItem() ?? 0) - 1 }}
                                </td>
                                <td>
                                    <strong>
                                        @if ($topic->icon)
                                            <i class="bi {{ $topic->icon }} me-2"></i>
                                        @endif
                                        {{ $topic->name }}
                                    </strong>
                                    @if ($topic->description)
                                        <br>
                                        <small class="text-muted">
                                            {{ \Illuminate\Support\Str::limit($topic->description, 80) }}
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    <strong>
                                        {{ $topic->conference->short_name }}
                                    </strong>
                                    <br>
                                    <small class="text-muted">
                                        {{ $topic->conference->year }}
                                    </small>
                                </td>
                                <td>
                                    <span class="badge text-bg-{{ $topic->color }} rounded-0">
                                        {{ ucfirst($topic->color) }}
                                    </span>
                                </td>
                                <td>
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
                                <td>
                                    <a href="{{ route('admin.topics.show', $topic) }}"
                                        class="btn btn-info btn-sm rounded-0">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.topics.edit', $topic) }}"
                                        class="btn btn-warning btn-sm rounded-0">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.topics.destroy', $topic) }}" method="POST"
                                        class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm rounded-0">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="bi bi-diagram-3 display-5 text-secondary"></i>
                                    <p class="mt-3 mb-0">
                                        No topics available.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($topics->hasPages())
            <div class="card-footer clearfix">
                {{ $topics->links() }}
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Delete Topic?',
                    text: 'This action cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Delete',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
