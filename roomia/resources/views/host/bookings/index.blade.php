{{-- resources/views/host/bookings/index.blade.php --}}
@extends('layouts.host')

@section('content')
    <div class="container py-4">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1 class="h3 mb-1">Đặt phòng của khách</h1>
                <p class="text-muted mb-0">Danh sách booking thuộc các phòng của bạn.</p>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong>Bookings</strong>
                <span class="text-muted small">
                    Tổng: {{ $bookings->total() ?? $bookings->count() }}
                </span>
            </div>

            @if ($bookings->count())
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Mã</th>
                                <th>Khách sạn</th>
                                <th>Loại phòng</th>
                                <th>Khách</th>
                                <th>Nhận phòng</th>
                                <th>Trả phòng</th>
                                <th class="text-center">Số khách</th>
                                <th class="text-end">Tổng tiền</th>
                                <th class="text-center">Trạng thái</th>
                                <th class="text-end">Thao tác</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($bookings as $booking)
                                @php
                                    $roomType = $booking->roomType;
                                    $room = $roomType?->room;
                                    $guest = $booking->guest;

                                    $status = strtolower($booking->status ?? 'pending');
                                    $badge = match ($status) {
                                        'confirmed' => 'bg-success',
                                        'cancelled' => 'bg-secondary',
                                        'rejected' => 'bg-danger',
                                        default => 'bg-warning text-dark',
                                    };
                                @endphp

                                <tr>
                                    <td class="fw-semibold">#{{ $booking->id }}</td>
                                    <td>{{ $room?->title ?? '—' }}</td>
                                    <td>{{ $roomType?->name ?? '—' }}</td>

                                    <td>
                                        @if ($guest)
                                            <div class="fw-semibold">{{ $guest->name }}</div>
                                            <div class="text-muted small">{{ $guest->email }}</div>
                                        @else
                                            —
                                        @endif
                                    </td>

                                    <td>{{ $booking->check_in?->format('d/m/Y') ?? '—' }}</td>
                                    <td>{{ $booking->check_out?->format('d/m/Y') ?? '—' }}</td>
                                    <td class="text-center">{{ $booking->guest_count ?? 0 }}</td>

                                    <td class="text-end">
                                        {{ number_format($booking->total_price ?? 0) }} đ
                                    </td>

                                    <td class="text-center">
                                        <span class="badge {{ $badge }}">
                                            {{ ucfirst($status) }}
                                        </span>
                                    </td>

                                    <td class="text-end">
                                        <a href="{{ route('host.bookings.show', $booking) }}"
                                            class="btn btn-outline-primary btn-sm">
                                            <i class="fa fa-eye me-1"></i> Xem
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>

                @if (method_exists($bookings, 'links'))
                    <div class="card-footer">
                        {{ $bookings->links() }}
                    </div>
                @endif
            @else
                <div class="card-body">
                    <p class="text-muted mb-0">Chưa có booking nào cho phòng của bạn.</p>
                </div>
            @endif
        </div>

    </div>
@endsection