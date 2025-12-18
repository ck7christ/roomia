<div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
    <div>
        <h3 class="mb-1">Host Dashboard</h3>
        <div class="text-muted small">Tổng quan hoạt động của bạn</div>
    </div>

    <div class="d-flex flex-wrap gap-2">
        <span class="badge text-bg-light border">
            <i class="fa-solid fa-plane-arrival me-1"></i> Check-in hôm nay: <strong>{{ $checkInsToday ?? 0 }}</strong>
        </span>
        <span class="badge text-bg-light border">
            <i class="fa-solid fa-plane-departure me-1"></i> Check-out hôm nay:
            <strong>{{ $checkOutsToday ?? 0 }}</strong>
        </span>
        <span class="badge text-bg-light border">
            <i class="fa-solid fa-clock me-1"></i> Pending: <strong>{{ $pendingCount ?? 0 }}</strong>
        </span>
    </div>
</div>