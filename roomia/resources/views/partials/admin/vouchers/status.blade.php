@php
    $ok = $voucher->isCurrentlyActive();
@endphp
<span class="badge text-bg-{{ $ok ? 'success' : 'secondary' }}">
    {{ $ok ? 'active' : 'inactive' }}
</span>
