@auth
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button"
            data-bs-toggle="dropdown">
            <span class="me-2">{{ Auth::user()->name }}</span>
            <i class="fa-solid fa-user-circle"></i>
        </a>

        <ul class="dropdown-menu dropdown-menu-end">
            @if (Route::has('profile.edit'))
                <li>
                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                        <i class="fa-solid fa-user-gear me-2"></i> Profile
                    </a>
                </li>
            @endif

            <li>
                <hr class="dropdown-divider">
            </li>

            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="dropdown-item">
                        <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
                    </button>
                </form>
            </li>
        </ul>
    </li>
@endauth
