{{-- Guest User Dropdown --}}
<li class="nav-item dropdown">

    <a class="nav-link dropdown-toggle d-flex align-items-center gap-2 text-light" href="#" role="button" data-bs-toggle="dropdown"
        aria-expanded="true">

        <i class="fas fa-user-circle fa-lg"></i>

        <span class="d-none d-lg-inline fw-semibold">
            {{ Auth::user()->name ?? 'Guest' }}
        </span>
    </a>

    <ul class="dropdown-menu dropdown-menu-end shadow-sm">

        {{-- Header --}}
        <li class="px-3 py-2">
            <div class="fw-semibold">
                {{ Auth::user()->name ?? 'Guest User' }}
            </div>
            <div class="text-muted small">
                {{ Auth::user()->email ?? '' }}
            </div>
        </li>

        <li>
            <hr class="dropdown-divider">
        </li>

        {{-- Dashboard --}}
        <li>
            <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('guest.home') }}">
                <i class="fas fa-tachometer-alt text-primary"></i>
                <span>Tổng Quan</span>
            </a>
        </li>


        <li>
            <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('guest.profile.edit') }}">
                <i class="fas fa-user text-secondary"></i>
                <span>Hồ Sơ Cá Nhân</span>
            </a>
        </li>


        <li>
            <hr class="dropdown-divider">
        </li>

        {{-- Logout --}}
        <li class="px-3">
            <form method="POST" action="{{ route('logout') }}" class="js-confirm"
                data-confirm="Bạn chắc chắn muốn đăng xuất khỏi Roomia?">
                @csrf

                <button type="submit"
                    class="btn btn-outline-danger w-100 d-flex align-items-center justify-content-center gap-2">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Đăng Xuất</span>
                </button>
            </form>
        </li>

    </ul>
</li>