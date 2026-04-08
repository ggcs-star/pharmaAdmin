<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends BaseController
{
    /* ===============================
        ORDER LIST
    =============================== */
    public function index()
    {
        if (!session('user_token')) {
            return redirect()->route('login')->with('error', 'Please login to view orders');
        }

        try {
            $response = $this->apiGet('/orders');

            if ($response->successful()) {

                $data = $response->json();
                $orders = $data['data'] ?? [];

                return view('orders', compact('orders'));
            }

            return view('orders', ['orders' => []])
                ->with('error', 'Failed to fetch orders');

        } catch (\Exception $e) {
            return view('orders', ['orders' => []])
                ->with('error', 'Orders service unavailable');
        }
    }

    /* ===============================
        ORDER DETAIL
    =============================== */
public function show($id)
{
    if (!session('user_token')) {
        return redirect()->route('login')->with('error', 'Please login');
    }

    try {
        $response = $this->apiGet("/orders/{$id}");

        if ($response->successful()) {

            $data = $response->json();
            $order = $data['data'] ?? null;

            if (!$order) {
                return redirect()->route('orders.index')
                    ->with('error', 'Order not found');
            }

            /* ===============================
                🔥 DATA NORMALIZATION
            =============================== */

            // ✅ USER
            $order['user'] = [
                'name'   => $order['user']['name'] ?? 'N/A',
                'mobile' => $order['user']['mobile'] ?? 'N/A',
                'email'  => $order['user']['email'] ?? 'N/A',
            ];

            // ✅ ADDRESS (🔥 NEW)
            $order['address'] = [
                'line1'   => $order['address']['line1'] ?? 'N/A',
                'city'    => $order['address']['city'] ?? 'N/A',
                'state'   => $order['address']['state'] ?? 'N/A',
                'pincode' => $order['address']['pincode'] ?? 'N/A',
            ];

            // ✅ ITEMS
            if (!empty($order['items'])) {
                foreach ($order['items'] as &$item) {
                    $item['product'] = [
                        'name' => $item['product']['name'] ?? 'Item #' . ($item['item_id'] ?? '')
                    ];
                }
            }

            return view('orders', compact('order'));
        }

        return redirect()->route('orders.index')
            ->with('error', 'Order not found');

    } catch (\Exception $e) {
        return redirect()->route('orders.index')
            ->with('error', 'Failed to fetch order details');
    }
}

    /* ===============================
        PLACE ORDER
    =============================== */
    public function place(Request $request)
    {
        try {

            if (!session('user_token')) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not logged in'
                ]);
            }

            if (!$request->address_id) {
                return response()->json([
                    'status' => false,
                    'message' => 'Please select address'
                ]);
            }

            if (!$request->payment_mode) {
                return response()->json([
                    'status' => false,
                    'message' => 'Payment mode required'
                ]);
            }

            $payload = [
                'address_id'   => $request->address_id,
                'payment_id'   => $request->payment_id,
                'payment_mode' => $request->payment_mode
            ];

            $response = $this->apiPost('/orders/place', $payload);

            if ($response->successful()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Order placed successfully'
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Order failed'
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /* ===============================
        CANCEL ORDER
    =============================== */
    public function cancel($id)
    {
        if (!session('user_token')) {
            return redirect()->route('login')->with('error', 'Please login');
        }

        try {
            $response = $this->apiPost("/orders/cancel/{$id}");

            if ($response->successful()) {
                return redirect()->route('orders.index')
                    ->with('success', 'Order cancelled successfully');
            }

            return redirect()->route('orders.index')
                ->with('error', 'Failed to cancel order');

        } catch (\Exception $e) {
            return redirect()->route('orders.index')
                ->with('error', 'Cancel failed');
        }
    }
}