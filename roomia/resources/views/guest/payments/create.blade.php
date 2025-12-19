{{-- resources/views/guest/payments/create.blade.php --}}

@extends('layouts.guest')

@section('content')
    <div class="py-3">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h4 class="mb-1">Thanh toán</h4>
                <div class="text-muted small">Chọn phương thức thanh toán cho booking của bạn.</div>
            </div>

            <a href="{{ route('guest.bookings.show', $booking) }}" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-left me-1"></i> Quay lại booking
            </a>
        </div>

        {{-- Errors / flash --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <div class="fw-semibold mb-1">Không thể tiếp tục:</div>
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-warning">{{ session('error') }}</div>
        @endif

        @php
            $nights = null;
            if ($booking->check_in && $booking->check_out) {
                $nights = \Carbon\Carbon::parse($booking->check_in)->diffInDays(
                    \Carbon\Carbon::parse($booking->check_out),
                );
            }

            $amount = (int) ($booking->total_price ?? 0);
            $methodStripe = \App\Models\Payment::METHOD_STRIPE;
            $methodCod = \App\Models\Payment::METHOD_COD;

            $selected = old('method', $methodStripe);
        @endphp

        <div class="row g-3">
            {{-- Booking info --}}
            <div class="col-12 col-lg-7">
                <div class="card">
                    <div class="card-header bg-white">
                        <div class="fw-semibold">
                            <i class="fa-solid fa-receipt me-1"></i>
                            Thông tin booking
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <div class="fw-semibold">
                                {{ optional($booking->roomType)->name ?? 'Loại phòng' }}
                            </div>
                            <div class="text-muted small">
                                {{ optional(optional($booking->roomType)->room)->title ?? '' }}
                            </div>
                        </div>

                        <div class="row g-2 small">
                            <div class="col-12 col-md-6">
                                <div class="text-muted">Nhận phòng</div>
                                <div class="fw-semibold">
                                    {{ $booking->check_in ? \Carbon\Carbon::parse($booking->check_in)->format('d/m/Y') : '—' }}
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="text-muted">Trả phòng</div>
                                <div class="fw-semibold">
                                    {{ $booking->check_out ? \Carbon\Carbon::parse($booking->check_out)->format('d/m/Y') : '—' }}
                                </div>
                            </div>

                            <div class="col-12 col-md-6 mt-2">
                                <div class="text-muted">Số đêm</div>
                                <div class="fw-semibold">{{ is_null($nights) ? '—' : $nights }}</div>
                            </div>

                            <div class="col-12 col-md-6 mt-2">
                                <div class="text-muted">Trạng thái</div>
                                <div class="fw-semibold">{{ $booking->status ?? '—' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Payment --}}
            <div class="col-12 col-lg-5">
                <div class="card">
                    <div class="card-header bg-white">
                        <div class="fw-semibold">
                            <i class="fa-solid fa-credit-card me-1"></i>
                            Chọn phương thức thanh toán
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="text-muted">Tổng tiền</div>
                            <div class="fw-bold">
                                {{ number_format($amount, 0, ',', '.') }} <span class="text-muted">₫</span>
                            </div>
                        </div>

                        <hr>

                        <form method="POST" action="{{ route('guest.bookings.payments.store', $booking) }}">
                            @csrf

                            {{-- method (BẮT BUỘC theo controller) --}}
                            <div class="mb-3">
                                <div class="form-check border rounded-3 p-3 mb-2">
                                    <input class="form-check-input" type="radio" name="method" id="mStripe"
                                        value="{{ $methodStripe }}" {{ $selected === $methodStripe ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="mStripe">
                                        Thanh toán online (Stripe)
                                    </label>
                                    <div class="text-muted small mt-1">
                                        Bạn sẽ được chuyển sang trang thanh toán an toàn để hoàn tất giao dịch.
                                    </div>
                                </div>

                                <div class="form-check border rounded-3 p-3">
                                    <input class="form-check-input" type="radio" name="method" id="mCod"
                                        value="{{ $methodCod }}" {{ $selected === $methodCod ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="mCod">
                                        Thanh toán trực tiếp (COD)
                                    </label>
                                    <div class="text-muted small mt-1">
                                        Tạo yêu cầu COD. Bạn thanh toán trực tiếp với host theo thỏa thuận.
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa-solid fa-lock me-1"></i> Tiếp tục
                            </button>

                            <div class="text-muted small mt-2">
                                * Nếu chọn Stripe, hệ thống sẽ tự chuyển hướng sang trang thanh toán.
                            </div>
                        </form>
                    </div>
                </div>

                <div class="alert alert-light border mt-3 mb-0">
                    <div class="fw-semibold mb-1">
                        <i class="fa-solid fa-shield-halved me-1"></i> Lưu ý
                    </div>
                    <div class="small text-muted">
                        Không chia sẻ thông tin thanh toán. Nếu có lỗi, vui lòng thử lại hoặc liên hệ hỗ trợ.
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
