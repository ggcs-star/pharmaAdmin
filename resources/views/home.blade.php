@extends('layouts.app')

@section('title', 'PharmaSphere 360 - Your Trusted Online Pharmacy')

@section('content')

<style>

/* =========================================
HOME PAGE
========================================= */

.home-section{
    padding:20px 0 40px;
    background:#f6f6f6;
}

/* =========================================
HERO SECTION
========================================= */

.hero-wrapper{
    display:grid;
    grid-template-columns:2fr 1fr;
    gap:16px;
    margin-bottom:24px;
}

.hero-banner{
    border-radius:8px;
    overflow:hidden;
    background:#fff;
}

.hero-banner img{
    width:100%;
    height:auto;
    display:block;
    border-radius:8px;
}

.side-banner{
    display:flex;
    flex-direction:column;
    gap:16px;
}

.side-banner-card{
    background:#fff;
    border-radius:8px;
    overflow:hidden;
    flex:1;
}

.side-banner-card img{
    width:100%;
    height:auto;
    display:block;
}

/* =========================================
HEALTH CONCERNS
========================================= */

.health-section{
    margin-bottom:34px;
}

.health-header{
    margin-bottom:20px;
}

.health-header h2{
    font-size:30px;
    font-weight:700;
    color:#212121;
    margin:0;
}

.health-scroll{
    display:flex;
    gap:18px;
    overflow-x:auto;
    padding-bottom:10px;
    scrollbar-width:none;
    scroll-behavior:smooth;
}

.health-scroll::-webkit-scrollbar{
    display:none;
}

.health-card{
    min-width:170px;
    max-width:170px;
    background:#fff;
    border:1px solid #ececec;
    border-radius:12px;
    padding:14px;
    text-align:center;
    text-decoration:none;
    transition:.25s;
    flex-shrink:0;
}

.health-card:hover{
    transform:translateY(-3px);
    box-shadow:0 6px 18px rgba(0,0,0,.08);
    border-color:#ff6f61;
}

.health-image{
    width:100%;
    height:140px;
    border-radius:10px;
    overflow:hidden;
    margin-bottom:14px;
    background:#f7f7f7;
}

.health-image img{
    width:100%;
    height:100%;
    object-fit:cover;
    transition:.3s;
}

.health-card:hover img{
    transform:scale(1.03);
}

.health-card h4{
    font-size:15px;
    font-weight:600;
    color:#212121;
    line-height:22px;
    margin:0;
}

/* =========================================
SECTION
========================================= */

.section-box{
    margin-bottom:30px;
}

.section-header{
    display:flex;
    align-items:center;
    justify-content:space-between;
    margin-bottom:18px;
}

.section-title{
    font-size:24px;
    font-weight:700;
    color:#212121;
    margin:0;
}

.view-all{
    font-size:14px;
    color:#ff6f61;
    font-weight:600;
    text-decoration:none;
}

/* =========================================
PRODUCT GRID
========================================= */

.product-grid{
    display:grid;
    grid-template-columns:repeat(5,1fr);
    gap:16px;
}

/* =========================================
PRODUCT CARD
========================================= */

.product-card{
    background:#fff;
    border:1px solid #ececec;
    border-radius:8px;
    overflow:hidden;
    transition:.2s;
    position:relative;
}

.product-card:hover{
    box-shadow:0 4px 14px rgba(0,0,0,.08);
}

.discount-badge{
    position:absolute;
    top:10px;
    left:10px;
    background:#ff6f61;
    color:#fff;
    font-size:11px;
    font-weight:700;
    padding:4px 8px;
    border-radius:4px;
    z-index:2;
}

.product-image-box{
    height:210px;
    background:#fff;
    padding:18px;
    display:flex;
    align-items:center;
    justify-content:center;
}

.product-image{
    max-width:100%;
    max-height:100%;
    object-fit:contain;
}

.product-body{
    padding:14px;
}

.product-title{
    font-size:14px;
    font-weight:500;
    color:#212121;
    line-height:22px;
    height:44px;
    overflow:hidden;
    margin-bottom:8px;
}

.product-company{
    font-size:12px;
    color:#666;
    margin-bottom:10px;
}

.rating-row{
    display:flex;
    align-items:center;
    gap:6px;
    margin-bottom:12px;
}

.rating-badge{
    background:#1aab2a;
    color:#fff;
    border-radius:4px;
    padding:2px 6px;
    font-size:11px;
    font-weight:600;
}

.rating-count{
    font-size:12px;
    color:#666;
}

.price-row{
    display:flex;
    align-items:center;
    gap:8px;
    flex-wrap:wrap;
    margin-bottom:14px;
}

.price{
    font-size:18px;
    font-weight:700;
    color:#212121;
}

.mrp{
    font-size:13px;
    color:#999;
    text-decoration:line-through;
}

.off{
    font-size:12px;
    color:#1aab2a;
    font-weight:600;
}

.product-actions{
    display:flex;
    gap:8px;
}

.view-btn{
    flex:1;
    height:38px;
    border:1px solid #ff6f61;
    background:#fff;
    color:#ff6f61;
    border-radius:6px;
    font-size:13px;
    font-weight:600;
    text-decoration:none;
}

.cart-btn{
    flex:1;
    height:38px;
    border:none;
    background:#ff6f61;
    color:#fff;
    border-radius:6px;
    font-size:13px;
    font-weight:600;
}

/* =========================================
TRUST SECTION
========================================= */

.trust-grid{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:16px;
}

.trust-card{
    background:#fff;
    border:1px solid #ececec;
    border-radius:8px;
    padding:24px 18px;
    text-align:center;
}

.trust-card i{
    font-size:34px;
    color:#ff6f61;
    margin-bottom:14px;
}

.trust-card h5{
    font-size:16px;
    font-weight:700;
    margin-bottom:8px;
}

.trust-card p{
    font-size:13px;
    color:#666;
    margin:0;
    line-height:22px;
}

/* =========================================
RESPONSIVE
========================================= */

@media(max-width:1400px){

    .product-grid{
        grid-template-columns:repeat(4,1fr);
    }

}

@media(max-width:991px){

    .hero-wrapper{
        grid-template-columns:1fr;
    }

    .product-grid{
        grid-template-columns:repeat(2,1fr);
    }

    .trust-grid{
        grid-template-columns:repeat(2,1fr);
    }

}

@media(max-width:768px){

    .health-header h2{
        font-size:24px;
    }

    .health-card{
        min-width:140px;
        max-width:140px;
        padding:12px;
    }

    .health-image{
        height:110px;
    }

}

@media(max-width:576px){

    .product-grid{
        grid-template-columns:1fr;
    }

    .trust-grid{
        grid-template-columns:1fr;
    }

}
/* =========================================
BRANDS SCROLL
========================================= */

.brand-grid{
    display:flex;
    gap:16px;
    overflow-x:auto;
    overflow-y:hidden;
    scrollbar-width:none;
    scroll-behavior:smooth;
    padding-bottom:10px;
}

.brand-grid::-webkit-scrollbar{
    display:none;
}

.brand-card{
    min-width:220px;
    max-width:220px;
    flex-shrink:0;
    background:#fff;
    border:1px solid #ececec;
    border-radius:12px;
    padding:14px;
    text-decoration:none;
    transition:.25s;
    display:block;
}

.brand-card:hover{
    transform:translateY(-3px);
    box-shadow:0 6px 18px rgba(0,0,0,.08);
}

.brand-image{
    width:100%;
    height:150px;
    border-radius:10px;
    overflow:hidden;
    background:#f8f8f8;
    margin-bottom:12px;
}

.brand-image img{
    width:100%;
    height:100%;
    object-fit:contain;
}

.brand-content h4{
    font-size:15px;
    font-weight:600;
    color:#212121;
    margin-bottom:6px;
}

.brand-content p{
    font-size:13px;
    color:#666;
    margin:0;
}
</style>

<div class="home-section">

    <div class="container-fluid">

        <!-- HERO -->

        <div class="hero-wrapper">

            <div class="hero-banner">

                <img
                    src="https://onemg.gumlet.io/2025-08%2F1756293934_Ayur_960x200.png"
                    alt=""
                >

            </div>

            <div class="side-banner">

                <div class="side-banner-card">

                    <img
                        src="https://onemg.gumlet.io/2025-02%2F1739789153_1948x800+%281%29.png"
                        alt=""
                    >

                </div>

       

            </div>

        </div>

        <!-- SHOP BY HEALTH CONCERNS -->

        <div class="health-section">

            <div class="health-header">

                <h2>
                    Shop by health concerns
                </h2>

            </div>

            <div class="health-scroll">

                @forelse($categories as $category)

                    @php

                        $catImage = asset('images/default-category.png');

                        if (!empty($category['image'])) {

                            if (filter_var($category['image'], FILTER_VALIDATE_URL)) {

                                $catImage = $category['image'];

                            } elseif (str_starts_with($category['image'], 'categories/')) {

                                $catImage = 'https://pharma-catalog-assets.s3.us-east-1.amazonaws.com/' . $category['image'];

                            } else {

                                $catImage = Storage::url($category['image']);
                            }
                        }

                    @endphp

      <a
href="{{ url('/category/'.($category['slug'] ?? $category['id'])) }}"    class="health-card"
>

                        <div class="health-image">

                            <img
                                src="{{ $catImage }}"
                                alt="{{ $category['name'] }}"
                                onerror="this.src='{{ asset('images/default-category.png') }}'"
                            >

                        </div>

                        <h4>

                            {{ $category['name'] }}

                        </h4>

                    </a>

                @empty

                    <div class="bg-white rounded-3 p-4">

                        No Categories Found

                    </div>

                @endforelse

            </div>

        </div>



        <!-- POPULAR CATEGORIES -->

<div class="section-box">

    <div class="section-header">

        <h2 class="section-title">
            Popular Categories
        </h2>

    </div>

    <div class="brand-grid">

        @forelse($popularCategories as $category)

            <a
                href="{{ url('/category/'.$category['slug']) }}"
                class="brand-card"
            >

                <div class="brand-image">

                    <img
                        src="{{ $category['image'] }}"
                        alt="{{ $category['name'] }}"
                    >

                </div>

                <div class="brand-content">

                    <h4>
                        {{ $category['name'] }}
                    </h4>

                    <p>
                        {{ $category['total_products'] }} Products
                    </p>

                </div>

            </a>

        @empty

            <div class="bg-white rounded-3 p-4">

                No Categories Found

            </div>

        @endforelse

    </div>

</div>
<!-- BRANDS -->

<div class="section-box">

    <div class="section-header">

        <h2 class="section-title">
            Popular Brands
        </h2>

        <a href="{{ route('brands.index') }}" class="view-all">
            View All
        </a>

    </div>

    <div class="brand-grid">

        @forelse(collect($brands)->take(12) as $brand)

            <a
                href="{{ url('/brand/'.$brand['brand']) }}"
                class="brand-card"
            >

                <div class="brand-image">

                    <img
                        src="{{ $brand['image'] }}"
                        alt="{{ $brand['brand'] }}"
                    >

                </div>

                <div class="brand-content">

                    <h4>
                        {{ $brand['brand'] }}
                    </h4>

                    <p>
                        {{ $brand['total_products'] }} Products
                    </p>

                </div>

            </a>

        @empty

            <div class="bg-white rounded-3 p-4">

                No Brands Found

            </div>

        @endforelse

    </div>

</div>     <!-- DYNAMIC HOME SECTIONS -->

@forelse($homeSections as $section)

<div class="section-box">

    <div class="section-header">

        <h2 class="section-title">

            {{ $section['title'] }}

        </h2>

        <a href="#" class="view-all">

            View All

        </a>

    </div>

    @if(empty($section['products']))

        <div class="bg-white rounded-3 p-5 text-center">

            <h5>No products available</h5>

        </div>

    @else

        <div class="brand-grid">

            @foreach($section['products'] as $product)

            @php

                $image = asset('images/default-medicine.png');

                if (!empty($product['main_image'])) {

                    if (filter_var($product['main_image'], FILTER_VALIDATE_URL)) {

                        $image = $product['main_image'];

                    } elseif (str_starts_with($product['main_image'], 'items/')) {

                        $image = 'https://pharma-catalog-assets.s3.us-east-1.amazonaws.com/' . $product['main_image'];

                    } else {

                        $image = Storage::url($product['main_image']);
                    }
                }

            @endphp

            <div class="brand-card p-0 overflow-hidden">

                <div class="position-relative">

                    <div class="discount-badge">

                        15% OFF

                    </div>

                    <div class="product-image-box">

                        <img
                            src="{{ $image }}"
                            class="product-image"
                            alt="{{ $product['name'] }}"
                            onerror="this.src='{{ asset('images/default-medicine.png') }}'"
                        >

                    </div>

                </div>

                <div class="product-body">

                    <h3 class="product-title">

                        {{ $product['name'] }}

                    </h3>

                    <div class="product-company">

                        {{ $product['manufacturer_name'] ?? 'Pharma Product' }}

                    </div>

                    <div class="rating-row">

                        <span class="rating-badge">
                            4.4 ★
                        </span>

                        <span class="rating-count">
                            1,245 Ratings
                        </span>

                    </div>

                    <div class="price-row">

                        <span class="price">

                            ₹{{ number_format($product['selling_price'] ?? $product['price'] ?? 0, 2) }}

                        </span>

                    </div>

                    <div class="product-actions">

                        <a
                            href="{{ route('product.show', $product['id']) }}"
                            class="view-btn d-flex align-items-center justify-content-center"
                        >
                            View
                        </a>

                        <form
                            action="{{ route('cart.add') }}"
                            method="POST"
                            style="flex:1;"
                        >

                            @csrf

                            <input
                                type="hidden"
                                name="batch_id"
                                value="{{ $product['batch_id'] ?? '' }}"
                            >

                            <input
                                type="hidden"
                                name="qty"
                                value="1"
                            >

                            <button
                                type="submit"
                                class="cart-btn w-100"
                            >

                                Add

                            </button>

                        </form>

                    </div>

                </div>

            </div>

            @endforeach

        </div>

    @endif

</div>

@empty

<div class="bg-white rounded-4 p-5 text-center">

    <h5>

        No Homepage Sections Found

    </h5>

</div>

@endforelse
        </div>

        <!-- TRUST -->

        <div class="section-box">

            <div class="section-header">

                <h2 class="section-title">
                    Why Choose Us
                </h2>

            </div>

            <div class="trust-grid">

                <div class="trust-card">

                    <i class="fa-solid fa-truck-fast"></i>

                    <h5>Fast Delivery</h5>

                    <p>
                        Medicines delivered quickly at your doorstep.
                    </p>

                </div>

                <div class="trust-card">

                    <i class="fa-solid fa-capsules"></i>

                    <h5>Genuine Medicines</h5>

                    <p>
                        100% authentic medicines from trusted suppliers.
                    </p>

                </div>

                <div class="trust-card">

                    <i class="fa-solid fa-headset"></i>

                    <h5>24x7 Support</h5>

                    <p>
                        Dedicated support team always available for help.
                    </p>

                </div>

                <div class="trust-card">

                    <i class="fa-solid fa-shield-heart"></i>

                    <h5>Secure Payments</h5>

                    <p>
                        Safe and secure payment experience every time.
                    </p>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection