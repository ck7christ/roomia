<div class="row g-3">
    @include('partials.host.dashboard.stat-card', [
        'title' => 'Rooms',
        'value' => $roomsCount ?? 0,
        'icon' => 'fa-solid fa-hotel',
        'subtitle' => 'Tổng rooms của host',
        'href' => Route::has('host.rooms.index') ? route('host.rooms.index') : null,
    ])

    @include('partials.host.dashboard.stat-card', [
        'title' => 'Room Types',
        'value' => $roomTypesCount ?? 0,
        'icon' => 'fa-solid fa-layer-group',
        'subtitle' => 'Tổng loại phòng',
        'href' => Route::has('host.room-types.index') ? route('host.room-types.index') : null,
    ])

    @include('partials.host.dashboard.stat-card', [
        'title' => 'Bookings (tháng này)',
        'value' => $bookingsThisMonth ?? 0,
        'icon' => 'fa-solid fa-calendar-check',
        'subtitle' => 'Tạo trong tháng',
        'href' => Route::has('host.bookings.index') ? route('host.bookings.index') : null,
    ])

    @include('partials.host.dashboard.stat-card', [
        'title' => 'Revenue (tháng này)',
        'value' => number_format((float) ($revenueThisMonth ?? 0)),
        'icon' => 'fa-solid fa-sack-dollar',
        'subtitle' => 'Sum total_price',
        'href' => Route::has('host.payments.index') ? route('host.payments.index') : null,
    ])
</div>

<div class="row g-3 mt-1">
    @include('partials.host.dashboard.stat-card', [
        'title' => 'Reviews',
        'value' => $reviewsCount ?? 0,
        'icon' => 'fa-solid fa-star',
        'subtitle' => $avgRating ? ('Avg: ' . number_format((float) $avgRating, 1) . '/5') : 'Chưa có review',
        'href' => null,
        'col' => 'col-12 col-md-6 col-xl-3',
    ])
</div>
