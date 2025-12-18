{{-- partials/admin/dashboard/stats.blade.php --}}
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card h-100">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small">Tổng Users</div>
                    <div class="h4 mb-0">{{ $stats['users'] ?? '—' }}</div>
                </div>
                <div class="fs-3 text-muted"><i class="fa-solid fa-users"></i></div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card h-100">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small">Tổng Hosts</div>
                    <div class="h4 mb-0">{{ $stats['hosts'] ?? '—' }}</div>
                </div>
                <div class="fs-3 text-muted"><i class="fa-solid fa-user-tie"></i></div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card h-100">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small">Tổng Rooms</div>
                    <div class="h4 mb-0">{{ $stats['rooms'] ?? '—' }}</div>
                </div>
                <div class="fs-3 text-muted"><i class="fa-solid fa-hotel"></i></div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card h-100">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small">Bookings (30 ngày)</div>
                    <div class="h4 mb-0">{{ $stats['bookings_30d'] ?? '—' }}</div>
                </div>
                <div class="fs-3 text-muted"><i class="fa-solid fa-calendar-check"></i></div>
            </div>
        </div>
    </div>
</div>