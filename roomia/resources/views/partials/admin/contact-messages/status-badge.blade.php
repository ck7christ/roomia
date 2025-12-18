@php
    $status = $status ?? 'new';
    $map = [
        'new' => 'warning',
        'seen' => 'primary',
        'replied' => 'success',
        'closed' => 'secondary',
    ];
    $cls = $map[$status] ?? 'secondary';
@endphp

<span class="badge text-bg-{{ $cls }}">{{ $status }}</span>