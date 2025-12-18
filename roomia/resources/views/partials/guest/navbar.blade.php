{{-- resources/views/partials/guest/navbar.blade.php --}}
<nav class="navbar navbar-expand-lg navbar-dark app-navbar shadow-sm">
    <div class="container">

        {{-- BRAND --}}
        <a class="navbar-brand d-flex align-items-center" href="{{ Route::has('home') ? route('home') : url('/') }}">
            <span class="brand-icon d-inline-flex align-items-center justify-content-center me-2">
                <i class="fa-solid fa-suitcase-rolling"></i>
            </span>
            <span class="fw-semibold">Roomia</span>
        </a>

        {{-- TOGGLER --}}
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#guestNavbar"
            aria-controls="guestNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- CONTENT --}}
        <div class="collapse navbar-collapse" id="guestNavbar">

            {{-- LEFT --}}
            <ul class="navbar-nav me-auto align-items-lg-center">

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                        href="{{ Route::has('home') ? route('home') : url('/') }}">
                        <i class="fa-solid fa-house"></i>
                        Home
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('rooms.*') ? 'active' : '' }}"
                        href="{{ route('rooms.index') }}">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        Explore
                    </a>
                </li>

                @auth
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('bookings.*') ? 'active' : '' }}"
                            href="{{ route('guest.bookings.index') }}">
                            <i class="fa-solid fa-receipt"></i>
                            My Bookings
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('wishlist.*') ? 'active' : '' }}"
                            href="{{ route('guest.wishlist.index') }}">
                            <i class="fa-solid fa-heart"></i>
                            Wishlist
                        </a>
                    </li>
                @endauth

                {{-- SAFE PLACEHOLDER --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">
                        <i class="fa-solid fa-circle-info"></i>
                        About
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}"
                        href="{{ route('contact.show') }}">
                        <i class="fa-solid fa-envelope"></i>
                        Contact
                    </a>
                </li>
            </ul>

            {{-- RIGHT --}}
            <ul class="navbar-nav ms-lg-3 align-items-lg-center">

                @auth
                    {{-- USER DROPDOWN --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="fa-solid fa-user"></i>
                            {{ auth()->user()->name ?? 'Account' }}
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item"
                                    href="{{ Route::has('guest.profile.edit') ? route('guest.profile.edit') : '#' }}">
                                    <i class="fa-solid fa-user-gear me-2"></i>
                                    Profile
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form method="POST" action="{{ Route::has('logout') ? route('logout') : '#' }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fa-solid fa-right-from-bracket me-2"></i>
                                        Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    {{-- AUTH BUTTONS --}}
                    <li class="nav-item me-2">
                        <a class="btn btn-outline-light btn-sm" href="{{ Route::has('login') ? route('login') : '#' }}">
                            <i class="fa-solid fa-right-to-bracket me-1"></i>
                            Login
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="btn btn-primary btn-sm" href="{{ Route::has('register') ? route('register') : '#' }}">
                            <i class="fa-solid fa-user-plus me-1"></i>
                            Register
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
