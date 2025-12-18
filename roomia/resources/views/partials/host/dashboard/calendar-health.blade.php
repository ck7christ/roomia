<div class="card">
    <div class="card-header d-flex flex-wrap gap-2 align-items-center justify-content-between">
        <div class="fw-semibold"><i class="fa-solid fa-calendar-days me-1"></i> Calendar health (30 ngày tới)</div>

        @if(!empty($calendarSummary))
            <div class="d-flex flex-wrap gap-2">
                <span class="badge text-bg-light border">Tracked:
                    <strong>{{ $calendarSummary['room_types_tracked'] }}</strong></span>
                <span class="badge text-bg-light border">Closed days:
                    <strong>{{ $calendarSummary['closed_days_total'] }}</strong></span>
                <span class="badge text-bg-light border">Override:
                    <strong>{{ $calendarSummary['override_days_total'] }}</strong></span>
                <span class="badge text-bg-light border">Low avail:
                    <strong>{{ $calendarSummary['low_availability_total'] }}</strong></span>
            </div>
        @endif
    </div>

    <div class="card-body">
        @if(empty($calendarSummary))
            <div class="text-muted">
                Không thấy RoomCalendar hoặc thiếu cột date ⇒ bỏ qua block này (dashboard vẫn chạy bình thường).
            </div>
        @else
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Room</th>
                            <th>Room Type</th>
                            <th class="text-end">Days</th>
                            <th class="text-end">Closed</th>
                            <th class="text-end">Override</th>
                            <th class="text-end">Low avail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(($roomTypesForTable ?? []) as $rt)
                            @php
                                $m = ($calendarByRoomType[$rt->id] ?? null);
                            @endphp
                            <tr>
                                <td class="fw-semibold">{{ optional($rt->room)->title ?? '—' }}</td>
                                <td>{{ $rt->name ?? '—' }}</td>
                                <td class="text-end">{{ $m['days'] ?? 0 }}</td>
                                <td class="text-end">{{ $m['closed_days'] ?? 0 }}</td>
                                <td class="text-end">{{ $m['price_override_days'] ?? 0 }}</td>
                                <td class="text-end">{{ $m['low_availability_days'] ?? 0 }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Chưa có room types.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="text-muted small mt-2">
                Gợi ý: Closed/Override/Low availability giúp host biết phòng đang bị “đóng” quá nhiều, hoặc sắp hết tồn kho.
            </div>
        @endif
    </div>
</div>