<?php

namespace App\Services;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use App\Models\Payment;
use App\Models\Booking;

class StripePaymentService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        // Set API key một lần ở đây
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Convert số tiền VND (trong DB) sang số cent USD cho Stripe.
     *
     * @param  int|float  $amountVnd  Số tiền VND trong DB (ví dụ 1,200,000)
     * @return int                   Số cent USD (ví dụ 4800 = $48.00)
     */
    protected function convertVndToUsdCents($amountVnd): int
    {
        $rate = (float) config('currency.vnd_to_usd_rate', 25000);

        if ($rate <= 0) {
            $rate = 25000;
        }

        $amountUsd = $amountVnd / $rate;
        $amountUsdCents = (int) round($amountUsd * 100);

        return $amountUsdCents;
    }

    /**
     * Tạo Stripe Checkout Session cho 1 payment.
     *
     * Giả định:
     *  - $payment->amount đang lưu bằng VND
     *  - Stripe charge bằng USD (có convert)
     */
    public function createCheckoutSession(Payment $payment)
    {
        $amountVnd = $payment->amount;
        $amountUsdCents = $this->convertVndToUsdCents($amountVnd);

        return StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'usd', // Stripe không hỗ trợ VND
                        'product_data' => [
                            'name' => 'Booking #' . $payment->booking_id,
                        ],
                        'unit_amount' => $amountUsdCents,
                    ],
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment',
            'success_url' => config('services.stripe.success_url') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => config('services.stripe.cancel_url'),
            'metadata' => [
                'payment_id' => $payment->id,
                'booking_id' => $payment->booking_id,
                'amount_vnd' => $amountVnd,                    // số VND gốc
                'vnd_to_usd_rate' => config('currency.vnd_to_usd_rate'),
            ],
        ]);
    }
}
