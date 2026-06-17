@extends('layouts.app')

@section('title', $brand)

@section('content')

<style>

.brand-products-page{
    padding:30px 0;
    background:#f6f6f6;
}

.page-title{
    font-size:28px;
    font-weight:700;
    margin-bottom:24px;
    color:#212121;
}

.product-grid{
    display:grid;
    grid-template-columns:repeat(5,1fr);
    gap:16px;
}

.product-card{
    background:#fff;
    border:1px solid #ececec;
    border-radius:10px;
    overflow:hidden;
    transition:.25s;
}

.product-card:hover{
    transform:translateY(-3px);
    box-shadow:0 6px 18px rgba(0,0,0,.08);
}

.product-image-box{
    height:220px;
    padding:16px;
    display:flex;
    align-items:center;
    justify-content:center;
    background:#fff;
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
    font-weight:600;
    line-height:22px;
    height:44px;
    overflow:hidden;
    margin-bottom:10px;
}

.product-price{
    font-size:18px;
    font-weight:700;
    color:#212121;
    margin-bottom:14px;
}

.view-btn{
    width:100%;
    height:40px;
    border:none;
    background:#ff6f61;
    color:#fff;
    border-radius:6px;
    font-size:14px;
    font-weight:600;
    text-decoration:none;
    display:flex;
    align-items:center;
    justify-content:center;
}

@media(max-width:1200px){

    .product-grid{
        grid-template-columns:repeat(4,1fr);
    }

}

@media(max-width:768px){

    .product-grid{
        grid-template-columns:repeat(2,1fr);
    }

}

</style>

<div class="brand-products-page">

    <div class="container-fluid">

        <h2 class="page-title">
            {{ $brand }}
        </h2>

        <div class="product-grid">

            @forelse($products as $product)

                @php

                    $image = $product['main_image']
                        ?? asset('images/default-medicine.png');

                @endphp

                <div class="product-card">

                    <div class="product-image-box">

                        <img
                            src="{{ $image }}"
                            class="product-image"
                            alt="{{ $product['name'] }}"
                        >

                    </div>

                    <div class="product-body">

                        <h3 class="product-title">

                            {{ $product['name'] }}

                        </h3>

                        <div class="product-price">

                            ₹{{ number_format($product['selling_price'] ?? 0, 2) }}

                        </div>

                        <a
                            href="{{ route('product.show', $product['id']) }}"
                            class="view-btn"
                        >
                            View Product
                        </a>

                    </div>

                </div>

            @empty

                <div class="bg-white rounded-3 p-5">

                    No Products Found

                </div>

            @endforelse

        </div>

    </div>

</div>

@endsection 