{{-- resources/views/partials/host/dashboard/revenue-monthly.blade.php --}}

@php
    $labels = ($monthlyRevenue ?? collect())->pluck('month')->values();
    $revenues = ($monthlyRevenue ?? collect())->pluck('revenue')->values();
    $bookings = ($monthlyRevenue ?? collect())->pluck('bookings')->values();
@endphp

<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <div>
                <h6 class="mb-0 fw-semibold">Doanh thu 6 tháng gần nhất</h6>
                <div class="small text-muted">Theo tháng</div>
            </div>
        </div>

        {{-- Chart --}}
        <div class="mb-3">
            <canvas data-rm-chart="revenue-monthly" data-labels='@json($labels)' data-revenues='@json($revenues)'
                data-bookings='@json($bookings)' height="120"></canvas>
        </div>

        {{-- Table fallback (vẫn giữ để dễ xem + phòng khi JS lỗi) --}}
        <div class="table-responsive">
            <table class="table table-sm align-middle mb-0">
                <thead>
                    <tr class="text-muted small">
                        <th style="width: 120px;">Tháng</th>
                        <th class="text-end">Doanh thu</th>
                        <th class="text-end">Số đặt phòng</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(($monthlyRevenue ?? collect()) as $row)
                        <tr>
                            <td class="fw-semibold">{{ $row['month'] ?? '-' }}</td>
                            <td class="text-end">{{ number_format((float) ($row['revenue'] ?? 0), 0, ',', '.') }} đ</td>
                            <td class="text-end">{{ (int) ($row['bookings'] ?? 0) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-3">Chưa có dữ liệu</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>