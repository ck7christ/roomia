{{-- resources/views/partials/host/navbar.blade.php --}}
{{-- 
    NAVBAR CHO HOST (Chủ Nhà)
    - Dùng Bootstrap Navbar (responsive): có nút toggler để thu gọn trên mobile.
    - Các route dùng route() để đảm bảo URL đúng theo cấu hình Laravel.
    - Active state dùng request()->routeIs(...) để tô sáng menu theo trang hiện tại.
--}}
<nav class="navbar navbar-expand-lg navbar-dark app-navbar shadow-sm">
    <div class="container">
        {{-- THƯƠNG HIỆU / LOGO --}}
        <a class="navbar-brand" href="{{ route('host.dashboard') }}">
            <span class="brand-icon d-inline-flex align-items-center justify-content-center me-2">
                <i class="fa-solid fa-suitcase-rolling"></i>
            </span>
            {{-- Tên thương hiệu + phân hệ đang dùng --}}
            <span class="fw-semibold">Roomia <span class="text-accent">Host</span></span>
        </a>
        {{-- NÚT THU GỌN (hiện trên màn hình nhỏ) --}}
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#hostNavbar"
            aria-controls="hostNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        {{-- NỘI DUNG NAVBAR (sẽ collapse trên mobile) --}}
        <div class="collapse navbar-collapse" id="hostNavbar">
            {{-- MENU BÊN TRÁI --}}
            <ul class="navbar-nav me-auto align-items-lg-center g-2">
                {{-- TỔNG QUAN (Dashboard) --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('host.dashboard') ? 'active' : '' }}"
                        href="{{ route('host.dashboard') }}">
                        <i class="fa-solid fa-gauge-high me-1"></i>
                        Tổng Quan
                    </a>
                </li>
                {{-- KHÁCH SẠN --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('host.rooms.*') ? 'active' : '' }}"
                        href="{{ route('host.rooms.index') }}">
                        <i class="fa-solid fa-bed me-1"></i>
                        Khách Sạn
                    </a>
                </li>
                {{-- ĐẶT PHÒNG --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('host.bookings.*') ? 'active' : '' }}"
                        href="{{ route('host.bookings.index') }}">
                        <i class="fa-solid fa-receipt me-1"></i>
                        Đặt Phòng
                    </a>
                </li>
                {{-- THANH TOÁN --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('host.payments.*') ? 'active' : '' }}"
                        href="{{ route('host.payments.index') }}">
                        <i class="fa-solid fa-money-bill-wave me-1"></i>
                        Thanh Toán
                    </a>
                </li>
                {{-- ĐÁNH GIÁ --}}
                <li class="nav-item">
                    <a class="nav-link @if (request()->routeIs('host.reviews.*')) active @endif"
                        href="{{ route('host.reviews.index') }}">
                        <i class="fas fa-star me-1"></i> Đánh Giá
                    </a>
                </li>
            </ul>
            {{-- MENU BÊN PHẢI DROPDOWN NGƯỜI DÙNG --}}
            <ul class="navbar-nav ms-lg-3">
                {{-- Partial hiển thị user dropdown (Profile/Logout/...) --}}
                @include('partials.host.user-dropdown')
            </ul>
        </div>
    </div>
</nav>
