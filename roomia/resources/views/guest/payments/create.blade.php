{{-- resources/views/guest/payments/create.blade.php --}}
{{--
    TẠO THANH TOÁN (GUEST)
    - Mục đích: Guest thanh toán cho một booking (đặt phòng).
    - Dữ liệu mong đợi từ Controller (tuỳ flow):
        $booking  : Booking cần thanh toán (khuyến nghị có)
        $amount   : Số tiền cần thanh toán (có thể lấy từ $booking)
        $currency : 'VND' / 'USD'...
    - Form sẽ POST về route guest.payments.store (nếu đã khai báo).
    - Không dùng JS inline: nếu dùng Stripe Elements/Checkout, bạn nên xử lý ở file JS ngoài.
--}}

@extends('layouts.guest')

@section('content')
    <div class="py-3">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h4 class="mb-1">Tạo Thanh Toán</h4>
                <div class="text-muted small">
                    Xác nhận thông tin và thực hiện thanh toán cho đặt phòng.
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

        {{-- Thông báo / lỗi --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <div class="fw-semibold mb-1">Có Lỗi Xảy Ra:</div>
                <ul class="mb-0">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @php
            // Chuẩn hoá dữ liệu để view hoạt động ổn ngay cả khi controller đặt tên khác
            $booking = $booking ?? null;
            $amount = $amount ?? ($booking->total_amount ?? ($booking->amount ?? null));
            $currency = $currency ?? ($booking->currency ?? 'VND');

            $hasPaymentsStore = \Illuminate\Support\Facades\Route::has('guest.payments.store');
            $hasBookingsShow = \Illuminate\Support\Facades\Route::has('guest.bookings.show');
        @endphp

        <div class="row g-3">
            {{-- Cột trái: Thông tin booking --}}
            <div class="col-12 col-lg-7">
                <div class="card">
                    <div class="card-header bg-white">
                        <div class="fw-semibold">
                            <i class="fa-solid fa-calendar-check me-1"></i>
                            Thông Tin Đặt Phòng
                        </div>
                    </div>
                    <div class="card-body">
                        @if ($booking)
                            <div class="mb-2">
                                <div class="fw-semibold">
                                    {{ $booking->roomType->name ?? ($booking->room_type_name ?? 'Loại Phòng') }}
                                </div>
                                <div class="text-muted small">
                                    {{ $booking->roomType->room->title ?? ($booking->room_title ?? '') }}
                                </div>
                            </div>

                            <div class="row g-2 small">
                                <div class="col-12 col-md-6">
                                    <div class="text-muted">Check In</div>
                                    <div class="fw-semibold">
                                        {{ $booking->check_in ? \Carbon\Carbon::parse($booking->check_in)->format('d/m/Y') : '—' }}
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="text-muted">Check Out</div>
                                    <div class="fw-semibold">
                                        {{ $booking->check_out ? \Carbon\Carbon::parse($booking->check_out)->format('d/m/Y') : '—' }}
                                    </div>
                                </div>
                            </div>

                            @if ($hasBookingsShow)
                                <div class="mt-3">
                                    <a href="{{ route('guest.bookings.show', $booking) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="fa-regular fa-eye me-1"></i>
                                        Xem Chi Tiết Đặt Phòng
                                    </a>
                                </div>
                            @endif
                        @else
                            <div class="text-muted">
                                Không tìm thấy thông tin đặt phòng để thanh toán. Vui lòng quay lại và thử lại.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Cột phải: Tóm tắt thanh toán + nút thanh toán --}}
            <div class="col-12 col-lg-5">
                <div class="card">
                    <div class="card-header bg-white">
                        <div class="fw-semibold">
                            <i class="fa-solid fa-wallet me-1"></i>
                            Tóm Tắt Thanh Toán
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted">Số Tiền</div>
                            <div class="fw-bold">
                                @if (!is_null($amount))
                                    {{ number_format((float) $amount, 0, ',', '.') }}
                                    <span class="text-muted">{{ $currency === 'VND' ? '₫' : $currency }}</span>
                                @else
                                    —
                                @endif
                            </div>
                        </div>

                        <hr>

                        {{-- Form thanh toán --}}
                        @if ($hasPaymentsStore && $booking)
                            <form method="POST" action="{{ route('guest.payments.store', $booking) }}">
                                @csrf

                                {{--
                                    Bạn có thể truyền thêm thông tin nếu cần:
                                    - amount/currency (nếu controller không tự tính)
                                    - payment_method (nếu bạn có nhiều cổng thanh toán)
                                --}}
                                <input type="hidden" name="booking_id" value="{{ $booking->id }}">

                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" value="1" id="agree" required>
                                    <label class="form-check-label" for="agree">
                                        Tôi Đồng Ý Với Điều Khoản Thanh Toán
                                    </label>
                                </div>

                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fa-solid fa-lock me-1"></i>
                                    Thanh Toán Ngay
                                </button>

                                <div class="text-muted small mt-2">
                                    Hệ thống sẽ xử lý thanh toán và cập nhật trạng thái giao dịch.
                                </div>
                            </form>
                        @else
                            <div class="alert alert-warning mb-0">
                                Route thanh toán chưa sẵn sàng hoặc thiếu dữ liệu booking.
                                <div class="small text-muted mt-1">
                                    Kiểm tra route name <code>guest.payments.store</code> và controller create().
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Gợi ý bảo mật / UX --}}
                <div class="alert alert-light border mt-3 mb-0">
                    <div class="fw-semibold mb-1">
                        <i class="fa-solid fa-shield-halved me-1"></i>
                        Lưu Ý
                    </div>
                    <div class="small text-muted">
                        Không chia sẻ thông tin thanh toán cho người khác. Nếu có lỗi, vui lòng liên hệ hỗ trợ.
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
