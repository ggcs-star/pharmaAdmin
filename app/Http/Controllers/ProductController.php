<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends BaseController
{
    /*
    |--------------------------------------------------------------------------
    | PRODUCT DETAIL
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        try {

            // API CALL
            $response = $this->apiGet("/product/{$id}");

            // FAILED
            if (!$response->successful()) {

                return redirect()
                    ->route('home')
                    ->with('error', 'Product not found');
            }

            $res = $response->json();

            // INVALID RESPONSE
            if (
                !isset($res['status']) ||
                !$res['status'] ||
                !isset($res['data'])
            ) {

                return redirect()
                    ->route('home')
                    ->with('error', 'Invalid product data');
            }

            $product = $res['data'];

            // SAFETY
            $product['gallery_images'] =
                $product['gallery_images'] ?? [];

            $product['packings'] =
                $product['packings'] ?? [];

            return view('product', compact('product'));

        } catch (\Exception $e) {

            return redirect()
                ->route('home')
                ->with('error', 'API Error : ' . $e->getMessage());
        }
    }

    /*
    |--------------------------------------------------------------------------
    | CATEGORY PRODUCTS
    |--------------------------------------------------------------------------
    */

    public function categoryProducts($slug)
    {
        try {

            // CATEGORY API
            $response = $this->apiGet("/products/category/{$slug}");

            // FAILED
            if (!$response->successful()) {

                return redirect()
                    ->route('home')
                    ->with('error', 'Category products not found');
            }

            $res = $response->json();

            // INVALID
            if (
                !isset($res['status']) ||
                !$res['status']
            ) {

                return redirect()
                    ->route('home')
                    ->with('error', 'Invalid category data');
            }

            // PRODUCTS
            $products = $res['data'] ?? [];

            // CATEGORY NAME
            $categorySlug = $res['category_slug'] ?? $slug;

            return view(
                'category-products',
                compact(
                    'products',
                    'categorySlug'
                )
            );

        } catch (\Exception $e) {

            return redirect()
                ->route('home')
                ->with('error', 'API Error : ' . $e->getMessage());
        }
    }
}