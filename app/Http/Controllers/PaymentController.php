<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /* ===============================
        CREATE ORDER (INIT PAYMENT)
    =============================== */
    public function create(Request $request)
    {
        try {

            $request->validate([
                'amount' => 'required|numeric|min:1'
            ]);

            // ✅ Convert to paise
            $amount = (int) ($request->amount * 100);

            $key = config('services.razorpay.key');
            $secret = config('services.razorpay.secret');

            if (!$key || !$secret) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment config missing'
                ], 500);
            }

            $api = new Api($key, $secret);

            $order = $api->order->create([
                'receipt' => 'order_' . time(),
                'amount' => $amount,
                'currency' => 'INR'
            ]);

            return response()->json([
                'success' => true,
                'order_id' => $order['id'],
                'amount' => $amount,
                'key' => $key
            ]);

        } catch (\Exception $e) {

            Log::error('Payment Create Error', [
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Payment initialization failed'
            ], 500);
        }
    }

    /* ===============================
        VERIFY PAYMENT
    =============================== */
    public function verify(Request $request)
    {
        try {

            $request->validate([
                'razorpay_order_id' => 'required',
                'razorpay_payment_id' => 'required',
                'razorpay_signature' => 'required',
            ]);

            $key = config('services.razorpay.key');
            $secret = config('services.razorpay.secret');

            $api = new Api($key, $secret);

            $attributes = [
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature
            ];

            // ✅ Verify signature
            $api->utility->verifyPaymentSignature($attributes);

            return response()->json([
                'success' => true,
                'message' => 'Payment verified'
            ]);

        } catch (\Exception $e) {

            Log::error('Payment Verify Error', [
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Payment verification failed'
            ], 400);
        }
    }
}