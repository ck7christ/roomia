@extends('layouts.host')

@section('content')
    <div class="container py-4">

        @php
            $status = strtolower($payment->status ?? 'pending');
            $badge = match ($status) {
                'paid', 'completed', 'succeeded' => 'bg-success',
                'failed' => 'bg-danger',
                'refunded' => 'bg-secondary',
                default => 'bg-warning text-dark',
            };

            $method = strtolower($payment->method ?? ($payment->payment_method ?? ''));
            $booking = $payment->booking ?? null;
            $guest = $booking?->guest ?? null;
            $roomType = $booking?->roomType ?? null;
            $room = $roomType?->room ?? null;

            $amount = $payment->amount ?? $payment->total_amount ?? $booking?->total_price ?? 0;

            // COD confirm: bạn có route payments.confirmCod
            $canConfirmCod = ($method === 'cod' || $method === 'cash_on_delivery')
                && in_array($status, ['pending', 'unpaid', 'processing'], true);
        @endphp

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1 class="h3 mb-1">Payment #{{ $payment->id }}</h1>
                <span class="badge {{ $badge }}">{{ ucfirst($status) }}</span>
            </div>

            <a href="{{ route('host.payments.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i>Quay lại
            </a>
        </div>

        @include('partials.general.flash-message')

        <div class="row g-3">

            {{-- Thông tin thanh toán --}}
            <div class="col-lg-8">
                <div class="card shadow-sm mb-3">
                    <div class="card-header"><strong>Chi tiết thanh toán</strong></div>
                    <div class="card-body">
                        <dl class="row mb-0">
                            <dt class="col-sm-4">Số tiền</dt>
                            <dd class="col-sm-8">{{ number_format($amount) }} đ</dd>

                            <dt class="col-sm-4">Phương thức</dt>
                            <dd class="col-sm-8">
                                <span class="badge bg-light text-dark border">{{ strtoupper($method ?: '—') }}</span>
                            </dd>

                            <dt class="col-sm-4">Trạng thái</dt>
                            <dd class="col-sm-8">
                                <span class="badge {{ $badge }}">{{ ucfirst($status) }}</span>
                            </dd>

                            <dt class="col-sm-4">Ngày tạo</dt>
                            <dd class="col-sm-8">{{ $payment->created_at?->format('d/m/Y H:i') ?? '—' }}</dd>

                            <dt class="col-sm-4">Cập nhật</dt>
                            <dd class="col-sm-8">{{ $payment->updated_at?->format('d/m/Y H:i') ?? '—' }}</dd>
                        </dl>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-header"><strong>Booking liên quan</strong></div>
                    <div class="card-body">
                        @if ($booking)
                            <dl class="row mb-0">
                                <dt class="col-sm-4">Booking</dt>
                                <dd class="col-sm-8">
                                    <a href="{{ route('host.bookings.show', $booking) }}" class="text-decoration-none">
                                        #{{ $booking->id }}
                                    </a>
                                </dd>

                                <dt class="col-sm-4">Phòng</dt>
                                <dd class="col-sm-8">{{ $room?->title ?? '—' }}</dd>

                                <dt class="col-sm-4">Loại phòng</dt>
                                <dd class="col-sm-8">{{ $roomType?->name ?? '—' }}</dd>

                                <dt class="col-sm-4">Check-in</dt>
                                <dd class="col-sm-8">{{ $booking->check_in?->format('d/m/Y') ?? '—' }}</dd>

                                <dt class="col-sm-4">Check-out</dt>
                                <dd class="col-sm-8">{{ $booking->check_out?->format('d/m/Y') ?? '—' }}</dd>

                                <dt class="col-sm-4">Tổng booking</dt>
                                <dd class="col-sm-8">{{ number_format($booking->total_price ?? 0) }} đ</dd>
                            </dl>
                        @else
                            <p class="text-muted mb-0">Không tìm thấy booking liên quan.</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Khách + thao tác --}}
            <div class="col-lg-4">
                <div class="card shadow-sm mb-3">
                    <div class="card-header"><strong>Khách</strong></div>
                    <div class="card-body">
                        @if ($guest)
                            <div class="fw-semibold">{{ $guest->name }}</div>
                            <div class="text-muted">{{ $guest->email }}</div>
                        @else
                            <p class="text-muted mb-0">Không có thông tin khách.</p>
                        @endif
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-header"><strong>Thao tác</strong></div>
                    <div class="card-body">
                        @if ($canConfirmCod)
                            <form action="{{ route('host.payments.confirmCod', $payment) }}" method="POST" class="js-confirm"
                                data-confirm="Xác nhận bạn đã nhận tiền COD cho payment #{{ $payment->id }}?">
                                @csrf

                                <button type="submit" class="btn btn-success w-100">
                                    <i class="fas fa-check me-1"></i>Xác nhận đã nhận COD
                                </button>
                            </form>
                        @else
                            <p class="text-muted mb-0">
                                Không có thao tác phù hợp cho trạng thái/phương thức hiện tại.
                            </p>
                        @endif
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection