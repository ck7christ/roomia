<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Payment;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{
    //
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent(
                $payload,
                $sigHeader,
                $secret
            );
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;

            $paymentId = $session->metadata->payment_id;
            $payment = Payment::find($paymentId);

            if ($payment && $payment->status !== 'success') {
                $payment->update([
                    'status' => 'success',
                    'paid_at' => now(),
                ]);

                $payment->booking->update([
                    'status' => 'paid',
                ]);
            }
        }

        return response()->json(['status' => 'ok']);
    }
}
