{{-- resources/views/partials/guest/bookings/row.blade.php --}}

@php
    use Carbon\Carbon;
    use Illuminate\Support\Facades\Route;

    $checkIn = $booking->check_in ? Carbon::parse($booking->check_in) : null;
    $checkOut = $booking->check_out ? Carbon::parse($booking->check_out) : null;

    $roomTitle = optional(optional($booking->roomType)->room)->title;
    $roomTypeName = optional($booking->roomType)->name;

    $amount = (int) ($booking->total_price ?? 0);

    // payment (nếu bạn đã tạo relation payment() latestOfMany)
    $payment = $booking->payment ?? null;

    $status = (string) ($booking->status ?? 'unknown');

    $isPaid = $status === 'paid' || (string) ($payment->status ?? '') === 'paid';

    $canPay = !$isPaid && $status !== 'cancelled' && Route::has('guest.bookings.payments.create');

    $canReview = $checkOut ? $checkOut->endOfDay()->isPast() : false;
    $hasReview = !empty($booking->review);

    // route review (ưu tiên theo scheme mới guest.bookings.review.*)
    $reviewCreateRoute = Route::has('guest.bookings.review.create')
        ? route('guest.bookings.review.create', $booking)
        : (Route::has('guest.reviews.create')
            ? route('guest.reviews.create', $booking)
            : null);

    $reviewEditRoute = Route::has('guest.bookings.review.edit') ? route('guest.bookings.review.edit', $booking) : null;
@endphp

<tr>
    {{-- Mã --}}
    <td class="align-middle">
        <div class="fw-semibold">#{{ $booking->id }}</div>
        <div class="text-muted small">{{ optional($booking->created_at)->format('d/m/Y H:i') }}</div>
    </td>

    {{-- Chỗ ở --}}
    <td class="align-middle">
        <div class="fw-semibold">
            {{ $roomTitle ?? 'Chỗ ở' }}
        </div>
        @if ($roomTypeName)
            <div class="text-muted small">
                <i class="fa-solid fa-bed me-1"></i>{{ $roomTypeName }}
            </div>
        @endif
    </td>

    {{-- Nhận phòng --}}
    <td class="align-middle text-nowrap">
        {{ $checkIn ? $checkIn->format('d/m/Y') : '—' }}
    </td>

    {{-- Trả phòng --}}
    <td class="align-middle text-nowrap">
        {{ $checkOut ? $checkOut->format('d/m/Y') : '—' }}
    </td>

    {{-- Khách --}}
    <td class="align-middle text-nowrap">
        <i class="fa-solid fa-user-group me-1"></i>
        {{ (int) ($booking->guest_count ?? 0) }}
    </td>

    {{-- Tổng tiền --}}
    <td class="align-middle text-nowrap">
        <div class="fw-bold">{{ number_format($amount, 0, ',', '.') }} ₫</div>
        @if ($payment && !empty($payment->method))
            <div class="text-muted small">
                <i class="fa-solid fa-credit-card me-1"></i>{{ strtoupper($payment->method) }}
            </div>
        @endif
    </td>

    {{-- Trạng thái --}}
    <td class="align-middle text-nowrap">
        @php
            $badge = match ($status) {
                'pending', 'pending_payment' => 'warning',
                'confirmed' => 'primary',
                'paid','completed' => 'success',
                'cancelled' => 'secondary',
                default => 'light',
            };
            $label = strtoupper(str_replace('_', ' ', $status));
        @endphp

        <span class="badge text-bg-{{ $badge }}">{{ $label }}</span>

        @if ($isPaid && $status !== 'paid')
            <div class="text-muted small mt-1">Đã thanh toán</div>
        @endif
    </td>

    {{-- Thao tác --}}
    <td class="align-middle text-end text-nowrap">
        <div class="d-flex " >
            <a class="btn btn-outline-secondary btn-sm me-1" href="{{ route('guest.bookings.show', $booking) }}">
                <i class="fa-solid fa-eye" title="Xem"></i> 
            </a>

            @if ($canPay)
                <a class="btn btn-primary btn-sm me-1" href="{{ route('guest.bookings.payments.create', $booking) }}">
                    <i class="fa-solid fa-credit-card" title="Thanh Toán"></i>
                </a>
            @endif

            @if ($canReview)
                @if (!$hasReview && $reviewCreateRoute)
                    <a class="btn btn-outline-primary btn-sm me-1" href="{{ $reviewCreateRoute }}">
                        <i class="fa-solid fa-star"></i> Đánh giá
                    </a>
                @elseif($hasReview && $reviewEditRoute)
                    <a class="btn btn-outline-success btn-sm me-1" href="{{ $reviewEditRoute }}">
                        <i class="fa-solid fa-pen"></i> Sửa
                    </a>
                @endif
            @endif
        </div>
    </td>
</tr>
