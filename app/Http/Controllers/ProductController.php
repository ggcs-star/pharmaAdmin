<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends BaseController
{
    public function show($id)
    {
        try {

            // ✅ CLEAN API CALL
            $response = $this->apiGet("/product/{$id}");

            if ($response->successful()) {

                $res = $response->json();

                // 🔥 CRITICAL CHECK
                if (!isset($res['data']) || !is_array($res['data'])) {
                    return redirect()->route('home')->with('error', 'Invalid product data');
                }

                $product = $res['data'];

                return view('product', compact('product'));

            }

            return redirect()->route('home')->with('error', 'Product not found');

        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', 'API error');
        }
    }
}