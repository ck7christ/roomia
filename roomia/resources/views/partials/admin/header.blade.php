{{-- resources/views/partials/admin/header.blade.php --}}
<div class="d-flex align-items-center justify-content-between border-bottom bg-white px-3 py-2">
    <div class="d-flex align-items-center gap-2">
        {{-- Hamburger (chỉ hiện mobile) --}}
        <button class="btn btn-outline-secondary btn-sm d-lg-none" type="button" data-bs-toggle="offcanvas"
            data-bs-target="#adminSidebar" aria-controls="adminSidebar">
            <i class="fa-solid fa-bars"></i>
        </button>

        <div class="d-flex align-items-center gap-2">
            <i class="fa-solid fa-shield-halved text-muted"></i>
            <span class="fw-semibold">
                @hasSection('title')
                    @yield('title')
                @else
                    Admin
                @endif
            </span>
        </div>
    </div>

    {{-- User dropdown (hiện trên mobile để logout dễ, desktop bạn có thể để trong sidebar) --}}
    <div class="d-lg-none">
        @include('partials.admin.user-dropdown')
    </div>
</div>