<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CartController extends Controller
{
   protected $apiBaseUrl;

public function __construct()
{
    $this->apiBaseUrl = env('API_BASE_URL');
}

    /* ===============================
        CART PAGE
    =============================== */
    public function index()
    {
        // dd($this->apiBaseUrl);
        if (!session('user_token')) {
            return redirect()->route('login')->with('error', 'Please login to view your cart');
        }

        try {
            $response = Http::withToken(session('user_token'))
                ->timeout(10)
                ->get($this->apiBaseUrl . '/cart');
// dd($response);
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
    // ✅ LOGIN CHECK (IMPORTANT FIX)
    if (!session('user_token')) {
        return response()->json([
            'status' => false,
            'message' => 'Unauthenticated'
        ], 401);
    }

    // ✅ VALIDATION
    $request->validate([
        'batch_id' => 'required|integer',
        'qty' => 'required|integer|min:1'
    ]);

    try {

        // ✅ ADD TO CART API CALL
        $response = Http::withToken(session('user_token'))
            ->timeout(10)
            ->post($this->apiBaseUrl . '/cart/add', [
                'batch_id' => $request->batch_id,
                'qty' => $request->qty
            ]);

        if ($response->successful()) {

            // 🔥 FETCH UPDATED CART COUNT
            $cartResponse = Http::withToken(session('user_token'))
                ->get($this->apiBaseUrl . '/cart');

            $count = 0;

            if ($cartResponse->successful()) {
                $data = $cartResponse->json();
                $count = collect($data['items'] ?? [])->sum('qty');
            }

            // ✅ SAVE IN SESSION (OPTIONAL BUT GOOD)
            session(['cart_count' => $count]);

            // ✅ RETURN JSON (VERY IMPORTANT FOR AJAX)
if ($request->expectsJson()) {
    return response()->json([
        'success' => true,
        'cart_count' => $count,
        'message' => 'Added to cart'
    ]);
}

return redirect()->back()->with('success', 'Added to cart');        }
if ($request->expectsJson()) {
    return response()->json([
        'success' => false,
        'message' => 'Failed to add'
    ], 400);
}

return redirect()->back()->with('error', 'Failed to add');
    } catch (\Exception $e) {

if ($request->expectsJson()) {
    return response()->json([
        'success' => false,
        'message' => 'Cart service error'
    ], 500);
}

return redirect()->back()->with('error', 'Cart service error');    }
}
    /* ===============================
        UPDATE CART
    =============================== */
 public function update(Request $request)
{
    $request->validate([
        'cart_id' => 'required|integer',
        'qty' => 'required|integer|min:1'
    ]);

    try {
        $response = Http::withToken(session('user_token'))
            ->post($this->apiBaseUrl . '/cart/update', [
                'cart_id' => $request->cart_id,
                'qty' => $request->qty
            ]);

        if ($response->successful()) {
            $this->refreshCartCount();

            return response()->json([
                'status' => true,
                'summary' => $response->json()['summary'] ?? []
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Update failed'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Update error'
        ]);
    }
}

    /* ===============================
        REMOVE ITEM
    =============================== */
public function remove($id)
{
    try {
        $response = Http::withToken(session('user_token'))
            ->delete($this->apiBaseUrl . "/cart/remove/{$id}");

        if ($response->successful()) {
            $this->refreshCartCount();

            return response()->json([
                'status' => true,
                'summary' => $response->json()['summary'] ?? []
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Remove failed'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Remove error'
        ]);
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
    // public function placeOrder()
    // {
    //     try {
    //         $response = Http::withToken(session('user_token'))
    //             ->timeout(10)
    //             ->post($this->apiBaseUrl . '/orders/place');

    //         if ($response->successful()) {
    //             session(['cart_count' => 0]);
    //             return redirect()->route('orders')->with('success', 'Order placed');
    //         }

    //         return redirect()->route('cart')->with('error', 'Order failed');

    //     } catch (\Exception $e) {
    //         return redirect()->route('cart')->with('error', 'Order error');
    //     }
    // }
    public function placeOrder()
{
    try {

        // 🔥 STEP 1: GET CART FIRST
        $cartResponse = Http::withToken(session('user_token'))
            ->get($this->apiBaseUrl . '/cart');

        if (!$cartResponse->successful()) {
            return redirect()->route('cart')->with('error', 'Unable to verify cart');
        }

        $cartData = $cartResponse->json();

        // 🔥 STEP 2: CHECK RX ITEMS
        $hasRx = collect($cartData['items'] ?? [])->contains(function ($item) {
            return isset($item['need_prescription']) && $item['need_prescription'] == 1;
        });
// 🔥 STEP 3: CHECK PRESCRIPTION FROM API
// 🔥 GET LATEST PRESCRIPTION ID
$prescriptionId = null;

$prescriptionList = Http::withToken(session('user_token'))
    ->get($this->apiBaseUrl . '/prescription/my');

if ($prescriptionList->successful()) {
    $list = $prescriptionList->json()['data'] ?? [];

    if (!empty($list)) {
$latest = collect($list)->sortByDesc('id')->first();
        $prescriptionId = $latest['id'] ?? null;
    }
}

// 🔥 FINAL CONDITION
if ($hasRx && !$prescriptionId) {
        return redirect()->route('cart')

        ->with('error', 'Please upload prescription before placing order');
}

        $response = Http::withToken(session('user_token'))
    ->post($this->apiBaseUrl . '/orders/place', [
'payment_mode' => 'razorpay',
'payment_id' => $request->payment_id ?? 'manual', // ya pass from frontend        'address_id' => session('selected_address_id'), // ensure set
        'prescription_id' => $prescriptionId // 🔥 IMPORTANT
    ]);

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