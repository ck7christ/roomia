<div class="card mb-3">
    <div class="card-body d-flex flex-wrap gap-2">
        @if(Route::has('host.rooms.create'))
            <a class="btn btn-primary" href="{{ route('host.rooms.create') }}">
                <i class="fa-solid fa-plus me-1"></i> Thêm Room
            </a>
        @endif

        @if(Route::has('host.rooms.index'))
            <a class="btn btn-outline-secondary" href="{{ route('host.rooms.index') }}">
                <i class="fa-solid fa-hotel me-1"></i> Rooms
            </a>
        @endif

        @if(Route::has('host.room-types.index'))
            <a class="btn btn-outline-secondary" href="{{ route('host.room-types.index') }}">
                <i class="fa-solid fa-layer-group me-1"></i> Room Types
            </a>
        @endif

        @if(Route::has('host.bookings.index'))
            <a class="btn btn-outline-secondary" href="{{ route('host.bookings.index') }}">
                <i class="fa-solid fa-calendar-check me-1"></i> Bookings
            </a>
        @endif

        @if(Route::has('host.rooms.calendars.index'))
            <a class="btn btn-outline-secondary" href="{{ route('host.rooms.calendars.index') }}">
                <i class="fa-solid fa-calendar-days me-1"></i> Calendar
            </a>
        @endif

        @if(Route::has('profile.edit'))
            <a class="btn btn-outline-secondary" href="{{ route('profile.edit') }}">
                <i class="fa-solid fa-user-gear me-1"></i> Profile
            </a>
        @endif
    </div>
</div>