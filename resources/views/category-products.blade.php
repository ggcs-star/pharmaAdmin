@extends('layouts.app')

@section('title', ucfirst($categorySlug))

@section('content')

<div class="container py-4">

    <h2 class="mb-4 text-capitalize">

        {{ str_replace('-', ' ', $categorySlug) }}

    </h2>

    <div class="row">

        @forelse($products as $product)

            <div class="col-lg-3 col-md-4 col-6 mb-4">

                <div class="card border-0 shadow-sm h-100">

                    <div
                        style="
                            height:220px;
                            display:flex;
                            align-items:center;
                            justify-content:center;
                            padding:15px;
                            background:#fff;
                        "
                    >

                        <img
                            src="{{ $product['main_image'] ?? asset('images/default-medicine.png') }}"
                            style="
                                max-width:100%;
                                max-height:100%;
                                object-fit:contain;
                            "
                        >

                    </div>

                    <div class="card-body">

                        <h6
                            style="
                                font-size:14px;
                                line-height:22px;
                                min-height:44px;
                            "
                        >

                            {{ $product['name'] }}

                        </h6>

                        <div class="fw-bold mb-3">

                            ₹{{ $product['price'] ?? 0 }}

                        </div>

                        <a
                            href="{{ route('product.show', $product['id']) }}"
                            class="btn btn-danger w-100"
                        >

                            View Product

                        </a>

                    </div>

                </div>

            </div>

        @empty

            <div class="col-12">

                <div class="alert alert-warning">

                    No products found

                </div>

            </div>

        @endforelse

    </div>

</div>

@endsection