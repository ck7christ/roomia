@php
    $s = strtolower((string) ($status ?? 'unknown'));
    $map = [
        'pending' => 'secondary',
        'confirmed' => 'primary',
        'completed' => 'success',
        'cancelled' => 'danger',
        'canceled' => 'danger',
    ];
    $cls = $map[$s] ?? 'dark';
@endphp

<span class="badge text-bg-{{ $cls }}">{{ $status ?? 'unknown' }}</span>