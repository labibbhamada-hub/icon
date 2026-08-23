@extends('layouts.participant')
@section('title', 'Notifications')
@section('header')
    <div class="row align-items-center">
        <div class="col-sm-6">
            <h1 class="mb-0 fs-3 fw-bold">
                Notifications
            </h1>
            <p class="text-muted mb-0">
                Stay updated with your conference activities.
            </p>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('participant.dashboard') }}">
                        Dashboard
                    </a>
                </li>
                <li class="breadcrumb-item active">
                    Notifications
                </li>
            </ol>
        </div>
    </div>
@endsection
@section('content')
    <div class="card rounded-0">
        <div class="card-header">
            <h3 class="card-title">
                <i class="bi bi-bell me-2"></i>
                My Notifications
            </h3>
            @if ($notifications->whereNull('read_at')->isNotEmpty())
                <div class="float-end">
                    <form action="{{ route('participant.notifications.read-all') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-primary btn-sm rounded-0">
                            <i class="bi bi-check2-all me-1"></i>
                            Mark All as Read
                        </button>
                    </form>
                </div>
            @endif
        </div>
        <div class="card-body p-0">
            @forelse ($notifications as $notification)
                @php
                    $data = $notification->data;
                    $type = $data['type'] ?? 'info';
                    $icon = match ($type) {
                        'success' => 'bi-check-circle',
                        'warning' => 'bi-exclamation-circle',
                        'danger' => 'bi-x-circle',
                        default => 'bi-info-circle',
                    };
                @endphp
                <div class="border-bottom p-3 {{ $notification->read_at ? '' : 'bg-light' }}">
                    <div class="d-flex align-items-start gap-3">
                        <div class="flex-shrink-0">
                            <div class="bg-{{ $type }}-subtle rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 44px; height: 44px;">
                                <i class="bi {{ $icon }} text-{{ $type }}"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start gap-3">
                                <div>
                                    <h6 class="fw-bold mb-1">
                                        {{ $data['title'] ?? 'Notification' }}
                                    </h6>
                                    <p class="text-muted mb-2">
                                        {{ $data['message'] ?? '' }}
                                    </p>
                                    <small class="text-muted">
                                        {{ $notification->created_at?->diffForHumans() }}
                                    </small>
                                </div>
                                @if (!$notification->read_at)
                                    <span class="badge text-bg-primary rounded-0">
                                        New
                                    </span>
                                @endif
                            </div>
                            <div class="mt-2">
                                @if (!empty($data['action_url']) && !empty($data['action_text']))
                                    <a href="{{ $data['action_url'] }}" class="btn btn-outline-primary btn-sm rounded-0">
                                        <i class="bi bi-arrow-right me-1"></i>
                                        {{ $data['action_text'] }}
                                    </a>
                                @endif
                                @if (!$notification->read_at)
                                    <form action="{{ route('participant.notifications.read', $notification->id) }}"
                                        method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-outline-secondary btn-sm rounded-0">
                                            <i class="bi bi-check2 me-1"></i>
                                            Mark as Read
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <i class="bi bi-bell-slash display-5 text-muted"></i>
                    <h5 class="mt-3">
                        No Notifications
                    </h5>
                    <p class="text-muted mb-0">
                        Your conference notifications will appear here.
                    </p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
