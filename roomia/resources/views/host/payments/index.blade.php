@extends('layouts.host')

@section('content')
    <div class="container py-4">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1 class="h3 mb-1">Thanh toán</h1>
                <p class="text-muted mb-0">Danh sách thanh toán từ các booking thuộc phòng của bạn</p>
            </div>
        </div>

        @include('partials.general.flash-message')

        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong>Payments</strong>
                <span class="text-muted small">
                    Tổng:
                    {{ method_exists($payments, 'total') ? $payments->total() : $payments->count() }}
                </span>
            </div>

            @if ($payments->count())
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Booking</th>
                                <th>Khách</th>
                                <th>Phòng</th>
                                <th class="text-center">Phương thức</th>
                                <th class="text-end">Số tiền</th>
                                <th class="text-center">Trạng thái</th>
                                <th>Ngày tạo</th>
                                <th class="text-end">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($payments as $payment)
                                @php
                                    $status = strtolower($payment->status ?? 'pending');
                                    $badge = match ($status) {
                                        'paid', 'completed', 'succeeded' => 'bg-success',
                                        'failed' => 'bg-danger',
                                        'refunded' => 'bg-secondary',
                                        default => 'bg-warning text-dark',
                                    };

                                    $method = strtoupper($payment->method ?? ($payment->payment_method ?? '—'));

                                    $booking = $payment->booking ?? null;
                                    $guest = $booking?->guest ?? null;
                                    $roomType = $booking?->roomType ?? null;
                                    $room = $roomType?->room ?? null;

                                    $amount = $payment->amount ?? $payment->total_amount ?? $booking?->total_price ?? 0;
                                @endphp

                                <tr>
                                    <td class="fw-semibold">#{{ $payment->id }}</td>

                                    <td>
                                        @if ($booking)
                                            <a href="{{ route('host.bookings.show', $booking) }}" class="text-decoration-none">
                                                #{{ $booking->id }}
                                            </a>
                                        @else
                                            —
                                        @endif
                                    </td>

                                    <td>
                                        {{ $guest?->name ?? '—' }}
                                        <div class="text-muted small">{{ $guest?->email }}</div>
                                    </td>

                                    <td>{{ $room?->title ?? '—' }}</td>

                                    <td class="text-center">
                                        <span class="badge bg-light text-dark border">{{ $method }}</span>
                                    </td>

                                    <td class="text-end">{{ number_format($amount) }} đ</td>

                                    <td class="text-center">
                                        <span class="badge {{ $badge }}">{{ ucfirst($status) }}</span>
                                    </td>

                                    <td>
                                        {{ $payment->created_at?->format('d/m/Y H:i') ?? '—' }}
                                    </td>

                                    <td class="text-end">
                                        <a href="{{ route('host.payments.show', $payment) }}"
                                            class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-eye me-1"></i>Xem
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if (method_exists($payments, 'links'))
                    <div class="card-footer">
                        {{ $payments->links() }}
                    </div>
                @endif
            @else
                <div class="card-body">
                    <p class="text-muted mb-0">Chưa có thanh toán nào.</p>
                </div>
            @endif
        </div>

    </div>
@endsection