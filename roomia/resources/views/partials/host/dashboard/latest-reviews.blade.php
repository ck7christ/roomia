<div class="card">
    <div class="card-header fw-semibold">
        <i class="fa-solid fa-star me-1"></i> Reviews mới nhất
    </div>

    <div class="list-group list-group-flush">
        @forelse(($latestReviewedBookings ?? []) as $b)
            @php
                $r = $b->review;
                $rating = (int) ($r->rating ?? 0);
                $comment = $r->comment ?? $r->content ?? '';
            @endphp
            <div class="list-group-item">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="me-2">
                        <div class="fw-semibold">{{ optional(optional($b->roomType)->room)->title ?? '—' }}</div>
                        <div class="text-muted small">{{ optional($b->roomType)->name ?? '—' }}</div>
                        <div class="text-muted small mt-1">{{ \Illuminate\Support\Str::limit($comment, 90) }}</div>
                    </div>
                    <div class="text-nowrap">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fa-star {{ $i <= $rating ? 'fa-solid' : 'fa-regular' }}"></i>
                        @endfor
                    </div>
                </div>
            </div>
        @empty
            <div class="list-group-item text-muted">Chưa có review.</div>
        @endforelse
    </div>
</div>