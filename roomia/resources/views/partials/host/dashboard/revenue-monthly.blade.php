<div class="card">
    <div class="card-header fw-semibold">
        <i class="fa-solid fa-chart-line me-1"></i> Revenue 6 tháng gần nhất
    </div>

    <div class="table-responsive">
        <table class="table mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Tháng</th>
                    <th class="text-end">Bookings</th>
                    <th class="text-end">Revenue</th>
                </tr>
            </thead>
            <tbody>
                @foreach(($monthlyRevenue ?? []) as $row)
                    <tr>
                        <td>{{ $row['month'] }}</td>
                        <td class="text-end">{{ $row['bookings'] }}</td>
                        <td class="text-end">{{ number_format((float) $row['revenue']) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="card-footer bg-transparent text-muted small">
        Gợi ý: sau này bạn có thể gắn Chart.js để vẽ biểu đồ, hiện tại dùng table để ổn định (không JS).
    </div>
</div>