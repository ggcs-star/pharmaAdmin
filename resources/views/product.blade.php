@extends('layouts.app')

@section('title', $product['name'] . ' - MediLife')

@section('content')
<div class="container py-4 py-md-5">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-secondary">Home</a></li>
            <li class="breadcrumb-item active fw-medium" aria-current="page">{{ $product['name'] }}</li>
        </ol>
    </nav>
    
    {{-- Product Container --}}
    <div class="card border-0 rounded-4 shadow-lg overflow-hidden">
        <div class="card-body p-4 p-lg-5">
            <div class="row g-4 g-lg-5 align-items-center">
                {{-- Left Column - Image --}}
                <div class="col-md-5">
                    <div class="product-image-wrapper bg-light rounded-4 p-4 d-flex align-items-center justify-content-center">
                        @php
$image = asset('images/default-medicine.png');

if (!empty($product['main_image'])) {

    if (filter_var($product['main_image'], FILTER_VALIDATE_URL)) {
        // External URL (Excel import)
        $image = $product['main_image'];

    } else {
        // S3 uploaded image
        $image = Storage::disk('s3')->url($product['main_image']);
    }
}
@endphp
      <img 
    src="{{ $image }}" 
    class="img-fluid product-image" 
    alt="{{ $product['name'] }}"
    style="width: 100%; height: 400px; object-fit: contain;"
    onerror="this.onerror=null;this.src='{{ asset('images/default-medicine.png') }}';"
>
>
                    </div>
                </div>
                
                {{-- Right Column - Details --}}
                <div class="col-md-7">
                    {{-- Title --}}
                    <h1 class="display-6 fw-bold mb-2 lh-sm">{{ $product['name'] }}</h1>
                    
                    {{-- Rating --}}
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="text-warning">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                        <span class="text-secondary small">4.5 (2.3k ratings)</span>
                    </div>
                    
                    {{-- Price Section --}}
                    <div class="price-wrapper bg-light rounded-3 p-3 mb-4">
                        <div class="d-flex align-items-center flex-wrap gap-2 gap-md-3">
                            <span class="text-success fw-bold fs-2">₹{{ number_format($product['price'] ?? 0, 2) }}</span>
                            @if(isset($product['mrp']) && $product['mrp'] > $product['price'])
                                <span class="text-muted text-decoration-line-through fs-5">₹{{ number_format($product['mrp'], 2) }}</span>
                                <span class="badge bg-success fs-6 px-3 py-2 rounded-pill">
                                    {{ round((($product['mrp'] - $product['price']) / $product['mrp']) * 100) }}% OFF
                                </span>
                            @endif
                        </div>
                        <p class="text-success small mb-0 mt-2">
                            <i class="fas fa-tag me-1"></i>Inclusive of all taxes
                        </p>
                    </div>
                    
                    {{-- Description --}}
                    @if(isset($product['description']))
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-2">Description</h6>
                            <p class="text-muted lh-base">{{ $product['description'] }}</p>
                        </div>
                    @endif
                    
                    {{-- Key Benefits --}}
                    <div class="mb-4">
                        <h6 class="fw-semibold mb-3">Key Benefits</h6>
                        <ul class="text-muted list-unstyled">
                            <li class="mb-2">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Prescription medicine
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Doctor consultation available
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Free delivery on orders above ₹499
                            </li>
                        </ul>
                    </div>
                    
                    {{-- Action Buttons --}}
                    <form action="{{ route('cart.add') }}" method="POST">
                        @csrf
<input type="hidden" name="batch_id" value="{{ $product['batch_id'] }}">
                        <input type="hidden" name="qty" value="1">
                        
                        <div class="d-flex gap-3 flex-wrap flex-sm-nowrap">
                            <button type="submit" class="btn btn-success btn-lg flex-grow-1 shadow-sm">
                                <i class="fas fa-cart-plus me-2"></i>Add to Cart
                            </button>
                           <button type="button" 
onclick="buyNow({{ $product['batch_id'] }})"
        class="btn btn-outline-success btn-lg px-4 shadow-sm">
    <i class="fas fa-bolt me-2"></i>Buy Now
</button>
                        </div>
                    </form>
                    
                    {{-- Trust Badges --}}
                    <div class="mt-4 pt-3 border-top">
                        <div class="row text-center g-2">
                            <div class="col-4">
                                <div class="p-2 rounded-3">
                                    <i class="fas fa-truck text-success fs-5 mb-1"></i>
                                    <small class="d-block text-muted fw-medium">Free Delivery</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-2 rounded-3">
                                    <i class="fas fa-shield-alt text-success fs-5 mb-1"></i>
                                    <small class="d-block text-muted fw-medium">Authentic</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-2 rounded-3">
                                    <i class="fas fa-undo-alt text-success fs-5 mb-1"></i>
                                    <small class="d-block text-muted fw-medium">Easy Returns</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modern UI Enhancements CSS --}}
<style>
    /* Product Image Container */
    .product-image-wrapper {
        min-height: 400px;
        position: relative;
        overflow: hidden;
    }
    
    /* Product Image */
    .product-image {
        transition: transform 0.3s ease;
        max-width: 100%;
        height: auto;
    }
    
    .product-image-wrapper:hover .product-image {
        transform: scale(1.05);
    }
    
    /* Button Styles */
    .btn-success {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        border: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn-success:hover {
        background: linear-gradient(135deg, #218838 0%, #1aa179 100%);
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(40, 167, 69, 0.25) !important;
    }
    
    .btn-outline-success {
        border-width: 2px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn-outline-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(40, 167, 69, 0.15) !important;
    }
    
    /* Badge Enhancement */
    .badge.bg-success {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;
        font-weight: 500;
        letter-spacing: 0.3px;
    }
    
    /* Card Shadow */
    .shadow-lg {
        box-shadow: 0 1rem 3rem rgba(0,0,0,0.1) !important;
    }
    
    /* Price Wrapper */
    .price-wrapper {
        border: 1px solid rgba(40, 167, 69, 0.1);
    }
    
    /* Success Icon Color */
    .text-success {
        color: #20c997 !important;
    }
    
    /* Breadcrumb Enhancement */
    .breadcrumb-item + .breadcrumb-item::before {
        color: #6c757d;
    }
    
    .breadcrumb-item a:hover {
        color: #20c997 !important;
    }
    
    /* General Transitions */
    .card, .btn, .badge {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .product-image-wrapper {
            min-height: 300px;
        }
        
        .product-image {
            height: 300px !important;
        }
    }
    
    @media (max-width: 576px) {
        .display-6 {
            font-size: 1.75rem;
        }
        
        .btn-lg {
            padding: 0.75rem 1rem;
            font-size: 1rem;
        }
        
        .product-image-wrapper {
            min-height: 250px;
        }
        
        .product-image {
            height: 250px !important;
        }
    }
</style>
<script>
function buyNow(batchId) {

    let formData = new FormData();
    formData.append('batch_id', batchId);
    formData.append('qty', 1);

    fetch("{{ route('cart.add') }}", {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {

        if (data.success) {
            // 🔥 direct checkout page pe redirect
            window.location.href = "/cart";
        } else {
            alert(data.message || 'Something went wrong');
        }

    })
    .catch(err => {
        console.log(err);
        alert('Please Login to continue');
    });
}
</script>
@endsection