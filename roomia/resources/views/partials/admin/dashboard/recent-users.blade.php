{{-- partials/admin/dashboard/recent-users.blade.php --}}
<div class="card">
    <div class="card-header fw-semibold">
        <i class="fa-solid fa-user-plus me-1"></i> User mới
    </div>

    <div class="card-body">
        @forelse($recentUsers as $u)
            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                <div>
                    <div class="fw-semibold">{{ $u->name ?? '—' }}</div>
                    <div class="text-muted small">{{ $u->email ?? '' }}</div>
                </div>
                <div class="text-muted small">
                    {{ !empty($u->created_at) ? \Carbon\Carbon::parse($u->created_at)->format('d/m/Y') : '' }}
                </div>
            </div>
        @empty
            <div class="text-muted">Chưa có user mới.</div>
        @endforelse
    </div>
</div>