<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Payment;
use App\Models\Booking;
use App\Services\StripePaymentService;

use Stripe\Stripe;
use Stripe\Checkout\Session;
use Exception;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $user = Auth::user();

        $payments = Payment::whereHas('booking', function ($q) use ($user) {
            $q->where('guest_id', $user->id);
        })
            ->latest()
            ->paginate(10);

        return view('guest.payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Booking $booking)
    {
        //
        $this->authorizeBooking($booking);

        if ($booking->status === Booking::STATUS_CANCELLED) {
            return redirect()
                ->route('guest.bookings.show', $booking)
                ->with('error', 'Booking này đã bị hủy, không thể thanh toán.');
        }

        // Nếu đã có payment success thì không cho thanh toán lại
        if ($booking->payment && $booking->payment->status === Payment::STATUS_SUCCESS) {
            return redirect()
                ->route('guest.bookings.show', $booking)
                ->with('info', 'Booking này đã được thanh toán.');
        }

        return view('guest.payments.create', compact('booking'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Booking $booking)
    {
        //
        $this->authorizeBooking($booking);

        if ($booking->status === Booking::STATUS_CANCELLED) {
            return redirect()
                ->route('guest.bookings.show', $booking)
                ->with('error', 'Booking này đã bị hủy, không thể thanh toán.');
        }

        $request->validate([
            'method' => 'required|string|in:' . implode(',', [
                Payment::METHOD_STRIPE,
                Payment::METHOD_COD,
            ]),
        ]);

        $method = $request->input('method');
        $amount = (int) $booking->total_price; // cột đúng trong Booking
        $currency = 'VND'; // nếu bạn có cột currency riêng thì chỉnh lại

        DB::beginTransaction();

        try {
            // 1. Tạo payment record
            $payment = Payment::create([
                'booking_id' => $booking->id,
                'amount' => $amount,
                'currency' => $currency,
                'method' => $method,
                'status' => Payment::STATUS_PENDING,
                'provider_transaction_id' => null,
                'meta' => [],
            ]);

            // 2. Theo từng method
            if ($method === Payment::METHOD_STRIPE) {
                $stripe = new StripePaymentService();
                $session = $stripe->createCheckoutSession($payment);


                // Lưu payment_intent vào provider_transaction_id để dùng cho refund
                $payment->update([
                    'provider_transaction_id' => $session->payment_intent ?? $session->id,
                    'meta' => [
                        'session_id' => $session->id,
                        'url' => $session->url,
                    ],
                ]);

                DB::commit();

                return redirect($session->url);
            }

            if ($method === Payment::METHOD_COD) {
                // Với COD mình để PENDING, host sẽ confirm trong HostPaymentController
                DB::commit();

                return redirect()
                    ->route('guest.bookings.show', $booking)
                    ->with('success', 'Đã tạo yêu cầu thanh toán COD. Vui lòng thanh toán trực tiếp với host.');
            }

            DB::rollBack();

            return back()->with('error', 'Phương thức thanh toán không hợp lệ.');
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Create payment error', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Có lỗi xảy ra khi tạo thanh toán. Vui lòng thử lại.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        //
        $this->authorizePayment($payment);

        $payment->load(['booking.roomType.room']);

        return view('guest.payments.show', compact('payment'));
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

    public function stripeSuccess(Request $request)
    {
        $sessionId = $request->query('session_id');

        if (!$sessionId) {
            return redirect()->route('guest.bookings.index')
                ->with('error', 'Không tìm thấy phiên thanh toán.');
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $session = Session::retrieve($sessionId);

            // Tìm payment theo session_id trong meta
            $payment = Payment::where('meta->session_id', $session->id)->first();

            if (!$payment) {
                return redirect()->route('guest.bookings.index')
                    ->with('error', 'Không tìm thấy thông tin thanh toán.');
            }

            $booking = $payment->booking;

            if (!$booking || $booking->guest_id !== Auth::id()) {
                abort(403);
            }

            // Cập nhật payment
            $payment->update([
                'status' => Payment::STATUS_SUCCESS,
                'paid_at' => now(),
            ]);

            // Cập nhật booking
            $booking->update([
                'status' => Booking::STATUS_CONFIRMED,
            ]);

            return redirect()
                ->route('guest.bookings.show', $booking)
                ->with('success', 'Thanh toán thành công.');
        } catch (Exception $e) {
            Log::error('Stripe success callback error', [
                'session_id' => $sessionId,
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('guest.bookings.index')
                ->with('error', 'Có lỗi xảy ra khi xử lý kết quả thanh toán.');
        }
    }
    public function stripeCancel()
    {
        return redirect()
            ->route('guest.bookings.index')
            ->with('info', 'Bạn đã hủy thanh toán trên Stripe.');
    }


    /**
     * Booking phải thuộc về guest hiện tại
     */
    protected function authorizeBooking(Booking $booking): void
    {
        if ($booking->guest_id !== Auth::id()) {
            abort(403);
        }
    }

    /**
     * Payment phải thuộc booking của guest hiện tại
     */
    protected function authorizePayment(Payment $payment): void
    {
        if (!$payment->booking || $payment->booking->guest_id !== Auth::id()) {
            abort(403);
        }
    }
}
