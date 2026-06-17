<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    protected $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = env('API_BASE_URL');
    }

    /*
    |--------------------------------------------------------------------------
    | COMMON API REQUEST
    |--------------------------------------------------------------------------
    */

    private function apiRequest()
    {
        return Http::withToken(session('user_token'))
            ->withHeaders([
                'X-Device-Id' => session('device_id'),
                'Accept' => 'application/json'
            ])
            ->timeout(15);
    }

    /*
    |--------------------------------------------------------------------------
    | CART PAGE
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        if (!session('user_token')) {

            return redirect()
                ->route('login')
                ->with('error', 'Please login first');
        }

        try {

            $response = $this->apiRequest()
                ->get($this->apiBaseUrl . '/cart');

            if ($response->successful()) {

                $data = $response->json();

                $cart = [
                    'items' => $data['items'] ?? [],
                    'summary' => $data['summary'] ?? [
                        'items' => 0,
                        'qty' => 0,
                        'total' => 0
                    ]
                ];

                return view('cart', compact('cart'));
            }

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

        } catch (\Exception $e) {

            Log::error('Cart Index Error', [
                'message' => $e->getMessage()
            ]);

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

    /*
    |--------------------------------------------------------------------------
    | ADD TO CART
    |--------------------------------------------------------------------------
    */

public function addToCart(Request $request)
{
    if (!session('user_token')) {

        if ($request->ajax() || $request->wantsJson()) {

            return response()->json([
                'status' => false,
                'message' => 'Please login first'
            ], 401);
        }

        return redirect()
            ->route('login')
            ->with('error', 'Please login first');
    }

    $request->validate([
        'batch_id' => 'required|integer',
        'qty' => 'required|integer|min:1'
    ]);

    try {

    Log::info('FULL SESSION', session()->all());
        // 🔥 DEBUG LOG
        Log::info('ADD TO CART DEBUG', [

            'session_token' => session('user_token'),

            'session_device_id' => session('device_id'),

            'headers' => [
                'X-Device-Id' => session('device_id'),
                'Accept' => 'application/json'
            ],

            'request_data' => [
                'batch_id' => $request->batch_id,
                'qty' => $request->qty
            ]
        ]);

        $response = $this->apiRequest()
            ->post($this->apiBaseUrl . '/cart/add', [
                'batch_id' => $request->batch_id,
                'qty' => $request->qty
            ]);

        // 🔥 API RESPONSE LOG
        Log::info('ADD TO CART API RESPONSE', [

            'status' => $response->status(),

            'body' => $response->body(),

            'json' => $response->json()
        ]);

        if ($response->successful()) {

            $this->refreshCartCount();

            return response()->json([
                'status' => true,
                'message' => 'Added to cart',
                'cart_count' => session('cart_count', 0)
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => $response->json()['message']
                ?? 'Failed to add cart'
        ], $response->status());

    } catch (\Exception $e) {

        Log::error('Add To Cart Error', [

            'message' => $e->getMessage(),

            'line' => $e->getLine(),

            'file' => $e->getFile()
        ]);

        return response()->json([
            'status' => false,
            'message' => 'Cart service error'
        ], 500);
    }
}


    /*
    |--------------------------------------------------------------------------
    | UPDATE CART
    |--------------------------------------------------------------------------
    */

    public function update(Request $request)
    {
        $request->validate([
            'cart_id' => 'required|integer',
            'qty' => 'required|integer|min:1'
        ]);

        try {

            $response = $this->apiRequest()
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
                'message' => $response->json()['message']
                    ?? 'Update failed'
            ], $response->status());

        } catch (\Exception $e) {

            Log::error('Cart Update Error', [
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Update error'
            ], 500);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | REMOVE ITEM
    |--------------------------------------------------------------------------
    */

    public function remove($id)
    {
        try {

            $response = $this->apiRequest()
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
                'message' => $response->json()['message']
                    ?? 'Remove failed'
            ], $response->status());

        } catch (\Exception $e) {

            Log::error('Cart Remove Error', [
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Remove error'
            ], 500);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | CLEAR CART
    |--------------------------------------------------------------------------
    */

    public function clear()
    {
        try {

            $response = $this->apiRequest()
                ->delete($this->apiBaseUrl . '/cart/clear');

            if ($response->successful()) {

                session(['cart_count' => 0]);

                return redirect()
                    ->route('cart')
                    ->with('status', 'Cart cleared');
            }

            return redirect()
                ->route('cart')
                ->with('error', 'Clear failed');

        } catch (\Exception $e) {

            Log::error('Cart Clear Error', [
                'message' => $e->getMessage()
            ]);

            return redirect()
                ->route('cart')
                ->with('error', 'Error clearing cart');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | PLACE ORDER
    |--------------------------------------------------------------------------
    */

    public function placeOrder(Request $request)
    {
        try {

            $cartResponse = $this->apiRequest()
                ->get($this->apiBaseUrl . '/cart');

            if (!$cartResponse->successful()) {

                return redirect()
                    ->route('cart')
                    ->with('error', 'Unable to verify cart');
            }

            $cartData = $cartResponse->json();

            $hasRx = collect($cartData['items'] ?? [])
                ->contains(function ($item) {

                    return isset($item['need_prescription'])
                        && $item['need_prescription'] == 1;
                });

            $prescriptionId = null;

            $prescriptionList = $this->apiRequest()
                ->get($this->apiBaseUrl . '/prescription/my');

            if ($prescriptionList->successful()) {

                $list = $prescriptionList->json()['data'] ?? [];

                if (!empty($list)) {

                    $latest = collect($list)
                        ->sortByDesc('id')
                        ->first();

                    $prescriptionId = $latest['id'] ?? null;
                }
            }

            if ($hasRx && !$prescriptionId) {

                return redirect()
                    ->route('cart')
                    ->with(
                        'error',
                        'Please upload prescription before placing order'
                    );
            }

            $response = $this->apiRequest()
                ->post($this->apiBaseUrl . '/orders/place', [
                    'payment_mode' => 'razorpay',
                    'payment_id' => $request->payment_id ?? 'manual',
                    'address_id' => session('selected_address_id'),
                    'prescription_id' => $prescriptionId
                ]);

            if ($response->successful()) {

                session(['cart_count' => 0]);

                return redirect()
                    ->route('orders')
                    ->with('status', 'Order placed');
            }

            return redirect()
                ->route('cart')
                ->with(
                    'error',
                    $response->json()['message']
                        ?? 'Order failed'
                );

        } catch (\Exception $e) {

            Log::error('Place Order Error', [
                'message' => $e->getMessage()
            ]);

            return redirect()
                ->route('cart')
                ->with('error', 'Order error');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | REFRESH CART COUNT
    |--------------------------------------------------------------------------
    */

    private function refreshCartCount()
    {
        try {

            $response = $this->apiRequest()
                ->get($this->apiBaseUrl . '/cart');

            if ($response->successful()) {

                $data = $response->json();

                $count = collect($data['items'] ?? [])
                    ->sum('qty');

                session([
                    'cart_count' => $count
                ]);
            }

        } catch (\Exception $e) {

            Log::error('Refresh Cart Error', [
                'message' => $e->getMessage()
            ]);
        }
    }
}

