{{-- resources/views/partials/admin/sidebar.blade.php --}}
@php
    use Illuminate\Support\Facades\Route;

    $items = [
        ['label' => 'TỔNG QUAN', 'icon' => 'fa-gauge-high', 'route' => 'admin.dashboard', 'match' => 'admin.dashboard'],
        ['label' => 'NGƯỜI DÙNG', 'icon' => 'fa-users', 'route' => 'admin.users.index', 'match' => 'admin.users.*'],
        ['label' => 'KHÁCH SẠN', 'icon' => 'fa-hotel', 'route' => 'admin.rooms.index', 'match' => 'admin.rooms.*'],
        ['label' => 'ĐẶT PHÒNG', 'icon' => 'fa-calendar-check', 'route' => 'admin.bookings.index', 'match' => 'admin.bookings.*'],
        ['label' => 'THANH TOÁN', 'icon' => 'fa-credit-card', 'route' => 'admin.payments.index', 'match' => 'admin.payments.*'],
        ['label' => 'MÃ GIẢM GIÁ', 'icon' => 'fa-ticket', 'route' => 'admin.vouchers.index', 'match' => 'admin.vouchers.*'],
        ['label' => 'TIỆN ÍCH', 'icon' => 'fa-bell-concierge', 'route' => 'admin.amenities.index', 'match' => 'admin.amenities.*'],
    ];
@endphp

{{-- ========== Desktop sidebar (hiện từ LG trở lên) ========== --}}
<div class="d-none d-lg-flex flex-column vh-100 position-sticky top-0 border-end bg-white">
    <div class="px-3 py-3 border-bottom">
        <a href="{{ Route::has('admin.dashboard') ? route('admin.dashboard') : '#' }}"
            class="text-decoration-none d-flex align-items-center gap-2">
            <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-primary text-white"
                style="width:36px;height:36px;">
                <i class="fa-solid fa-shield-halved"></i>
            </span>
            <span class="fw-semibold text-dark">Admin</span>
        </a>
    </div>

    <nav class="nav flex-column px-2 py-2">
        @foreach($items as $it)
            @php
                $exists = Route::has($it['route']);
                $href = $exists ? route($it['route']) : '#';
                $active = $exists && request()->routeIs($it['match']);
            @endphp

            <a href="{{ $href }}" class="nav-link d-flex align-items-center gap-2 rounded-3 px-3 py-2
                          {{ $active ? 'active bg-primary bg-opacity-10 text-primary fw-semibold' : 'text-dark' }}
                          {{ $exists ? '' : 'disabled' }}" @if(!$exists) aria-disabled="true" tabindex="-1" @endif>
                <i class="fa-solid {{ $it['icon'] }}"></i>
                <span>{{ $it['label'] }}</span>
            </a>
        @endforeach
    </nav>

    <div class="mt-auto px-2 py-3 border-top">
        @include('partials.admin.user-dropdown')
    </div>
</div>

{{-- ========== Mobile sidebar (OFFCANVAS: hiện dưới LG) ========== --}}
<div class="offcanvas offcanvas-start d-lg-none" tabindex="-1" id="adminSidebar" aria-labelledby="adminSidebarLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="adminSidebarLabel">Menu Admin</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>

    <div class="offcanvas-body p-0">
        <nav class="nav flex-column px-2 py-2">
            @foreach($items as $it)
                @php
                    $exists = Route::has($it['route']);
                    $href = $exists ? route($it['route']) : '#';
                    $active = $exists && request()->routeIs($it['match']);
                @endphp

                <a href="{{ $href }}" class="nav-link d-flex align-items-center gap-2 rounded-3 px-3 py-2
                              {{ $active ? 'active bg-primary bg-opacity-10 text-primary fw-semibold' : 'text-dark' }}
                              {{ $exists ? '' : 'disabled' }}" @if($exists) data-bs-dismiss="offcanvas" @else
                            aria-disabled="true" tabindex="-1" @endif>
                    <i class="fa-solid {{ $it['icon'] }}"></i>
                    <span>{{ $it['label'] }}</span>
                </a>
            @endforeach
        </nav>

        <div class="border-top px-2 py-3">
            @include('partials.admin.user-dropdown')
        </div>
    </div>
</div>