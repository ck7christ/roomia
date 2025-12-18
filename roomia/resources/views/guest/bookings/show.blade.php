{{-- resources/views/guest/bookings/show.blade.php --}}
@extends('layouts.guest')

@section('title', 'Chi tiết đặt phòng')

@section('content')
    @php
        $roomType = $booking->roomType;
        $room = $roomType?->room;

        $ci = $booking->check_in ? \Carbon\Carbon::parse($booking->check_in) : null;
        $co = $booking->check_out ? \Carbon\Carbon::parse($booking->check_out) : null;

        $payment = $booking->payment ?? null;

        $canPay =
            \Illuminate\Support\Facades\Route::has('guest.payments.create') &&
            in_array($booking->status, ['pending', 'unpaid', 'awaiting_payment'], true);

        $cancelRoute = \Illuminate\Support\Facades\Route::has('guest.bookings.cancel')
            ? route('guest.bookings.cancel', $booking)
            : null;

        $review = $booking->review ?? null;
        $canReview = !$review && $co && $co->isPast() && \Illuminate\Support\Facades\Route::has('guest.reviews.create');
    @endphp

    <div class="container py-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
            <div>
                <h1 class="h4 mb-1">Chi tiết đặt phòng #{{ $booking->id }}</h1>
                <div class="text-muted small">
                    @include('partials.guest.bookings.status-badge', ['status' => $booking->status])
                </div>
            </div>

            <div class="d-flex gap-2">
                @if (\Illuminate\Support\Facades\Route::has('guest.bookings.index'))
                    <a class="btn btn-outline-secondary" href="{{ route('guest.bookings.index') }}">
                        <i class="fa-solid fa-arrow-left me-2"></i> Danh sách
                    </a>
                @endif

                @if ($canPay)
                    <a class="btn btn-primary" href="{{ route('guest.payments.create', $booking) }}">
                        <i class="fa-solid fa-credit-card me-2"></i> Thanh toán
                    </a>
                @endif

                @if ($cancelRoute && method_exists($booking, 'isCancellable') ? $booking->isCancellable() : true)
                    <button class="btn btn-outline-danger" type="button" data-bs-toggle="modal"
                        data-bs-target="#cancelBookingModal">
                        <i class="fa-solid fa-ban me-2"></i> Hủy booking
                    </button>
                @endif
            </div>
        </div>

        @includeIf('partials.general.alerts')

        <div class="row g-3">
            {{-- Thông tin chỗ ở --}}
            <div class="col-12 col-lg-7">
                <div class="card mb-3">
                    <div class="card-header bg-white fw-semibold">
                        <i class="fa-solid fa-hotel me-2 text-primary"></i> Thông tin lưu trú
                    </div>
                    <div class="card-body">
                        <div class="fw-semibold">{{ $room?->title ?? '—' }}</div>
                        <div class="text-muted small mb-3">{{ $roomType?->name ?? '—' }}</div>

                        <div class="row g-2">
                            <div class="col-6">
                                <div class="text-muted small">Nhận phòng</div>
                                <div>{{ $ci ? $ci->format('d/m/Y') : '—' }}</div>
                            </div>
                            <div class="col-6">
                                <div class="text-muted small">Trả phòng</div>
                                <div>{{ $co ? $co->format('d/m/Y') : '—' }}</div>
                            </div>

                            <div class="col-6">
                                <div class="text-muted small">Số khách</div>
                                <div>{{ (int) $booking->guest_count }}</div>
                            </div>
                            <div class="col-6">
                                <div class="text-muted small">Tổng tiền</div>
                                <div class="fw-semibold">
                                    {{ number_format((float) $booking->total_price, 0, ',', '.') }} đ
                                </div>
                            </div>
                        </div>

                        {{-- Voucher đã dùng --}}
                        @if (!empty($booking->voucher_code))
                            <hr class="my-3">
                            <div class="d-flex align-items-start gap-3">
                                <div class="text-primary">
                                    <i class="fa-solid fa-ticket fs-5"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold">Voucher: {{ $booking->voucher_code }}</div>
                                    <div class="text-muted small">
                                        Giảm: {{ number_format((float) $booking->voucher_discount_amount, 0, ',', '.') }} đ
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Review --}}
                <div class="card">
                    <div class="card-header bg-white fw-semibold">
                        <i class="fa-solid fa-star me-2 text-warning"></i> Đánh giá
                    </div>
                    <div class="card-body">
                        @include('partials.guest.bookings.review', [
                            'booking' => $booking,
                            'review' => $review,
                            'canReview' => $canReview,
                        ])
                    </div>
                </div>
            </div>

            {{-- Thanh toán --}}
            <div class="col-12 col-lg-5">
                <div class="card">
                    <div class="card-header bg-white fw-semibold">
                        <i class="fa-solid fa-money-bill-wave me-2 text-success"></i> Thanh Toán
                    </div>
                    <div class="card-body">
                        @if ($payment)
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="text-muted small">Phương thức</div>
                                    <div class="fw-semibold">{{ $payment->method ?? '—' }}</div>
                                </div>
                                <div class="col-6">
                                    <div class="text-muted small">Trạng thái</div>
                                    <div class="fw-semibold">{{ $payment->status ?? '—' }}</div>
                                </div>
                                <div class="col-6">
                                    <div class="text-muted small">Số tiền</div>
                                    <div class="fw-semibold">
                                        {{ number_format((float) ($payment->amount ?? 0), 0, ',', '.') }}
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-muted small">Thời gian</div>
                                    <div class="fw-semibold">
                                        {{ $payment->created_at ? $payment->created_at->format('d/m/Y H:i') : '—' }}
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-muted">
                                Chưa có thông tin thanh toán cho booking này.
                            </div>

                            @if ($canPay)
                                <div class="mt-3">
                                    <a class="btn btn-primary w-100" href="{{ route('guest.payments.create', $booking) }}">
                                        <i class="fa-solid fa-credit-card me-2"></i> Thanh toán ngay
                                    </a>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>

                {{-- Actions hint --}}
                <div class="text-muted small mt-3">
                    *Voucher/giá có thể được kiểm tra lại theo điều kiện tại thời điểm tạo booking.
                </div>
            </div>
        </div>
    </div>

    {{-- Cancel modal (không inline JS) --}}
    @if ($cancelRoute && (method_exists($booking, 'isCancellable') ? $booking->isCancellable() : true))
        <div class="modal fade" id="cancelBookingModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Hủy booking #{{ $booking->id }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <form method="POST" action="{{ $cancelRoute }}">
                        @csrf
                        @method('POST')

                        <div class="modal-body">
                            <div class="mb-2 text-muted">
                                Bạn chắc chắn muốn hủy booking này?
                            </div>

                            <label class="form-label">Lý do (tuỳ chọn)</label>
                            <textarea name="reason" rows="4" class="form-control">{{ old('reason') }}</textarea>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
                            <button type="submit" class="btn btn-danger">Xác nhận hủy</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection
