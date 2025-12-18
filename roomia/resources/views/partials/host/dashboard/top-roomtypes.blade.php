<div class="card">
    <div class="card-header fw-semibold">
        <i class="fa-solid fa-ranking-star me-1"></i> Top Room Types (30 ngày)
    </div>

    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Room</th>
                    <th>Room Type</th>
                    <th class="text-end">Bookings</th>
                    <th class="text-end">Revenue</th>
                </tr>
            </thead>
            <tbody>
                @forelse(($topRoomTypes ?? []) as $x)
                    <tr>
                        <td class="fw-semibold">{{ $x->room_title ?? '—' }}</td>
                        <td>{{ $x->room_type ?? '—' }}</td>
                        <td class="text-end">{{ $x->bookings }}</td>
                        <td class="text-end">{{ number_format((float) $x->revenue) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">Chưa đủ dữ liệu để thống kê.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>