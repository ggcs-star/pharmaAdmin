<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="api-token" content="{{ session('user_token') }}">
    <title>@yield('title', 'MediLife - Your Trusted Online Pharmacy')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        
        body {
            background-color: #f8f9fa;
        }
        
        .navbar-brand {
            font-weight: 800;
            font-size: 1.5rem;
        }
        
        .hero-gradient {
            background: linear-gradient(135deg, #0d9488 0%, #14b8a6 50%, #2dd4bf 100%);
        }
        
        .product-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-radius: 16px;
            overflow: hidden;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
        }
        
        .category-card {
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .category-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        
        .btn-primary-custom {
            background-color: #0d9488;
            border-color: #0d9488;
        }
        
        .btn-primary-custom:hover {
            background-color: #0f766e;
            border-color: #0f766e;
        }
        
        .cart-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background-color: #ef4444;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .alert-fixed {
            position: fixed;
            top: 80px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            animation: slideInRight 0.3s ease;
        }
        
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        .quantity-input {
            width: 60px;
            text-align: center;
        }
        
        .cart-item-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 12px;
        }
        
        /* Footer Styles */
        .footer {
            background: linear-gradient(135deg, #1a202c 0%, #2d3748 100%);
            position: relative;
            overflow: hidden;
        }
        
        .footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #0d9488, #14b8a6, #2dd4bf);
        }
        
        .footer-logo {
            font-size: 1.8rem;
            font-weight: 800;
        }
        
        .social-icon {
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transition: all 0.3s ease;
            color: #cbd5e0;
        }
        
        .social-icon:hover {
            background: #0d9488;
            color: white;
            transform: translateY(-3px);
        }
        
        .footer-link {
            color: #cbd5e0;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
        }
        
        .footer-link:hover {
            color: #2dd4bf;
            transform: translateX(5px);
        }
        
        .contact-item {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
            color: #cbd5e0;
        }
        
        .contact-icon {
            width: 35px;
            height: 35px;
            background: rgba(13, 148, 136, 0.2);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #2dd4bf;
        }
        
        .newsletter-input {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            border-radius: 50px;
            padding: 12px 20px;
        }
        
        .newsletter-input:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: #0d9488;
            outline: none;
            box-shadow: none;
            color: white;
        }
        
        .newsletter-input::placeholder {
            color: #94a3b8;
        }
        
        .btn-newsletter {
            background: #0d9488;
            border: none;
            border-radius: 50px;
            padding: 12px 28px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-newsletter:hover {
            background: #0f766e;
            transform: translateY(-2px);
        }
        
        .payment-icons i {
            font-size: 32px;
            color: #cbd5e0;
            transition: all 0.3s ease;
        }
        
        .payment-icons i:hover {
            color: #2dd4bf;
            transform: translateY(-2px);
        }
        
        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 20px;
        }
        
        .scroll-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 45px;
            height: 45px;
            background: #0d9488;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
        }
        
        .scroll-top.show {
            opacity: 1;
            visibility: visible;
        }
        
        .scroll-top:hover {
            background: #0f766e;
            transform: translateY(-3px);
        }
        
        @media (max-width: 768px) {
            .footer {
                text-align: center;
            }
            
            .contact-item {
                justify-content: center;
            }
            
            .social-icons {
                justify-content: center;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    
    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand text-teal-700" href="{{ route('home') }}">
                <i class="fas fa-tablets text-teal-600 me-2"></i>
                Pharma<span class="text-teal-500">Labs</span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarContent">
                <!-- Search Bar -->
                <form class="d-flex mx-auto w-50" role="search" method="GET" action="{{ route('home') }}">
                    <div class="input-group">
                        <input class="form-control" type="search" placeholder="Search for medicines, health products..." name="search">
                        <button class="btn btn-success" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
                
                <!-- Right Menu -->
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    @if(session('user_token'))
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-1"></i>
                                {{ session('user_name', 'Account') }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('orders') }}"><i class="fas fa-box me-2"></i>My Orders</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="{{ route('logout') }}"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt me-1"></i> Login
                            </a>
                        </li>
                    @endif
                    
                    <li class="nav-item position-relative">
                        <a class="nav-link" href="{{ route('cart') }}">
                            <i class="fas fa-shopping-cart"></i> Cart
                        @php
    $cartCount = 0;

    if(auth()->check()){
        $cartCount = \DB::table('carts')
            ->where('user_id', auth()->id())
            ->sum('qty');
    }
@endphp

@if($cartCount > 0)
    <span class="cart-badge">{{ $cartCount }}</span>
@endif
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show alert-fixed" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show alert-fixed" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    <!-- Main Content -->
    <main>
        @yield('content')
    </main>
    
    <!-- Footer -->
    <footer class="footer text-white mt-5 py-5">
        <div class="container">
            <div class="row">
                <!-- Company Info -->
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <div class="mb-3">
                        <h3 class="footer-logo">
                            <i class="fas fa-tablets text-teal-400 me-2"></i>
                            Pharma<span class="text-teal-400">Labs</span>
                        </h3>
                        <p class="text-muted mt-2">
                            Your trusted partner in health and wellness. Providing authentic medicines and healthcare products since 2020.
                        </p>
                    </div>
                    
                    <!-- Social Links -->
                    <div class="social-icons d-flex gap-3">
                        <a href="#" class="social-icon">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="social-icon">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="social-icon">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="social-icon">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a href="#" class="social-icon">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div class="col-lg-2 col-md-4 mb-4 mb-md-0">
                    <h6 class="fw-bold mb-3">Quick Links</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="footer-link"><i class="fas fa-chevron-right me-2 fa-xs"></i>About Us</a></li>
                        <li class="mb-2"><a href="#" class="footer-link"><i class="fas fa-chevron-right me-2 fa-xs"></i>Contact Us</a></li>
                        <li class="mb-2"><a href="#" class="footer-link"><i class="fas fa-chevron-right me-2 fa-xs"></i>FAQs</a></li>
                        <li class="mb-2"><a href="#" class="footer-link"><i class="fas fa-chevron-right me-2 fa-xs"></i>Privacy Policy</a></li>
                        <li class="mb-2"><a href="#" class="footer-link"><i class="fas fa-chevron-right me-2 fa-xs"></i>Terms & Conditions</a></li>
                    </ul>
                </div>
                
                <!-- Categories -->
                <div class="col-lg-2 col-md-4 mb-4 mb-md-0">
                    <h6 class="fw-bold mb-3">Categories</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="footer-link"><i class="fas fa-chevron-right me-2 fa-xs"></i>Medicines</a></li>
                        <li class="mb-2"><a href="#" class="footer-link"><i class="fas fa-chevron-right me-2 fa-xs"></i>Personal Care</a></li>
                        <li class="mb-2"><a href="#" class="footer-link"><i class="fas fa-chevron-right me-2 fa-xs"></i>Health Devices</a></li>
                        <li class="mb-2"><a href="#" class="footer-link"><i class="fas fa-chevron-right me-2 fa-xs"></i>Wellness</a></li>
                        <li class="mb-2"><a href="#" class="footer-link"><i class="fas fa-chevron-right me-2 fa-xs"></i>Ayurveda</a></li>
                    </ul>
                </div>
                
                <!-- Contact Info -->
                <div class="col-lg-4 col-md-4">
                    <h6 class="fw-bold mb-3">Contact Information</h6>
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div>
                            123 Health Street, Andheri East<br>
                            Mumbai, Maharashtra - 400069
                        </div>
                    </div>
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div>+91 98765 43210</div>
                    </div>
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div>support@medilife.com</div>
                    </div>
                </div>
            </div>
            
            <!-- Newsletter Section -->
            <div class="row mt-4 pt-3">
                <div class="col-lg-8 mx-auto text-center">
                    <h6 class="fw-bold mb-3">Subscribe to our Newsletter</h6>
                    <p class="text-muted small mb-3">Get health tips, offers & updates directly in your inbox</p>
                    <div class="input-group mb-3" style="max-width: 500px; margin: 0 auto;">
                        <input type="email" class="form-control newsletter-input" placeholder="Enter your email address">
                        <button class="btn btn-newsletter" type="button">
                            Subscribe <i class="fas fa-paper-plane ms-2"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Payment Methods & Copyright -->
            <div class="footer-bottom mt-4 pt-3">
                <div class="row align-items-center">
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        <div class="payment-icons d-flex gap-3 justify-content-center justify-content-md-start">
                            <i class="fab fa-cc-visa"></i>
                            <i class="fab fa-cc-mastercard"></i>
                            <i class="fab fa-cc-amex"></i>
                            <i class="fab fa-cc-paypal"></i>
                            <i class="fab fa-google-pay"></i>
                            <i class="fab fa-apple-pay"></i>
                            <i class="fas fa-lock"></i>
                        </div>
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <small class="text-muted">
                            &copy; 2024 MediLife Pharmacy. All rights reserved. | 
                            <a href="#" class="text-muted text-decoration-none">Sitemap</a>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Scroll to Top Button -->
    <div class="scroll-top" id="scrollTop">
        <i class="fas fa-arrow-up"></i>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            document.querySelectorAll('.alert-fixed').forEach(alert => {
                alert.style.display = 'none';
            });
        }, 5000);
        
        // Scroll to Top functionality
        const scrollTop = document.getElementById('scrollTop');
        
        window.addEventListener('scroll', () => {
            if (window.scrollY > 300) {
                scrollTop.classList.add('show');
            } else {
                scrollTop.classList.remove('show');
            }
        });
        
        scrollTop.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
        
        // Newsletter subscription
        const newsletterBtn = document.querySelector('.btn-newsletter');
        if (newsletterBtn) {
            newsletterBtn.addEventListener('click', function() {
                const emailInput = document.querySelector('.newsletter-input');
                const email = emailInput.value;
                
                if (email && email.includes('@')) {
                    showToast('Subscribed successfully! Check your inbox.', 'success');
                    emailInput.value = '';
                } else {
                    showToast('Please enter a valid email address', 'error');
                }
            });
        }
        
        // Toast function for newsletter
        function showToast(message, type) {
            const toast = document.createElement('div');
            toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed`;
            toast.style.bottom = '20px';
            toast.style.right = '20px';
            toast.style.zIndex = '9999';
            toast.style.minWidth = '280px';
            toast.style.animation = 'slideInRight 0.3s ease';
            toast.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(toast);
            setTimeout(() => {
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }
    </script>
    
    @stack('scripts')
</body>
</html>