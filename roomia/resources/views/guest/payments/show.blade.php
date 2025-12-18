{{-- resources/views/guest/payments/show.blade.php --}}
{{--
    CHI TIẾT THANH TOÁN (GUEST)
    - Mục đích: Guest xem trạng thái và thông tin chi tiết của 1 giao dịch.
    - Dữ liệu mong đợi từ Controller:
        $payment: Payment model (có thể load thêm booking)
--}}

@extends('layouts.guest')

@section('content')
    <div class="py-3">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h4 class="mb-1">Chi Tiết Thanh Toán</h4>
                <div class="text-muted small">
                    Thông tin giao dịch và trạng thái xử lý thanh toán.
                </div>
            </div>

            @php
                $hasPaymentsIndex = \Illuminate\Support\Facades\Route::has('guest.payments.index');
            @endphp

            @if ($hasPaymentsIndex)
                <a href="{{ route('guest.payments.index') }}" class="btn btn-outline-secondary">
                    <i class="fa-solid fa-arrow-left me-1"></i>
                    Quay Lại
                </a>
            @endif
        </div>

        @php
            $status = strtolower((string) ($payment->status ?? 'unknown'));
            $badgeClass = match ($status) {
                'paid', 'succeeded', 'success' => 'bg-success',
                'pending', 'requires_payment_method', 'processing' => 'bg-warning text-dark',
                'failed', 'canceled', 'cancelled' => 'bg-danger',
                default => 'bg-secondary',
            };

            $amount = $payment->amount ?? ($payment->total_amount ?? null);
            $currency = $payment->currency ?? 'VND';
            $booking = $payment->booking ?? null;

            $hasBookingsShow = \Illuminate\Support\Facades\Route::has('guest.bookings.show');
        @endphp

        <div class="row g-3">
            <div class="col-12 col-lg-7">
                <div class="card">
                    <div class="card-header bg-white d-flex align-items-center justify-content-between">
                        <div class="fw-semibold">
                            <i class="fa-solid fa-receipt me-1"></i>
                            Giao Dịch #{{ $payment->id }}
                        </div>
                        <span class="badge {{ $badgeClass }}">
                            {{ ucfirst($status) }}
                        </span>
                    </div>

                    <div class="card-body">
                        <dl class="row mb-0">
                            <dt class="col-5 col-md-4 text-muted">Số Tiền</dt>
                            <dd class="col-7 col-md-8 fw-semibold">
                                @if (!is_null($amount))
                                    {{ number_format((float) $amount, 0, ',', '.') }}
                                    <span class="text-muted">{{ $currency === 'VND' ? '₫' : $currency }}</span>
                                @else
                                    —
                                @endif
                            </dd>

                            <dt class="col-5 col-md-4 text-muted">Trạng Thái</dt>
                            <dd class="col-7 col-md-8">
                                <span class="badge {{ $badgeClass }}">{{ ucfirst($status) }}</span>
                            </dd>

                            <dt class="col-5 col-md-4 text-muted">Thời Gian Tạo</dt>
                            <dd class="col-7 col-md-8">
                                {{ optional($payment->created_at)->format('d/m/Y H:i') ?? '—' }}
                            </dd>

                            {{-- Các field dưới đây tuỳ DB bạn đặt (Stripe, transaction_id, paid_at...) --}}
                            @if (!empty($payment->provider))
                                <dt class="col-5 col-md-4 text-muted">Cổng Thanh Toán</dt>
                                <dd class="col-7 col-md-8">{{ ucfirst($payment->provider) }}</dd>
                            @endif

                            @if (!empty($payment->transaction_id))
                                <dt class="col-5 col-md-4 text-muted">Mã Giao Dịch</dt>
                                <dd class="col-7 col-md-8">{{ $payment->transaction_id }}</dd>
                            @endif

                            @if (!empty($payment->paid_at))
                                <dt class="col-5 col-md-4 text-muted">Thời Gian Thanh Toán</dt>
                                <dd class="col-7 col-md-8">
                                    {{ \Carbon\Carbon::parse($payment->paid_at)->format('d/m/Y H:i') }}
                                </dd>
                            @endif
                        </dl>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-5">
                <div class="card">
                    <div class="card-header bg-white">
                        <div class="fw-semibold">
                            <i class="fa-solid fa-calendar-check me-1"></i>
                            Đặt Phòng Liên Quan
                        </div>
                    </div>

                    <div class="card-body">
                        @if ($booking)
                            <div class="fw-semibold">
                                {{ $booking->roomType->name ?? ($booking->room_type_name ?? 'Đặt Phòng') }}
                            </div>
                            <div class="text-muted small mb-2">
                                {{ $booking->roomType->room->title ?? ($booking->room_title ?? '') }}
                            </div>

                            <div class="small">
                                <div class="text-muted">Thời Gian</div>
                                <div class="fw-semibold">
                                    @if ($booking->check_in && $booking->check_out)
                                        {{ \Carbon\Carbon::parse($booking->check_in)->format('d/m/Y') }}
                                        → {{ \Carbon\Carbon::parse($booking->check_out)->format('d/m/Y') }}
                                    @else
                                        —
                                    @endif
                                </div>
                            </div>

                            @if ($hasBookingsShow)
                                <div class="mt-3">
                                    <a href="{{ route('guest.bookings.show', $booking) }}"
                                        class="btn btn-outline-primary w-100">
                                        <i class="fa-regular fa-eye me-1"></i>
                                        Xem Đặt Phòng
                                    </a>
                                </div>
                            @endif
                        @else
                            <div class="text-muted">
                                Không có booking liên quan hoặc chưa load quan hệ.
                            </div>
                        @endif
                    </div>
                </div>

                <div class="alert alert-light border mt-3 mb-0">
                    <div class="fw-semibold mb-1">
                        <i class="fa-solid fa-circle-info me-1"></i>
                        Gợi Ý
                    </div>
                    <div class="small text-muted">
                        Nếu trạng thái “pending/processing” kéo dài, hãy thử tải lại trang hoặc liên hệ hỗ trợ.
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
