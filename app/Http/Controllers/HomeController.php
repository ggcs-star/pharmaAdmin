<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends BaseController
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | PRODUCTS
        |--------------------------------------------------------------------------
        */

        try {

            $response = $this->apiGet('/products');

            if ($response->successful()) {

                $res = $response->json();

                $products = $res['data'] ?? [];

            } else {

                $products = [];

                session()->flash(
                    'error',
                    'Unable to fetch products'
                );
            }

        } catch (\Exception $e) {

            $products = [];

            session()->flash(
                'error',
                'Products API connection error'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | CATEGORIES
        |--------------------------------------------------------------------------
        */

        try {

            // CHANGE API URL IF NEEDED
            $categoryResponse = $this->apiGet('/categories');

            if ($categoryResponse->successful()) {

                $catRes = $categoryResponse->json();

                $categories = $catRes['data'] ?? [];

            } else {

                $categories = [];
            }

        } catch (\Exception $e) {

            $categories = [];
        }

        /*
|--------------------------------------------------------------------------
| BRANDS
|--------------------------------------------------------------------------
*/

try {

    $brandResponse = $this->apiGet('/brands');

    if ($brandResponse->successful()) {

        $brandRes = $brandResponse->json();

        $brands = $brandRes['data'] ?? [];

    } else {

        $brands = [];
    }

} catch (\Exception $e) {

    $brands = [];
}
/*
|--------------------------------------------------------------------------
| HOME SECTIONS
|--------------------------------------------------------------------------
*/

try {

    $homeSectionResponse = $this->apiGet('/home-sections');

    if ($homeSectionResponse->successful()) {

        $homeSections = $homeSectionResponse->json()['data'] ?? [];

    } else {

        $homeSections = [];
    }

} catch (\Exception $e) {

    $homeSections = [];
}
/*
|--------------------------------------------------------------------------
| POPULAR CATEGORIES
|--------------------------------------------------------------------------
*/

try {

    $popularCategoryResponse = $this->apiGet('/popular-categories');

    if ($popularCategoryResponse->successful()) {

        $popularCategories = $popularCategoryResponse->json()['data'] ?? [];

    } else {

        $popularCategories = [];
    }

} catch (\Exception $e) {

    $popularCategories = [];
}
        /*
        |--------------------------------------------------------------------------
        | FALLBACK STATIC CATEGORIES
        |--------------------------------------------------------------------------
        */

        if (empty($categories)) {

            $categories = [

                [
                    'id' => 1,
                    'name' => 'Diabetes Care',
                    'slug' => 'diabetes-care',
                    'image' => 'https://onemg.gumlet.io/a56b26a0-30f1-4977-96f8-7acf1b3e0e02.png?format=auto'
                ],

                [
                    'id' => 2,
                    'name' => 'Heart Care',
                    'slug' => 'heart-care',
                    'image' => 'https://onemg.gumlet.io/629aaf65-515f-4069-b730-28618f78597b.png?format=auto'
                ],

                [
                    'id' => 3,
                    'name' => 'Stomach Care',
                    'slug' => 'stomach-care',
                    'image' => 'https://onemg.gumlet.io/b251c9cf-8d88-4fb8-8c3c-7b328caa9f70.png?format=auto'
                ],

                [
                    'id' => 4,
                    'name' => 'Liver Care',
                    'slug' => 'liver-care',
                    'image' => 'https://onemg.gumlet.io/573d8a1a-edd0-46a5-a0fe-01a1c2bcc8f2.png?format=auto'
                ],

                [
                    'id' => 5,
                    'name' => 'Bone Joint & Muscle Care',
                    'slug' => 'bone-joint-muscle-care',
                    'image' => 'https://onemg.gumlet.io/a1af8b43-2836-483d-8709-99eff1cc6f70.png?format=auto'
                ],

                [
                    'id' => 6,
                    'name' => 'Kidney Care',
                    'slug' => 'kidney-care',
                    'image' => 'https://onemg.gumlet.io/96f9ed8a-ba62-426c-bd66-6762f40f3370.png?format=auto'
                ],

                [
                    'id' => 7,
                    'name' => 'Derma Care',
                    'slug' => 'derma-care',
                    'image' => 'https://onemg.gumlet.io/730dbe50-4bdc-4fa8-9a09-93bc5d6c6f38.png?format=auto'
                ],

                [
                    'id' => 8,
                    'name' => 'Respiratory Care',
                    'slug' => 'respiratory-care',
                    'image' => 'https://onemg.gumlet.io/8051e79c-6152-440e-b402-8d1ba8d7c82e.png?format=auto'
                ],

            ];
        }

        
        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'home',
compact(
    'products',
    'categories',
    'popularCategories',
    'brands',
    'homeSections'
)
        );
    }
    public function brands()
{
    try {

        $response = $this->apiGet('/brands');

        $brands = [];

        if ($response->successful()) {

            $brands = $response->json()['data'] ?? [];
        }

    } catch (\Exception $e) {

        $brands = [];
    }

    $brands = collect($brands);

    $page = request()->get('page', 1);

    $perPage = 24;

    $paginated = new \Illuminate\Pagination\LengthAwarePaginator(
        $brands->forPage($page, $perPage),
        $brands->count(),
        $perPage,
        $page,
        [
            'path' => request()->url()
        ]
    );

    return view('brands', [
        'brands' => $paginated
    ]);
}
public function brandProducts($brand)
{
    try {

        $response = $this->apiGet('/brands/'.$brand);

        if ($response->successful()) {

            $products = $response->json()['data'] ?? [];

        } else {

            $products = [];
        }

    } catch (\Exception $e) {

        $products = [];
    }

    return view('brand-products', compact(
        'products',
        'brand'
    ));
}
}