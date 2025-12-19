{{-- resources/views/partials/host/dashboard/status-summary.blade.php --}}

@php
    $counts = $bookingStatusCounts ?? collect();

    // Sắp xếp status theo thứ tự quen thuộc (nếu DB dùng các key này)
    $order = ['pending', 'confirmed', 'completed', 'cancelled'];
    $sorted = $counts->sortBy(function ($v, $k) use ($order) {
        $i = array_search($k, $order, true);
        return $i === false ? 999 : $i;
    });

    $labels = $sorted->keys()->values();
    $values = $sorted->values()->values();

    $totalAll = (int) $values->sum();
@endphp

<div class="card">
    <div class="card-header fw-semibold d-flex align-items-center justify-content-between">
        <div>
            <i class="fa-solid fa-chart-pie me-1"></i> Thống kê trạng thái booking
        </div>
        <span class="text-muted small">Tổng: <strong>{{ $totalAll }}</strong></span>
    </div>

    <div class="card-body">
        @if($counts->count())
            {{-- Chart --}}
            <div class="mb-3" style="min-height: 220px;">
                <canvas data-rm-chart="booking-status" data-labels='@json($labels)' data-values='@json($values)'></canvas>
            </div>

            {{-- List fallback (giữ lại để dễ đọc + phòng khi JS lỗi) --}}
            <div class="row g-2">
                @foreach($sorted as $status => $total)
                    <div class="col-12 col-md-6">
                        <div class="d-flex align-items-center justify-content-between border rounded p-2">
                            <div>@include('partials.general.status-badge', ['status' => $status])</div>
                            <div class="fw-semibold">{{ $total }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-muted">Chưa có dữ liệu.</div>
        @endif
    </div>
</div>