{{-- resources/views/partials/admin/sidebar.blade.php --}}
@php
    use Illuminate\Support\Facades\Route;

    $items = [
        ['label' => 'Dashboard', 'icon' => 'fa-gauge-high', 'route' => 'admin.dashboard'],
        ['label' => 'Users', 'icon' => 'fa-users', 'route' => 'admin.users.index'],
        ['label' => 'Hosts', 'icon' => 'fa-user-tie', 'route' => 'admin.hosts.index'],
        ['label' => 'Rooms', 'icon' => 'fa-hotel', 'route' => 'admin.rooms.index'],
        ['label' => 'Bookings', 'icon' => 'fa-calendar-check', 'route' => 'admin.bookings.index'],
        ['label' => 'Payments', 'icon' => 'fa-credit-card', 'route' => 'admin.payments.index'],
        ['label' => 'Vouchers', 'icon' => 'fa-ticket', 'route' => 'admin.vouchers.index'],
    ];
@endphp

{{-- ========== Desktop sidebar (ẩn trên mobile) ========== --}}
<div class="d-none d-lg-flex flex-column min-vh-100">
    <div class="px-3 py-3 fw-semibold border-bottom">Admin</div>

    <nav class="nav flex-column px-2 py-2">
        @foreach($items as $it)
            @php
                $exists = Route::has($it['route']);
                $href = $exists ? route($it['route']) : '#';
                $active = $exists && request()->routeIs($it['route']);
            @endphp

            <a href="{{ $href }}"
                class="nav-link d-flex align-items-center gap-2 {{ $active ? 'active' : '' }} {{ $exists ? '' : 'disabled' }}"
                @if(!$exists) aria-disabled="true" tabindex="-1" @endif>
                <i class="fa-solid {{ $it['icon'] }}"></i>
                <span>{{ $it['label'] }}</span>
            </a>
        @endforeach
    </nav>

    {{-- User dropdown (desktop) --}}
    <div class="mt-auto px-2 py-3 border-top">
        @include('partials.admin.user-dropdown')
    </div>
</div>

{{-- ========== Mobile sidebar (offcanvas) ========== --}}
<div class="offcanvas offcanvas-start d-lg-none" tabindex="-1" id="adminSidebar" aria-labelledby="adminSidebarLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="adminSidebarLabel">Admin</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>

    <div class="offcanvas-body p-0">
        <nav class="nav flex-column px-2 py-2">
            @foreach($items as $it)
                @php
                    $exists = Route::has($it['route']);
                    $href = $exists ? route($it['route']) : '#';
                    $active = $exists && request()->routeIs($it['route']);
                @endphp

                <a href="{{ $href }}"
                    class="nav-link d-flex align-items-center gap-2 {{ $active ? 'active' : '' }} {{ $exists ? '' : 'disabled' }}"
                    @if($exists) data-bs-dismiss="offcanvas" @else aria-disabled="true" tabindex="-1" @endif>
                    <i class="fa-solid {{ $it['icon'] }}"></i>
                    <span>{{ $it['label'] }}</span>
                </a>
            @endforeach
        </nav>
    </div>
</div>