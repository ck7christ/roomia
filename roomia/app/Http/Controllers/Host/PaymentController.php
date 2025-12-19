<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment;
use App\Models\Booking;
use Illuminate\Http\RedirectResponse;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $hostId = Auth::id();


        $payments = Payment::with([
            'booking.roomType.room',
            'booking.guest',
        ])
            ->whereHas('booking.roomType.room', function ($q) use ($hostId) {
                $q->where('host_id', $hostId);
            })
            ->latest()
            ->paginate(20);

        return view('host.payments.index', compact('payments'));
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        //
        $this->authorizePayment($payment);

        $payment->load(['booking.roomType.room', 'booking.guest']);

        return view('host.payments.show', compact('payment'));
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
    public function confirmCod(Payment $payment, Request $request): RedirectResponse
    {
        $this->authorizePayment($payment);

        // Đảm bảo đây là payment COD
        if ($payment->method !== Payment::METHOD_COD) {
            return back()->with('error', 'Chỉ có thể xác nhận thanh toán cho đơn COD.');
        }

        // Tránh xác nhận trùng
        if ($payment->status === Payment::STATUS_SUCCESS) {
            return back()->with('info', 'Thanh toán này đã được xác nhận trước đó.');
        }

        // Cập nhật payment
        $payment->status = Payment::STATUS_SUCCESS;
        $payment->paid_at = now();
        $payment->save();

        // Cập nhật booking liên quan (nếu có)
        if ($payment->booking) {
            // Nếu bạn muốn coi COD = đã xác nhận & đã thanh toán
            $payment->booking->status = Booking::STATUS_CONFIRMED;
            $payment->booking->save();
        }

        return back()->with('success', 'Đã xác nhận thanh toán COD thành công.');
    }

    protected function authorizePayment(Payment $payment): void
    {
        $hostId = Auth::id();

        if (
            !$payment->booking ||
            !$payment->booking->roomType ||
            !$payment->booking->roomType->room ||
            $payment->booking->roomType->room->host_id !== $hostId
        ) {
            abort(403);
        }
    }
}
