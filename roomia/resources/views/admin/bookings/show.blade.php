@extends('layouts.admin')
@section('title', 'Chi tiết Booking')

@section('content')
    @php
        $statuses = [
            \App\Models\Booking::STATUS_PENDING,
            \App\Models\Booking::STATUS_CONFIRMED,
            \App\Models\Booking::STATUS_COMPLETED,
            \App\Models\Booking::STATUS_CANCELLED,
        ];

        $badge = match ($booking->status) {
            \App\Models\Booking::STATUS_PENDING => 'warning',
            \App\Models\Booking::STATUS_CONFIRMED => 'primary',
            \App\Models\Booking::STATUS_COMPLETED => 'success',
            \App\Models\Booking::STATUS_CANCELLED => 'secondary',
            default => 'light',
        };
    @endphp

    <div class="py-4 px-3">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h4 class="mb-1">Booking #{{ $booking->id }}</h4>
                <div class="text-muted small">
                    Tạo lúc: {{ optional($booking->created_at)->format('d/m/Y H:i') ?? '-' }}
                </div>
            </div>
            <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-left me-1"></i> Quay lại
            </a>
        </div>

        <div class="row g-3">
            {{-- Left: main info --}}
            <div class="col-12 col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div>
                                <div class="text-muted small">Trạng thái</div>
                                <span class="badge text-bg-{{ $badge }}">{{ strtoupper($booking->status ?? '-') }}</span>
                            </div>

                            <div class="text-end">
                                <div class="text-muted small">Tổng tiền</div>
                                <div class="fw-semibold fs-5">
                                    {{ number_format((float) ($booking->total_price ?? 0), 0, ',', '.') }} đ
                                </div>
                                @if (!empty($booking->voucher_discount_amount) && (float) $booking->voucher_discount_amount > 0)
                                    <div class="text-muted small">
                                        Giảm: -{{ number_format((float) $booking->voucher_discount_amount, 0, ',', '.') }} đ
                                        @if (!empty($booking->voucher_code))
                                            <span class="ms-1">({{ $booking->voucher_code }})</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <div class="text-muted small">Check-in</div>
                                <div class="fw-semibold">{{ optional($booking->check_in)->format('d/m/Y') ?? '-' }}</div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="text-muted small">Check-out</div>
                                <div class="fw-semibold">{{ optional($booking->check_out)->format('d/m/Y') ?? '-' }}</div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="text-muted small">Số khách</div>
                                <div class="fw-semibold">{{ (int) ($booking->guest_count ?? 0) }}</div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="text-muted small">Phòng</div>
                                <div class="fw-semibold">
                                    {{ $booking->roomType?->room?->title ?? '-' }}
                                </div>
                                <div class="text-muted small">
                                    {{ $booking->roomType?->name ?? '' }}
                                </div>
                            </div>
                        </div>

                        @if ($booking->status === \App\Models\Booking::STATUS_CANCELLED)
                            <hr>
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <div class="text-muted small">Huỷ lúc</div>
                                    <div class="fw-semibold">
                                        {{ optional($booking->cancelled_at)->format('d/m/Y H:i') ?? '-' }}
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="text-muted small">Lý do</div>
                                    <div class="fw-semibold">{{ $booking->cancel_reason ?? '-' }}</div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Review (nếu có) --}}
                <div class="card shadow-sm border-0 mt-3">
                    <div class="card-body">
                        <div class="fw-semibold mb-2">Đánh giá</div>

                        @if ($booking->review)
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="text-muted small">
                                    {{ optional($booking->review->created_at)->format('d/m/Y H:i') ?? '-' }}
                                </div>
                                <div class="fw-semibold">
                                    Rating: {{ $booking->review->rating ?? '-' }}
                                </div>
                            </div>
                            <div class="mt-2">
                                {{ $booking->review->comment ?? ($booking->review->content ?? 'Không có nội dung.') }}
                            </div>
                        @else
                            <div class="text-muted">Chưa có đánh giá.</div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Right: guest + actions --}}
            <div class="col-12 col-lg-4">
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-body">
                        <div class="fw-semibold mb-2">Khách hàng</div>
                        <div class="fw-semibold">{{ $booking->guest?->name ?? '-' }}</div>
                        <div class="text-muted small">{{ $booking->guest?->email ?? '' }}</div>
                    </div>
                </div>

                {{-- Update status --}}
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-body">
                        <div class="fw-semibold mb-2">Cập nhật trạng thái</div>

                        <form method="POST" action="{{ route('admin.bookings.update', $booking) }}" class="d-flex gap-2">
                            @csrf
                            @method('PUT')

                            <select name="status" class="form-select">
                                @foreach ($statuses as $st)
                                    <option value="{{ $st }}" @selected(($booking->status ?? '') === $st)>
                                        {{ strtoupper($st) }}
                                    </option>
                                @endforeach
                            </select>

                            <button type="submit" class="btn btn-primary">
                                Lưu
                            </button>
                        </form>

                        @error('status')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Extra actions --}}
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="fw-semibold mb-2">Thao tác</div>


                        <form method="POST" action="{{ route('admin.bookings.cancel', $booking) }}" class="mb-2">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-outline-secondary w-100">
                                Huỷ booking
                            </button>
                        </form>

                        <form method="POST" action="{{ route('admin.bookings.destroy', $booking) }}">
                            @csrf
                            @method('DELETE')
                            {{-- TODO: nếu cần confirm, làm modal confirm (không inline JS) --}}
                            <button type="submit" class="btn btn-outline-danger w-100">
                                Xoá booking
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Payment (nếu có) --}}
                @if ($booking->latestPayment)
                    <div class="card shadow-sm border-0 mt-3">
                        <div class="card-body">
                            <div class="fw-semibold mb-2">Thanh toán</div>
                            <div class="text-muted small">Payment #{{ $booking->latestPayment->id }}</div>
                            <div class="text-muted small">
                                {{ optional($booking->latestPayment->created_at)->format('d/m/Y H:i') ?? '-' }}
                            </div>
                            @if (isset($booking->latestPayment->status))
                                <div class="mt-2">
                                    <span class="badge text-bg-light border">{{ $booking->latestPayment->status }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection