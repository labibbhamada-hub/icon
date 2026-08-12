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
                            <th width="60">No</th>
                            <th>Topic</th>
                            <th>Conference</th>
                            <th>Color</th>
                            <th>Status</th>
                            <th width="120">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topics as $topic)
                            <tr>
                                <td class="align-top">
                                    {{ $loop->iteration + ($topics->firstItem() ?? 0) - 1 }}
                                </td>
                                <td class="align-top">
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
                                <td class="align-top">
                                    <strong>
                                        {{ $topic->conference->short_name }}
                                    </strong>
                                    <br>
                                    <small class="text-muted">
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
                                    <div class="btn-group gap-1">
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
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="mb-2">
                                        <i class="bi bi-diagram-3 display-5 text-secondary"></i>
                                    </div>
                                    <div class="mb-2">
                                        <h5>No Topics Found</h5>
                                        <p class="text-muted">There is no topics data yet.</p>
                                    </div>
                                    <a href="{{ route('admin.topics.create') }}" class="btn btn-success rounded-0">
                                        <i class="bi bi-plus-circle me-1"></i>
                                        Create First Topics
                                    </a>
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
