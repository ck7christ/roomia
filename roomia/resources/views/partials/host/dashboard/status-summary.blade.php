@php $counts = $bookingStatusCounts ?? collect(); @endphp

<div class="card">
    <div class="card-header fw-semibold">
        <i class="fa-solid fa-chart-pie me-1"></i> Thống kê trạng thái booking
    </div>

    <div class="card-body">
        @if($counts->count())
            <div class="row g-2">
                @foreach($counts as $status => $total)
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