@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="app-content-header">
        <div class="container-fluid">
            {{-- Content Header --}}
            <div class="row mb-4">
                <div class="col-sm-6">
                    <h3 class="mb-1 fw-bold">Dashboard</h3>
                    <p class="text-muted mb-0">
                        Welcome back,
                        <strong>{{ Auth::user()->name }}</strong>
                    </p>
                </div>
                <div class="col-sm-6 text-end">
                    <small class="text-muted">
                        {{ now()->format('l, d F Y') }}
                    </small>
                </div>
            </div>
            {{-- Statistics --}}
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box text-bg-success">
                        <div class="inner">
                            <h3>0</h3>
                            <p>Total Participants</p>
                        </div>
                        <i class="small-box-icon bi bi-people-fill"></i>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box text-bg-warning">
                        <div class="inner">
                            <h3>0</h3>
                            <p>Total Papers</p>
                        </div>
                        <i class="small-box-icon bi bi-file-earmark-text"></i>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box text-bg-info">
                        <div class="inner">
                            <h3>0</h3>
                            <p>Pending Reviews</p>
                        </div>
                        <i class="small-box-icon bi bi-search"></i>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box text-bg-danger">
                        <div class="inner">
                            <h3>0</h3>
                            <p>Pending Payments</p>
                        </div>
                        <i class="small-box-icon bi bi-credit-card"></i>
                    </div>
                </div>
            </div>
            <div class="row">
                {{-- Conference Information --}}
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="bi bi-calendar-event me-2"></i>
                                Conference Information
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            <table class="table mb-0">
                                <tr>
                                    <th width="220">Conference</th>
                                    <td>ICON 2026</td>
                                </tr>
                                <tr>
                                    <th>Date</th>
                                    <td>15–16 July 2026</td>
                                </tr>
                                <tr>
                                    <th>Venue</th>
                                    <td>Universitas Bhamada</td>
                                </tr>
                                <tr>
                                    <th>Registration</th>
                                    <td>
                                        <span class="badge text-bg-success">
                                            OPEN
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Submission</th>
                                    <td>
                                        <span class="badge text-bg-success">
                                            OPEN
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                {{-- Quick Action --}}
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                Quick Actions
                            </h3>
                        </div>
                        <div class="card-body d-grid gap-2">
                            <a href="{{ route('admin.conferences.create') }}" class="btn btn-success">
                                <i class="bi bi-plus-circle me-2"></i>
                                Add Conference
                            </a>
                            <button class="btn btn-outline-secondary">
                                Add Topic
                            </button>
                            <button class="btn btn-outline-secondary">
                                Add Speaker
                            </button>
                            <button class="btn btn-outline-secondary">
                                Add Partner
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        Recent Activity
                    </h3>
                </div>
                <div class="card-body">
                    <div class="text-center py-5">
                        <i class="bi bi-clock-history fs-1 text-secondary"></i>
                        <p class="text-muted mt-3 mb-0">
                            No recent activity available.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
