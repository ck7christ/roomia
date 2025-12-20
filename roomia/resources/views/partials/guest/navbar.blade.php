{{-- resources/views/partials/guest/navbar.blade.php --}}
<nav class="navbar navbar-expand-lg navbar-dark app-navbar shadow-sm">
    <div class="container flex-wrap">

        {{-- ROW 1: BRAND + TOGGLER --}}
        <a class="navbar-brand d-flex align-items-center order-0"
            href="{{ Route::has('home') ? route('home') : url('/') }}">
            <span class="brand-icon d-inline-flex align-items-center justify-content-center me-2">
                <i class="fa-solid fa-suitcase-rolling"></i>
            </span>
            <span class="fw-semibold">Roomia</span>
        </a>

        {{-- RIGHT --}}
        <div class="d-flex justify-content-end gap-lg-1">
            <li class="nav-item">
                <a class="btn btn-outline-light btn-sm" href="#">
                    ĐĂNG KÝ ĐỂ TRỞ THÀNH HOST
                </a>
            </li>
            @auth
                {{-- USER DROPDOWN --}}
                @include('partials.guest.user-dropdown')
            @else
                {{-- AUTH BUTTONS --}}
                <li class="nav-item">
                    <a class="btn btn-outline-light btn-sm" href="{{ Route::has('login') ? route('login') : '#' }}">
                        <i class="fa-solid fa-right-to-bracket me-1"></i>
                        ĐĂNG NHẬP
                    </a>
                </li>

                <li class="nav-item">
                    <a class="btn btn-primary btn-sm" href="{{ Route::has('register') ? route('register') : '#' }}">
                        <i class="fa-solid fa-user-plus me-1"></i>
                        ĐĂNG KÝ
                    </a>
                </li>
            @endauth
        </div>

        <button class="navbar-toggler order-1" type="button" data-bs-toggle="collapse" data-bs-target="#guestNavbar"
            aria-controls="guestNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- ROW 2: NAV (expand on lg, toggle on mobile) --}}
        <div class="collapse navbar-collapse w-100 order-lg-3 mt-2 mt-lg-3 pt-lg-2 border-top border-light border-opacity-10"
            id="guestNavbar">

            <div class="d-lg-flex align-items-lg-center justify-content-between w-100">
                {{-- LEFT --}}
                <ul class="navbar-nav me-auto align-items-lg-center gap-lg-1">

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                            href="{{ Route::has('home') ? route('home') : url('/') }}">
                            <i class="fa-solid fa-house"></i>
                            TRANG CHỦ
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('rooms.*') ? 'active' : '' }}"
                            href="{{ route('rooms.index') }}">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            KHÁM PHÁ
                        </a>
                    </li>

                    @auth
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('bookings.*') ? 'active' : '' }}"
                                href="{{ route('guest.bookings.index') }}">
                                <i class="fa-solid fa-receipt"></i>
                                ĐẶT PHÒNG
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('wishlist.*') ? 'active' : '' }}"
                                href="{{ route('guest.wishlist.index') }}">
                                <i class="fa-solid fa-heart"></i>
                                YÊU THÍCH
                            </a>
                        </li>
                    @endauth

                    {{-- SAFE PLACEHOLDER --}}
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('vouchers.*') ? 'active' : '' }}"
                            href="{{ route('vouchers.index') }}">
                            <i class="fa-solid fa-ticket"></i>
                            MÃ GIẢM GIÁ
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}"
                            href="{{ route('about') }}">
                            <i class="fa-solid fa-circle-info"></i>
                            VỀ ROOMIA
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}"
                            href="{{ route('contact.show') }}">
                            <i class="fa-solid fa-envelope"></i>
                            LIÊN HỆ
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>