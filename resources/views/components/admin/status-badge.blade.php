@php
    $colors = [
        'draft' => 'secondary',
        'registration_open' => 'success',
        'submission_open' => 'primary',
        'review' => 'warning',
        'camera_ready' => 'info',
        'closed' => 'danger',
        'archived' => 'dark',
    ];
@endphp

<span class="badge text-bg-{{ $colors[$status] ?? 'secondary' }} rounded-0">
    {{ ucwords(str_replace('_', ' ', $status)) }}
</span>
