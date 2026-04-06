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

                return view('order-detail', compact('order'));

            }

            return redirect()->route('orders')
                ->with('error', 'Order not found');

        } catch (\Exception $e) {
            return redirect()->route('orders')
                ->with('error', 'Failed to fetch order details');
        }
    }

    /* ===============================
        PLACE ORDER (WITH ADDRESS)
    =============================== */
 public function place(Request $request)
{
    try {

        if (!session('user_token')) {
            return redirect()->route('login')
                ->with('error', 'User not logged in');
        }

        if (!$request->address_id) {
            return back()->with('error', 'Please select address');
        }

        if (!$request->payment_mode) {
            return back()->with('error', 'Payment mode required');
        }

        $payload = [
            'address_id'   => $request->address_id,
            'payment_id'   => $request->payment_id,
            'payment_mode' => $request->payment_mode
        ];

        $response = $this->apiPost('/orders/place', $payload);

        if ($response->successful()) {

            return redirect()->route('orders')
                ->with('success', 'Order placed successfully');
        }

        return back()->with('error', 'Order failed');

    } catch (\Exception $e) {

        return back()->with('error', 'Server error');
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
                return redirect()->route('orders')
                    ->with('success', 'Order cancelled successfully');
            }

            return redirect()->route('orders')
                ->with('error', 'Failed to cancel order');

        } catch (\Exception $e) {
            return redirect()->route('orders')
                ->with('error', 'Cancel failed');
        }
    }
}