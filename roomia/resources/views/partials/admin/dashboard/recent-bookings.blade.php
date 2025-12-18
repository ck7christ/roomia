{{-- partials/admin/dashboard/recent-bookings.blade.php --}}
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div class="fw-semibold">
            <i class="fa-solid fa-clock-rotate-left me-1"></i> Booking gần đây
        </div>
        <a class="small text-decoration-none" href="{{ $bookingsUrl }}">Xem tất cả</a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Khách</th>
                    <th>Phòng</th>
                    <th>Check-in</th>
                    <th>Check-out</th>
                    <th class="text-end">Tổng</th>
                    <th>Trạng thái</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentBookings as $b)
                    @php
                        // Linh hoạt theo schema hiện tại của bạn
                        $guestName = $b->user->name ?? $b->guest_name ?? '—';
                        $roomName = $b->roomType->name ?? $b->room_type_name ?? '—';
                        $checkIn = $b->check_in ?? $b->checkIn ?? null;
                        $checkOut = $b->check_out ?? $b->checkOut ?? null;
                        $total = $b->total_price ?? $b->total ?? null;
                        $status = $b->status ?? '—';

                        // Map status -> badge bootstrap
                        $badge = 'secondary';
                        if (in_array($status, ['paid', 'confirmed', 'success']))
                            $badge = 'success';
                        if (in_array($status, ['pending']))
                            $badge = 'warning';
                        if (in_array($status, ['cancelled', 'canceled', 'failed']))
                            $badge = 'danger';
                    @endphp

                    <tr>
                        <td class="text-muted">#{{ $b->id ?? '—' }}</td>
                        <td>{{ $guestName }}</td>
                        <td class="text-muted">{{ $roomName }}</td>
                        <td>{{ $checkIn ? \Carbon\Carbon::parse($checkIn)->format('d/m/Y') : '—' }}</td>
                        <td>{{ $checkOut ? \Carbon\Carbon::parse($checkOut)->format('d/m/Y') : '—' }}</td>
                        <td class="text-end">
                            {{ $total !== null ? number_format((float) $total, 0, ',', '.') : '—' }}
                        </td>
                        <td><span class="badge text-bg-{{ $badge }}">{{ $status }}</span></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Chưa có booking nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>