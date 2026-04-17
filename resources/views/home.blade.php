@extends('layouts.app')

@section('title', 'MediLife - Your Trusted Online Pharmacy')

@section('content')
<style>
    /* Custom Animations */
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
    }
    
    @keyframes slideInLeft {
        from {
            opacity: 0;
            transform: translateX(-50px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(50px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }
    
    .hero-section {
        background: linear-gradient(135deg, #0f766e 0%, #14b8a6 50%, #2dd4bf 100%);
        position: relative;
        overflow: hidden;
    }
    
    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.1)" fill-opacity="1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,154.7C960,171,1056,181,1152,165.3C1248,149,1344,107,1392,85.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
        background-size: cover;
        opacity: 0.3;
        pointer-events: none;
    }
    
    .hero-content {
        animation: slideInLeft 0.8s ease-out;
    }
    
    .hero-image {
        animation: slideInRight 0.8s ease-out;
    }
    
    .floating-element {
        animation: float 3s ease-in-out infinite;
    }
    
    .category-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }
    
    .category-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        transition: left 0.5s;
    }
    
    .category-card:hover::before {
        left: 100%;
    }
    
    .category-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 30px -12px rgba(0, 0, 0, 0.15);
    }
    
    .product-card {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        opacity: 0;
        animation: fadeInUp 0.6s ease-out forwards;
    }
    
    .product-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 25px 35px -12px rgba(0, 0, 0, 0.2);
    }
    
    .product-image-wrapper {
        overflow: hidden;
        position: relative;
    }
    
    .product-image {
        transition: transform 0.5s ease;
    }
    
    .product-card:hover .product-image {
        transform: scale(1.1);
    }
    
    .discount-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        background: linear-gradient(135deg, #ef4444, #dc2626);
        animation: pulse 2s ease-in-out infinite;
    }
    
    .add-to-cart-btn {
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .add-to-cart-btn::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255,255,255,0.3);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }
    
    .add-to-cart-btn:hover::before {
        width: 300px;
        height: 300px;
    }
    
    .feature-card {
        transition: all 0.3s ease;
    }
    
    .feature-card:hover {
        transform: translateY(-5px);
        background: linear-gradient(135deg, #ffffff, #f0fdf4);
    }
    
    .feature-icon {
        transition: transform 0.3s ease;
    }
    
    .feature-card:hover .feature-icon {
        transform: scale(1.1) rotate(5deg);
    }
    
    .stagger-item:nth-child(1) { animation-delay: 0.1s; }
    .stagger-item:nth-child(2) { animation-delay: 0.2s; }
    .stagger-item:nth-child(3) { animation-delay: 0.3s; }
    .stagger-item:nth-child(4) { animation-delay: 0.4s; }
    .stagger-item:nth-child(5) { animation-delay: 0.5s; }
    .stagger-item:nth-child(6) { animation-delay: 0.6s; }
    
    .glass-effect {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
    }
    
    .hover-scale {
        transition: transform 0.3s ease;
    }
    
    .hover-scale:hover {
        transform: scale(1.02);
    }
</style>

<!-- Hero Banner Section -->
<section class="hero-section text-white py-5 position-relative">
    <div class="container position-relative" style="z-index: 2;">
        <div class="row align-items-center min-vh-50">
            <div class="col-lg-6 hero-content">
                <div class="mb-4">
                    <span class="badge bg-white text-teal-600 px-3 py-2 rounded-pill mb-3 shadow-sm">
                        <i class="fas fa-check-circle me-1"></i> Trusted by 10L+ Customers
                    </span>
                </div>
                <h1 class="display-3 fw-bold mb-3">
                    Your Health, <br>
                    <span class="text-warning">Our Priority</span>
                </h1>
                <p class="lead mb-4 opacity-90">
                    Get authentic medicines and healthcare products delivered to your doorstep with free delivery* on orders above ₹499
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <button class="btn btn-light btn-lg px-5 py-3 rounded-pill fw-bold shadow-lg hover-scale">
                        <i class="fas fa-shopping-bag me-2"></i>Shop Now
                    </button>
                    <button class="btn btn-outline-light btn-lg px-5 py-3 rounded-pill fw-bold hover-scale">
                        <i class="fas fa-stethoscope me-2"></i>Consult Doctor
                    </button>
                </div>
                
                <!-- Trust Badges -->
                <div class="row mt-5 g-3">
                    <div class="col-4">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-truck fa-2x text-warning"></i>
                            <div>
                                <small class="d-block fw-bold">Free Delivery</small>
                                <small class="opacity-75">On ₹499+</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-shield-alt fa-2x text-warning"></i>
                            <div>
                                <small class="d-block fw-bold">100% Genuine</small>
                                <small class="opacity-75">Guaranteed</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-clock fa-2x text-warning"></i>
                            <div>
                                <small class="d-block fw-bold">24/7 Support</small>
                                <small class="opacity-75">Always here</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6 text-center hero-image">
               <div class="floating-element">
    <img src="{{ asset('images/healthcare.png') }}" 
         alt="Healthcare Services" 
         class="img-fluid rounded-4 shadow-xxl" 
         style="border-radius: 30px; box-shadow: 0 30px 40px -20px rgba(0,0,0,0.3);">
</div>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section with Enhanced Design -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge bg-teal-100 text-teal-600 px-3 py-2 rounded-pill mb-3">
                <i class="fas fa-tag me-1"></i> Categories
            </span>
            <h2 class="display-5 fw-bold mb-3">Shop by Categories</h2>
            <p class="text-muted fs-5">Find what you need across our wide range of categories</p>
        </div>
        
        <div class="row g-4">
            @foreach($categories as $index => $category)
            <div class="col-6 col-md-4 col-lg-2 stagger-item">
                <div class="category-card text-center p-4 bg-white rounded-4 shadow-sm h-100">
                    <div class="mb-3 position-relative">
                        <div class="display-1 mb-2">{{ $category['icon'] }}</div>
                        <div class="position-absolute top-0 end-0">
                            <span class="badge bg-teal-500 rounded-pill">{{ rand(100, 500) }}+</span>
                        </div>
                    </div>
                    <h6 class="fw-bold mb-2">{{ $category['name'] }}</h6>
                    <small class="text-muted">Shop Now <i class="fas fa-arrow-right ms-1"></i></small>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Products Grid with Enhanced Design -->
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-5 flex-wrap gap-3">
            <div>
                <span class="badge bg-teal-100 text-teal-600 px-3 py-2 rounded-pill mb-2">
                    <i class="fas fa-fire me-1"></i> Hot Deals
                </span>
                <h2 class="display-5 fw-bold mb-0">Popular Products</h2>
                <p class="text-muted mt-2">Most trusted medicines and health products</p>
            </div>
            <!-- <div>
                <div class="btn-group" role="group">
                    <button class="btn btn-outline-teal active">All</button>
                    <button class="btn btn-outline-teal">Medicines</button>
                    <button class="btn btn-outline-teal">Healthcare</button>
                    <button class="btn btn-outline-teal">Wellness</button>
                </div>
            </div> -->
        </div>
        
        @if(empty($products))
            <div class="text-center py-5 bg-light rounded-4">
                <i class="fas fa-box-open fa-5x text-muted mb-4"></i>
                <h3 class="fw-bold">No products available</h3>
                <p class="text-muted">Please check back later for amazing deals!</p>
            </div>
        @else
            <div class="row g-4">
                @foreach($products as $index => $product)
                <div class="col-md-6 col-lg-4 col-xl-3 product-card" style="animation-delay: {{ $index * 0.05 }}s">
                    <div class="bg-white rounded-4 shadow-sm h-100 d-flex flex-column">
                        <div class="product-image-wrapper position-relative rounded-top-4">
                          @php
$image = asset('images/default-medicine.png');

if (!empty($product['main_image'])) {

    if (filter_var($product['main_image'], FILTER_VALIDATE_URL)) {
        // External URL
        $image = $product['main_image'];

    } else {
        // S3 path
$image = Storage::url($product['main_image']);    }
}
@endphp

<img src="{{ $image }}"
     class="product-image w-100"
     alt="{{ $product['name'] }}"
     style="height: 220px; object-fit: cover;"
     onerror="this.onerror=null;this.src='{{ asset('images/default-medicine.png') }}';">
                            @if(isset($product['discount']) && $product['discount'] > 0)
                                <div class="discount-badge position-absolute top-0 start-0 m-3">
                                    <span class="badge bg-danger rounded-pill px-3 py-2 shadow-sm">
                                        {{ $product['discount'] }}% OFF
                                    </span>
                                </div>
                            @endif
                            
                            <div class="position-absolute top-0 end-0 m-3">
                                <button class="btn btn-light btn-sm rounded-circle shadow-sm wishlist-btn" style="width: 35px; height: 35px;">
                                    <i class="far fa-heart"></i>
                                </button>
                            </div>
                            
                            <div class="position-absolute bottom-0 start-0 end-0 bg-gradient-to-t from-black/50 to-transparent p-3">
                                <div class="d-flex gap-2">
                                    <span class="badge bg-white text-teal-600 rounded-pill">
                                        <i class="fas fa-star text-warning me-1"></i>4.5
                                    </span>
                                    <span class="badge bg-white text-success rounded-pill">
                                        <i class="fas fa-shopping-bag me-1"></i>In Stock
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-body p-4 d-flex flex-column flex-grow-1">
                            <h6 class="card-title fw-bold mb-2 line-clamp-2" style="min-height: 48px;">
                                {{ $product['name'] }}
                            </h6>
                            
                            <div class="mb-3">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <div class="text-warning">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star-half-alt"></i>
                                    </div>
                                    <small class="text-muted">(1,234 reviews)</small>
                                </div>
                                
                                <div class="d-flex align-items-baseline gap-2 flex-wrap">
                                    <span class="text-success fw-bold fs-3">
                                        ₹{{ number_format($product['selling_price'] ?? $product['price'] ?? 0, 2) }}
                                    </span>
                                    @if(isset($product['mrp']) && $product['mrp'] > ($product['selling_price'] ?? $product['price']))
                                        <span class="text-muted text-decoration-line-through">
                                            ₹{{ number_format($product['mrp'], 2) }}
                                        </span>
                                        <span class="badge bg-success">
                                            Save ₹{{ number_format($product['mrp'] - ($product['selling_price'] ?? $product['price']), 2) }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="mt-auto">
                                <div class="d-flex gap-2">
                                    <a href="{{ route('product.show', $product['id']) }}" 
                                       class="btn btn-outline-success flex-grow-1 rounded-pill">
                                        <i class="fas fa-eye me-1"></i> View
                                    </a>
                                    <form action="{{ route('cart.add') }}" method="POST" class="flex-grow-1">
                                        @csrf
<input type="hidden" name="batch_id" value="{{ $product['batch_id'] }}">                                        <input type="hidden" name="qty" value="1">
                                        <button type="submit" class="add-to-cart-btn btn btn-success w-100 rounded-pill position-relative overflow-hidden">
                                            <i class="fas fa-cart-plus me-1"></i> Add
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Load More Button -->
            <!-- <div class="text-center mt-5">
                <button class="btn btn-outline-teal btn-lg px-5 rounded-pill hover-scale">
                    Load More Products <i class="fas fa-arrow-down ms-2"></i>
                </button>
            </div> -->
        @endif
    </div>
</section>

<!-- Features Section with Enhanced Design -->
<section class="py-5 bg-gradient-to-r from-teal-50 to-blue-50">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-3">
                <div class="feature-card text-center p-4 bg-white rounded-4 shadow-sm h-100">
                    <div class="feature-icon mb-3">
                        <div class="bg-teal-100 rounded-circle d-inline-flex p-3">
                            <i class="fas fa-truck fa-3x text-teal-600"></i>
                        </div>
                    </div>
                    <h6 class="fw-bold mb-2">Free Delivery</h6>
                    <small class="text-muted">On orders above ₹499</small>
                    <div class="mt-2">
                        <small class="text-teal-600">Learn More <i class="fas fa-arrow-right ms-1"></i></small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="feature-card text-center p-4 bg-white rounded-4 shadow-sm h-100">
                    <div class="feature-icon mb-3">
                        <div class="bg-teal-100 rounded-circle d-inline-flex p-3">
                            <i class="fas fa-shield-alt fa-3x text-teal-600"></i>
                        </div>
                    </div>
                    <h6 class="fw-bold mb-2">100% Authentic</h6>
                    <small class="text-muted">Guaranteed quality products</small>
                    <div class="mt-2">
                        <small class="text-teal-600">Learn More <i class="fas fa-arrow-right ms-1"></i></small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="feature-card text-center p-4 bg-white rounded-4 shadow-sm h-100">
                    <div class="feature-icon mb-3">
                        <div class="bg-teal-100 rounded-circle d-inline-flex p-3">
                            <i class="fas fa-clock fa-3x text-teal-600"></i>
                        </div>
                    </div>
                    <h6 class="fw-bold mb-2">24/7 Support</h6>
                    <small class="text-muted">Customer care available</small>
                    <div class="mt-2">
                        <small class="text-teal-600">Learn More <i class="fas fa-arrow-right ms-1"></i></small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="feature-card text-center p-4 bg-white rounded-4 shadow-sm h-100">
                    <div class="feature-icon mb-3">
                        <div class="bg-teal-100 rounded-circle d-inline-flex p-3">
                            <i class="fas fa-undo-alt fa-3x text-teal-600"></i>
                        </div>
                    </div>
                    <h6 class="fw-bold mb-2">Easy Returns</h6>
                    <small class="text-muted">Hassle-free refunds</small>
                    <div class="mt-2">
                        <small class="text-teal-600">Learn More <i class="fas fa-arrow-right ms-1"></i></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('styles')
<style>
    .btn-outline-teal {
        color: #0d9488;
        border-color: #0d9488;
    }
    .btn-outline-teal:hover {
        background-color: #0d9488;
        border-color: #0d9488;
        color: white;
    }
    .bg-teal-100 {
        background-color: #ccfbf1;
    }
    .text-teal-600 {
        color: #0d9488;
    }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .shadow-xxl {
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }
</style>
@endpush

@push('scripts')
<script>
    // Add to cart animation
    document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            // Show ripple effect
            const ripple = document.createElement('span');
            ripple.style.position = 'absolute';
            ripple.style.borderRadius = '50%';
            ripple.style.backgroundColor = 'rgba(255,255,255,0.6)';
            ripple.style.transform = 'scale(0)';
            ripple.style.animation = 'ripple 0.6s linear';
            this.appendChild(ripple);
            
            setTimeout(() => ripple.remove(), 600);
        });
    });
    
    // Wishlist functionality
    document.querySelectorAll('.wishlist-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const icon = this.querySelector('i');
            if (icon.classList.contains('far')) {
                icon.classList.remove('far');
                icon.classList.add('fas');
                icon.style.color = '#ef4444';
                // Show toast message
                showToast('Added to wishlist!', 'success');
            } else {
                icon.classList.remove('fas');
                icon.classList.add('far');
                icon.style.color = '';
                showToast('Removed from wishlist', 'info');
            }
        });
    });
    
    // Toast notification function
    function showToast(message, type) {
        const toast = document.createElement('div');
        toast.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
        toast.style.bottom = '20px';
        toast.style.right = '20px';
        toast.style.zIndex = '9999';
        toast.style.minWidth = '250px';
        toast.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'info-circle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }
    
    // Category filter animation
    document.querySelectorAll('.category-card').forEach(card => {
        card.addEventListener('click', function() {
            const category = this.querySelector('h6').innerText;
            showToast(`Filtering by ${category}`, 'info');
        });
    });
</script>
@endpush
@endsection