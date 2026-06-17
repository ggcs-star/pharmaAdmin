@extends('layouts.app')

@section('title', 'All Brands')

@section('content')

<style>

.brand-page{
    padding:30px 0;
    background:#f6f6f6;
}

.brand-grid{
    display:grid;
    grid-template-columns:repeat(6,1fr);
    gap:16px;
}

.brand-card{
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
    height:130px;
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
    font-size:14px;
    font-weight:600;
    color:#212121;
    margin-bottom:6px;
}

.brand-content p{
    font-size:12px;
    color:#666;
    margin:0;
}

@media(max-width:1200px){

    .brand-grid{
        grid-template-columns:repeat(4,1fr);
    }

}

@media(max-width:768px){

    .brand-grid{
        grid-template-columns:repeat(2,1fr);
    }

}

</style>

<div class="brand-page">

    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <h2 class="fw-bold mb-0">
                All Brands
            </h2>

        </div>

        <div class="brand-grid">

            @forelse($brands as $brand)

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

        <div class="mt-4">

            {{ $brands->links() }}

        </div>

    </div>

</div>

@endsection