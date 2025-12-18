{{-- resources/views/guest/payments/index.blade.php --}}
{{--
    DANH SÁCH THANH TOÁN (GUEST)
    - Mục đích: Guest xem các giao dịch thanh toán của mình.
    - Dữ liệu mong đợi từ Controller:
        $payments: Collection hoặc LengthAwarePaginator (paginate) gồm các bản ghi Payment
    - Gợi ý: Controller nên eager-load quan hệ liên quan để tránh N+1:
        Payment::with(['booking.roomType.room'])->where('user_id', auth()->id())...
--}}

@extends('layouts.guest')

@section('content')
    <div class="py-3">

        {{-- Tiêu đề trang --}}
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h4 class="mb-1">Danh Sách Thanh Toán</h4>
                <div class="text-muted small">
                    Theo dõi các giao dịch và trạng thái thanh toán của bạn.
                </div>
            </div>

            @php
                // Tránh lỗi nếu route chưa khai báo
                $hasPaymentsCreate = \Illuminate\Support\Facades\Route::has('guest.payments.create');
            @endphp

            {{-- Nút tạo thanh toán thường đi từ booking (create cần booking_id),
                 nên ở đây chỉ hiển thị nút nếu bạn có flow "tạo thanh toán chung". --}}
            @if ($hasPaymentsCreate)
                <a href="{{ route('guest.payments.create') }}" class="btn btn-primary">
                    <i class="fa-solid fa-plus me-1"></i>
                    Tạo Thanh Toán
                </a>
            @endif
        </div>

        {{-- Thông báo --}}
        @if (session('success'))
            <div class="alert alert-success d-flex align-items-start gap-2">
                <i class="fa-solid fa-circle-check mt-1"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        @if (session('warning'))
            <div class="alert alert-warning d-flex align-items-start gap-2">
                <i class="fa-solid fa-triangle-exclamation mt-1"></i>
                <div>{{ session('warning') }}</div>
            </div>
        @endif

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

        {{-- Bảng dữ liệu --}}
        <div class="card">
            <div class="card-header bg-white">
                <div class="fw-semibold">
                    <i class="fa-solid fa-receipt me-1"></i>
                    Giao Dịch
                </div>
            </div>

            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 90px;">Mã</th>
                            <th>Đặt Phòng</th>
                            <th style="width: 160px;">Số Tiền</th>
                            <th style="width: 140px;">Trạng Thái</th>
                            <th style="width: 170px;">Thời Gian</th>
                            <th class="text-end" style="width: 120px;">Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $hasPaymentsShow = \Illuminate\Support\Facades\Route::has('guest.payments.show');
                            $hasBookingsShow = \Illuminate\Support\Facades\Route::has('guest.bookings.show');
                        @endphp

                        @forelse($payments as $payment)
                            @php
                                // Chuẩn hoá status để hiển thị badge
                                $status = strtolower((string) ($payment->status ?? 'unknown'));
                                $badgeClass = match ($status) {
                                    'paid', 'succeeded', 'success' => 'bg-success',
                                    'pending', 'requires_payment_method', 'processing' => 'bg-warning text-dark',
                                    'failed', 'canceled', 'cancelled' => 'bg-danger',
                                    default => 'bg-secondary',
                                };

                                // Số tiền + đơn vị (tuỳ DB bạn đặt amount/total)
                                $amount = $payment->amount ?? ($payment->total_amount ?? null);
                                $currency = $payment->currency ?? 'VND';

                                $booking = $payment->booking ?? null;
                            @endphp

                            <tr>
                                <td class="fw-semibold">
                                    #{{ $payment->id }}
                                </td>

                                <td>
                                    @if ($booking)
                                        <div class="fw-semibold">
                                            {{ $booking->roomType->name ?? ($booking->room_type_name ?? 'Đặt Phòng') }}
                                        </div>
                                        <div class="text-muted small">
                                            {{ $booking->roomType->room->title ?? ($booking->room_title ?? '') }}
                                            @if ($booking->check_in && $booking->check_out)
                                                • {{ \Carbon\Carbon::parse($booking->check_in)->format('d/m/Y') }}
                                                → {{ \Carbon\Carbon::parse($booking->check_out)->format('d/m/Y') }}
                                            @endif
                                        </div>

                                        @if ($hasBookingsShow)
                                            <a class="small" href="{{ route('guest.bookings.show', $booking) }}">
                                                Xem Đặt Phòng
                                            </a>
                                        @endif
                                    @else
                                        <span class="text-muted">Không Có Thông Tin</span>
                                    @endif
                                </td>

                                <td>
                                    @if (!is_null($amount))
                                        <span class="fw-semibold">{{ number_format((float) $amount, 0, ',', '.') }}</span>
                                        <span class="text-muted">{{ $currency === 'VND' ? '₫' : $currency }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>

                                <td>
                                    <span class="badge {{ $badgeClass }}">
                                        {{ ucfirst($status) }}
                                    </span>
                                </td>

                                <td class="text-muted small">
                                    {{ optional($payment->created_at)->format('d/m/Y H:i') ?? '—' }}
                                </td>

                                <td class="text-end">
                                    @if ($hasPaymentsShow)
                                        <a href="{{ route('guest.payments.show', $payment) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="fa-regular fa-eye me-1"></i>
                                            Xem
                                        </a>
                                    @else
                                        <button class="btn btn-sm btn-outline-secondary" disabled>
                                            <i class="fa-regular fa-eye me-1"></i>
                                            Xem
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    Chưa Có Giao Dịch Thanh Toán Nào.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Phân trang (nếu controller dùng paginate) --}}
            @if (method_exists($payments, 'links'))
                <div class="card-footer bg-white">
                    {{ $payments->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
