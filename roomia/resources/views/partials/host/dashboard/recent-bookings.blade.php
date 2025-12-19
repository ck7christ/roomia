<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div class="fw-semibold">
            <i class="fa-solid fa-list me-1"></i> Bookings gần đây
        </div>

        @if(Route::has('host.bookings.index'))
            <a class="btn btn-sm btn-outline-secondary" href="{{ route('host.bookings.index') }}">Xem tất cả</a>
        @endif
    </div>

    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Room / Type</th>
                    <th>Guest</th>
                    <th>Check-in/out</th>
                    <th>Status</th>
                    <th class="text-end">Total</th>
                </tr>
            </thead>

            <tbody>
                @forelse(($recentBookings ?? []) as $b)
                    <tr>
                        <td class="text-muted">#{{ $b->id }}</td>

                        <td>
                            <div class="fw-semibold">{{ data_get($b, 'roomType.room.title', '—') }}</div>
                            <div class="text-muted small">{{ data_get($b, 'roomType.name', '—') }}</div>
                        </td>

                        <td>
                            <div>{{ data_get($b, 'guest.name', '—') }}</div>
                            <div class="text-muted small">{{ data_get($b, 'guest.email', '') }}</div>
                        </td>

                        <td class="small">
                            <div><strong>In:</strong> {{ $b->check_in ? $b->check_in->format('d/m/Y') : '—' }}</div>
                            <div><strong>Out:</strong> {{ $b->check_out ? $b->check_out->format('d/m/Y') : '—' }}</div>
                        </td>

                        <td>
                            @include('partials.general.status-badge', ['status' => $b->status])
                        </td>

                        <td class="text-end">
                            {{ number_format((float) ($b->total_price ?? 0)) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Chưa có booking nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>