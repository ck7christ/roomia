{{-- partials/admin/dashboard/quick-actions.blade.php --}}
<div class="card mb-3">
    <div class="card-header fw-semibold">
        <i class="fa-solid fa-bolt me-1"></i> Thao tác nhanh
    </div>

    <div class="list-group list-group-flush">
        <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
            href="{{ $usersUrl }}">
            Quản lý Users <i class="fa-solid fa-chevron-right text-muted"></i>
        </a>
        <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
            href="{{ $roomsUrl }}">
            Quản lý Rooms <i class="fa-solid fa-chevron-right text-muted"></i>
        </a>
        <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
            href="{{ $bookingsUrl }}">
            Quản lý Bookings <i class="fa-solid fa-chevron-right text-muted"></i>
        </a>
        <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
            href="{{ $reportsUrl }}">
            Báo cáo / Thống kê <i class="fa-solid fa-chevron-right text-muted"></i>
        </a>
    </div>
</div>