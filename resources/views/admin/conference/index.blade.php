@extends('layouts.admin')

@section('title', 'Conference Management')

@section('content')
    <div class="app-content">
        <div class="container-fluid">
            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold mb-1">
                        Conference Management
                    </h3>
                    <p class="text-muted mb-0">
                        Manage conference information for ICON CMS.
                    </p>
                </div>
                <a href="{{ route('admin.conference.create') }}" class="btn btn-success">
                    <i class="bi bi-plus-circle me-2"></i>
                    Add Conference
                </a>
            </div>
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th width="60">No</th>
                                    <th>Conference</th>
                                    <th>Year</th>
                                    <th>Status</th>
                                    <th width="180">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        No conference data available.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
