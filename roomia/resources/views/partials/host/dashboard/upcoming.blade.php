<div class="card">
    <div class="card-header fw-semibold">
        <i class="fa-solid fa-calendar-days me-1"></i> 7 ngày tới
    </div>

    <div class="card-body">
        <div class="fw-semibold mb-2"><i class="fa-solid fa-plane-arrival me-1"></i> Check-in</div>
        <div class="list-group mb-3">
            @forelse(($upcomingCheckIns ?? []) as $b)
                <div class="list-group-item">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="fw-semibold">#{{ $b->id }} —
                                {{ optional(optional($b->roomType)->room)->title ?? '—' }}</div>
                            <div class="text-muted small">{{ optional($b->roomType)->name ?? '—' }}</div>
                            <div class="small mt-1"><strong>{{ optional($b->check_in)->format('d/m/Y') }}</strong> —
                                {{ optional($b->guest)->name ?? '—' }}</div>
                        </div>
                        <div>@include('partials.general.status-badge', ['status' => $b->status])</div>
                    </div>
                </div>
            @empty
                <div class="list-group-item text-muted">Không có check-in.</div>
            @endforelse
        </div>

        <div class="fw-semibold mb-2"><i class="fa-solid fa-plane-departure me-1"></i> Check-out</div>
        <div class="list-group">
            @forelse(($upcomingCheckOuts ?? []) as $b)
                <div class="list-group-item">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="fw-semibold">#{{ $b->id }} —
                                {{ optional(optional($b->roomType)->room)->title ?? '—' }}</div>
                            <div class="text-muted small">{{ optional($b->roomType)->name ?? '—' }}</div>
                            <div class="small mt-1"><strong>{{ optional($b->check_out)->format('d/m/Y') }}</strong> —
                                {{ optional($b->guest)->name ?? '—' }}</div>
                        </div>
                        <div>@include('partials.general.status-badge', ['status' => $b->status])</div>
                    </div>
                </div>
            @empty
                <div class="list-group-item text-muted">Không có check-out.</div>
            @endforelse
        </div>
    </div>
</div>