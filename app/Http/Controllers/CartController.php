<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CartController extends Controller
{
    protected $apiBaseUrl = 'http://127.0.0.1:8000/api';

    /* ===============================
        CART PAGE
    =============================== */
    public function index()
    {
        if (!session('user_token')) {
            return redirect()->route('login')->with('error', 'Please login to view your cart');
        }

        try {
            $response = Http::withToken(session('user_token'))
                ->timeout(10)
                ->get($this->apiBaseUrl . '/cart');

            if ($response->successful()) {

                $data = $response->json();

                // ✅ FIX: normalize data
                $cart = [
                    'items' => $data['items'] ?? [],
                    'summary' => $data['summary'] ?? [
                        'items' => 0,
                        'qty' => 0,
                        'total' => 0
                    ]
                ];

                return view('cart', compact('cart'));

            } else {
                return view('cart', [
                    'cart' => [
                        'items' => [],
                        'summary' => [
                            'items' => 0,
                            'qty' => 0,
                            'total' => 0
                        ]
                    ]
                ]);
            }

        } catch (\Exception $e) {
            return view('cart', [
                'cart' => [
                    'items' => [],
                    'summary' => [
                        'items' => 0,
                        'qty' => 0,
                        'total' => 0
                    ]
                ]
            ]);
        }
    }

    /* ===============================
        ADD TO CART
    =============================== */
    public function addToCart(Request $request)
    {
        if (!session('user_token')) {
            return redirect()->route('login')->with('error', 'Login required');
        }

        $request->validate([
            'batch_id' => 'required|integer',
            'qty' => 'required|integer|min:1'
        ]);

        try {
            $response = Http::withToken(session('user_token'))
                ->timeout(10)
                ->post($this->apiBaseUrl . '/cart/add', [
                    'batch_id' => $request->batch_id,
                    'qty' => $request->qty
                ]);

            if ($response->successful()) {
                $this->refreshCartCount();
                return redirect()->back()->with('success', 'Added to cart');
            }

            return redirect()->back()->with('error', 'Failed to add');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Cart service error');
        }
    }

    /* ===============================
        UPDATE CART
    =============================== */
    public function update(Request $request)
    {
        $request->validate([
            'cart_id' => 'required|integer', // ✅ FIXED
            'qty' => 'required|integer|min:1'
        ]);

        try {
            $response = Http::withToken(session('user_token'))
                ->timeout(10)
                ->post($this->apiBaseUrl . '/cart/update', [
                    'cart_id' => $request->cart_id, // ✅ FIXED
                    'qty' => $request->qty
                ]);

            if ($response->successful()) {
                $this->refreshCartCount();
                return redirect()->route('cart')->with('success', 'Updated');
            }

            return redirect()->route('cart')->with('error', 'Update failed');

        } catch (\Exception $e) {
            return redirect()->route('cart')->with('error', 'Update error');
        }
    }

    /* ===============================
        REMOVE ITEM
    =============================== */
    public function remove($id)
    {
        try {
            $response = Http::withToken(session('user_token'))
                ->timeout(10)
                ->delete($this->apiBaseUrl . "/cart/remove/{$id}");

            if ($response->successful()) {
                $this->refreshCartCount();
                return redirect()->route('cart')->with('success', 'Removed');
            }

            return redirect()->route('cart')->with('error', 'Remove failed');

        } catch (\Exception $e) {
            return redirect()->route('cart')->with('error', 'Remove error');
        }
    }

    /* ===============================
        CLEAR CART
    =============================== */
    public function clear()
    {
        try {
            $response = Http::withToken(session('user_token'))
                ->timeout(10)
                ->delete($this->apiBaseUrl . '/cart/clear');

            if ($response->successful()) {
                session(['cart_count' => 0]);
                return redirect()->route('cart')->with('success', 'Cart cleared');
            }

            return redirect()->route('cart')->with('error', 'Clear failed');

        } catch (\Exception $e) {
            return redirect()->route('cart')->with('error', 'Error clearing cart');
        }
    }

    /* ===============================
        PLACE ORDER
    =============================== */
    public function placeOrder()
    {
        try {
            $response = Http::withToken(session('user_token'))
                ->timeout(10)
                ->post($this->apiBaseUrl . '/orders/place');

            if ($response->successful()) {
                session(['cart_count' => 0]);
                return redirect()->route('orders')->with('success', 'Order placed');
            }

            return redirect()->route('cart')->with('error', 'Order failed');

        } catch (\Exception $e) {
            return redirect()->route('cart')->with('error', 'Order error');
        }
    }

    /* ===============================
        HELPER: REFRESH CART COUNT
    =============================== */
    private function refreshCartCount()
    {
        try {
            $response = Http::withToken(session('user_token'))
                ->get($this->apiBaseUrl . '/cart');

            if ($response->successful()) {
                $data = $response->json();

                $count = collect($data['items'] ?? [])
                    ->sum('qty'); // ✅ correct count

                session(['cart_count' => $count]);
            }

        } catch (\Exception $e) {
            // ignore
        }
    }
}