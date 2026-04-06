<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends BaseController
{
    public function index()
    {
        try {

            // ✅ CLEAN API CALL
            $response = $this->apiGet('/products');

            if ($response->successful()) {

                $res = $response->json();

                // ✅ ONLY DATA
                $products = $res['data'] ?? [];

            } else {
                $products = [];
                session()->flash('error', 'Unable to fetch products');
            }

        } catch (\Exception $e) {
            $products = [];
            session()->flash('error', 'API connection error');
        }

        // Static categories (same)
        $categories = [
            ['name' => 'Medicines', 'icon' => '💊', 'color' => 'primary'],
            ['name' => 'Personal Care', 'icon' => '🧴', 'color' => 'success'],
            ['name' => 'Health Devices', 'icon' => '🩺', 'color' => 'info'],
            ['name' => 'Wellness', 'icon' => '🌿', 'color' => 'warning'],
            ['name' => 'Ayurveda', 'icon' => '🍃', 'color' => 'danger'],
            ['name' => 'Diabetes Care', 'icon' => '🩸', 'color' => 'secondary'],
        ];

        return view('home', compact('products', 'categories'));
    }
}