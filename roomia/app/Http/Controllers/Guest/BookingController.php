<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\Booking;
use App\Models\RoomType;
use App\Models\RoomCalendar;
use App\Models\Payment;
use App\Models\Voucher;
use App\Models\VoucherRedemption;
use Stripe\Stripe;
use Stripe\Refund;
use Exception;



class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Danh sách booking của guest hiện tại
        $guestId = Auth::id();

        $bookings = Booking::with(['roomType.room', 'payment'])
            ->where('guest_id', $guestId)
            ->latest()
            ->paginate(20);

        return view('guest.bookings.index', compact('bookings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, RoomType $roomType)
    {
        $user = Auth::user();

        $data = $request->validate([
            'check_in' => ['required', 'date', 'after_or_equal:today'],
            'check_out' => ['required', 'date', 'after:check_in'],
            'guest_count' => ['required', 'integer', 'min:1'],
            'voucher_code' => ['nullable', 'string', 'max:50'],
        ]);

        $checkIn = Carbon::parse($data['check_in'])->startOfDay();
        $checkOut = Carbon::parse($data['check_out'])->startOfDay();
        $guestCount = (int) $data['guest_count'];

        // 1) Check số khách
        if (!is_null($roomType->max_guests) && $guestCount > $roomType->max_guests) {
            return back()->withInput()->withErrors([
                'guest_count' => 'Số khách vượt quá giới hạn cho loại phòng này.',
            ]);
        }

        // 2) Tính subtotal + check calendar
        $subtotal = 0;
        $period = CarbonPeriod::create($checkIn, $checkOut->copy()->subDay());

        foreach ($period as $date) {
            $calendar = RoomCalendar::where('room_type_id', $roomType->id)
                ->where('date', $date->toDateString())
                ->first();

            if ($calendar && $calendar->is_closed) {
                return back()->withInput()->withErrors([
                    'check_in' => 'Ngày ' . $date->format('d/m/Y') . ' hiện không nhận đặt phòng.',
                ]);
            }

            if ($calendar && !is_null($calendar->available_units) && $calendar->available_units < 1) {
                return back()->withInput()->withErrors([
                    'check_in' => 'Ngày ' . $date->format('d/m/Y') . ' đã hết phòng.',
                ]);
            }

            $priceForThisDate = $calendar && !is_null($calendar->price_per_night)
                ? (float) $calendar->price_per_night
                : (float) $roomType->price_per_night;

            $subtotal += $priceForThisDate;
        }

        // 3) Lấy voucher_code (ưu tiên input, fallback session('voucher.code'))
        $voucherCode = trim((string) ($data['voucher_code'] ?? ''));
        if ($voucherCode === '') {
            $voucherCode = trim((string) data_get(session('voucher'), 'code', ''));
        }

        DB::beginTransaction();

        try {
            // 4) Apply voucher (nếu có)
            $voucher = null;
            $discount = 0.0;

            if ($voucherCode !== '') {
                $voucher = $this->validateAndLockVoucher($voucherCode, $user->id, $subtotal);
                $discount = $this->calculateDiscount($voucher, $subtotal);
            }

            $payable = max(0, $subtotal - $discount);

            // 5) Tạo booking
            $booking = Booking::create([
                'room_type_id' => $roomType->id,
                'guest_id' => $user->id,
                'guest_count' => $guestCount,
                'check_in' => $checkIn,
                'check_out' => $checkOut,
                'total_price' => $payable,
                'status' => Booking::STATUS_PENDING,

                'voucher_id' => $voucher?->id,
                'voucher_code' => $voucher?->code,
                'voucher_discount_amount' => $discount,
                'voucher_snapshot' => $voucher ? [
                    'type' => $voucher->type,
                    'value' => (float) $voucher->value,
                    'min_subtotal' => $voucher->min_subtotal !== null ? (float) $voucher->min_subtotal : null,
                    'max_discount' => $voucher->max_discount !== null ? (float) $voucher->max_discount : null,
                    'subtotal_at_apply' => (float) $subtotal,
                    'discount_applied' => (float) $discount,
                ] : null,
            ]);

            // 6) Ghi nhận voucher usage (giữ slot ngay khi tạo booking)
            if ($voucher) {
                VoucherRedemption::create([
                    'voucher_id' => $voucher->id,
                    'user_id' => $user->id,
                    'booking_id' => $booking->id,
                    'discount_amount' => $discount,
                    'used_at' => now(),
                ]);

                $voucher->increment('used_count');

                // clear session voucher
                session()->forget('voucher');
                session()->forget('voucher_code'); // nếu trước đó bạn dùng key cũ
            }

            DB::commit();

            return redirect()
                ->route('guest.payments.create', $booking)
                ->with('success', 'Đặt phòng thành công! Vui lòng chọn phương thức thanh toán.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return back()->withInput()->withErrors($e->errors());
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Create booking error', [
                'room_type_id' => $roomType->id,
                'error' => $e->getMessage(),
            ]);

            return back()->withInput()->with('error', 'Có lỗi xảy ra khi đặt phòng. Vui lòng thử lại.');
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Booking $booking)
    {
        //
        $this->authorizeBooking($booking);

        $booking->load(['roomType.room', 'payment']);

        return view('guest.bookings.show', compact('booking'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Hủy booking + refund nếu có thanh toán Stripe
     */
    public function cancel(Request $request, Booking $booking)
    {
        $this->authorizeBooking($booking);

        if (!$booking->isCancellable()) {
            return back()->with('error', 'Booking này hiện tại không thể hủy.');
        }

        $request->validate([
            'reason' => ['nullable', 'string', 'max:1000'],
        ]);

        $payment = $booking->payment;

        DB::beginTransaction();

        try {
            // 1) Refund Stripe nếu có payment thành công
            if (
                $payment &&
                $payment->status === Payment::STATUS_SUCCESS &&
                $payment->method === Payment::METHOD_STRIPE &&
                $payment->provider_transaction_id
            ) {
                Stripe::setApiKey(config('services.stripe.secret'));

                $refund = Refund::create([
                    'payment_intent' => $payment->provider_transaction_id,
                    'amount' => $payment->amount,
                ]);

                $payment->update([
                    'status' => Payment::STATUS_REFUNDED,
                    'refunded_at' => now(),
                    'refund_id' => $refund->id,
                    'refund_amount' => $refund->amount,
                ]);
            }

            // 2) Update booking
            $booking->update([
                'status' => Booking::STATUS_CANCELLED,
                'cancelled_at' => now(),
                'cancelled_by_id' => Auth::id(),
                'cancelled_by_type' => 'guest',
                'cancel_reason' => $request->input('reason'),
            ]);

            // ✅ 3) Release voucher usage nếu booking có dùng voucher
            $this->releaseVoucherIfAny($booking);

            DB::commit();

            return redirect()
                ->route('guest.bookings.show', $booking)
                ->with('success', 'Hủy booking thành công.');
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Cancel booking error', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Có lỗi xảy ra khi hủy booking. Vui lòng thử lại sau.');
        }
    }

    /**
     * Đảm bảo booking thuộc về guest hiện tại
     */
    protected function authorizeBooking(Booking $booking): void
    {
        if ($booking->guest_id !== Auth::id()) {
            abort(403);
        }
    }
    /**
     *  Validate + lock voucher để tránh race condition
     */
    protected function validateAndLockVoucher(string $code, int $userId, float $subtotal): Voucher
    {
        $now = now();

        /** @var Voucher|null $voucher */
        $voucher = Voucher::where('code', $code)
            ->lockForUpdate()
            ->first();

        if (!$voucher) {
            throw ValidationException::withMessages([
                'voucher_code' => 'Voucher không tồn tại.',
            ]);
        }

        // Bạn đổi field name theo migration của bạn nếu khác
        if (property_exists($voucher, 'is_active') && !$voucher->is_active) {
            throw ValidationException::withMessages(['voucher_code' => 'Voucher đang bị tắt.']);
        }

        if (!is_null($voucher->starts_at ?? null) && $now->lt(Carbon::parse($voucher->starts_at))) {
            throw ValidationException::withMessages(['voucher_code' => 'Voucher chưa đến ngày áp dụng.']);
        }

        if (!is_null($voucher->ends_at ?? null) && $now->gt(Carbon::parse($voucher->ends_at))) {
            throw ValidationException::withMessages(['voucher_code' => 'Voucher đã hết hạn.']);
        }

        if (!is_null($voucher->min_subtotal ?? null) && $subtotal < (float) $voucher->min_subtotal) {
            throw ValidationException::withMessages(['voucher_code' => 'Chưa đạt điều kiện tối thiểu để áp voucher.']);
        }

        if (!is_null($voucher->usage_limit ?? null) && (int) $voucher->used_count >= (int) $voucher->usage_limit) {
            throw ValidationException::withMessages(['voucher_code' => 'Voucher đã hết lượt sử dụng.']);
        }

        if (!is_null($voucher->per_user_limit ?? null)) {
            $usedByUser = VoucherRedemption::where('voucher_id', $voucher->id)
                ->where('user_id', $userId)
                ->count();

            if ($usedByUser >= (int) $voucher->per_user_limit) {
                throw ValidationException::withMessages(['voucher_code' => 'Bạn đã dùng voucher này quá số lần cho phép.']);
            }
        }

        return $voucher;
    }

    /**
     *  Tính tiền giảm
     */
    protected function calculateDiscount(Voucher $voucher, float $subtotal): float
    {
        $type = (string) ($voucher->discount_type ?? 'fixed');
        $value = (float) ($voucher->discount_value ?? 0);

        $discount = 0.0;

        if ($type === 'percent') {
            $discount = $subtotal * ($value / 100.0);
        } else {
            $discount = $value;
        }

        // cap nếu có
        if (!is_null($voucher->max_discount_amount ?? null)) {
            $discount = min($discount, (float) $voucher->max_discount_amount);
        }

        return max(0.0, min($discount, $subtotal));
    }

    /**
     *  Nếu booking bị hủy thì trả lại lượt voucher (theo phương án cũ)
     */
    protected function releaseVoucherIfAny(Booking $booking): void
    {
        // Nếu bạn có cột voucher_id trong bookings thì dùng luôn.
        // Còn nếu chưa có, vẫn release theo redemption của booking.
        $redemption = VoucherRedemption::where('booking_id', $booking->id)->first();

        if (!$redemption) {
            return;
        }

        /** @var Voucher|null $voucher */
        $voucher = Voucher::whereKey($redemption->voucher_id)->lockForUpdate()->first();

        // xóa redemption
        $redemption->delete();

        // giảm used_count
        if ($voucher && (int) $voucher->used_count > 0) {
            $voucher->decrement('used_count');
        }
    }

}
