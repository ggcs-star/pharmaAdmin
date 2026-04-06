@extends('layouts.app')

@section('title', $product['name'] . ' - MediLife')

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $product['name'] }}</li>
        </ol>
    </nav>
    
    <div class="row">
        <div class="col-md-5 mb-4">
            <div class="bg-white rounded-4 shadow-sm p-4 text-center">
                @if(isset($product['image']))
                    <img src="{{ $product['image'] }}" class="img-fluid" alt="{{ $product['name'] }}" style="max-height: 400px;">
                @else
                    <i class="fas fa-capsules fa-8x text-muted"></i>
                @endif
            </div>
        </div>
        
        <div class="col-md-7">
            <div class="bg-white rounded-4 shadow-sm p-4">
                <h1 class="fw-bold mb-3">{{ $product['name'] }}</h1>
                
                <div class="mb-3">
                    <span class="text-success fw-bold fs-2">₹{{ number_format($product['price'] ?? 0, 2) }}</span>
                    @if(isset($product['mrp']) && $product['mrp'] > $product['price'])
                        <span class="text-muted text-decoration-line-through ms-2">₹{{ number_format($product['mrp'], 2) }}</span>
                        <span class="badge bg-success ms-2">{{ round((($product['mrp'] - $product['price']) / $product['mrp']) * 100) }}% OFF</span>
                    @endif
                </div>
                
                @if(isset($product['description']))
                    <div class="mb-4">
                        <h6>Description</h6>
                        <p class="text-muted">{{ $product['description'] }}</p>
                    </div>
                @endif
                
                <div class="mb-4">
                    <h6>Key Benefits</h6>
                    <ul class="text-muted">
                        <li>Prescription medicine</li>
                        <li>Doctor consultation available</li>
                        <li>Free delivery on orders above ₹499</li>
                    </ul>
                </div>
                
                <form action="{{ route('cart.add') }}" method="POST">
                    @csrf
                    <input type="hidden" name="batch_id" value="{{ $product['id'] }}">
                    <input type="hidden" name="qty" value="1">
                    
                    <div class="d-flex gap-3">
                        <button type="submit" class="btn btn-success btn-lg flex-grow-1">
                            <i class="fas fa-cart-plus me-2"></i>Add to Cart
                        </button>
                        <button type="button" class="btn btn-outline-success btn-lg">
                            <i class="fas fa-bolt me-2"></i>Buy Now
                        </button>
                    </div>
                </form>
                
                <div class="mt-4 pt-3 border-top">
                    <div class="row text-center">
                        <div class="col-4">
                            <i class="fas fa-truck text-teal-600"></i>
                            <small class="d-block text-muted">Free Delivery</small>
                        </div>
                        <div class="col-4">
                            <i class="fas fa-shield-alt text-teal-600"></i>
                            <small class="d-block text-muted">Authentic</small>
                        </div>
                        <div class="col-4">
                            <i class="fas fa-undo-alt text-teal-600"></i>
                            <small class="d-block text-muted">Easy Returns</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection