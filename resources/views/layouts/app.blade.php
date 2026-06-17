<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="api-token" content="{{ session('user_token') }}">
    <title>@yield('title','Rapid Retail Pharmacy')</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background: #f6f6f6;
            overflow-x: hidden;
            color: #212121;
        }

        a {
            text-decoration: none;
        }

        .container-fluid {
            padding-left: 32px !important;
            padding-right: 32px !important;
        }

        .main-header {
            background: #fff;
            position: sticky;
            top: 0;
            z-index: 99999;
            border-bottom: 1px solid #ececec;
        }

        .top-navbar {
            height: 80px;
            border-bottom: 1px solid #ececec;
            display: flex;
            align-items: center;
            background: #fff;
        }

        .top-navbar-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }

        .top-left-menu {
            display: flex;
            align-items: center;
            gap: 22px;
        }

       .logo-link {
    margin-right: 15px;
    display: flex;
    align-items: center;
    min-width: 200px;
}

.main-logo {
    height: 70px;
    width: auto;
    object-fit: contain;
    display: block;
}

        .top-left-menu a {
            color: #212121;
            font-size: 14px;
            font-weight: 600;
            position: relative;
            padding-bottom: 20px;
            white-space: nowrap;
        }

        .top-left-menu a.active-menu {
            color: #ff6f61;
        }

        .top-left-menu a.active-menu::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 100%;
            height: 3px;
            background: #ff6f61;
        }

        .save-more-badge {
            background: #ff6f61;
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 3px;
        }

        .top-right-menu {
            display: flex;
            align-items: center;
            gap: 22px;
        }

        .top-right-menu a {
            color: #212121;
            font-size: 14px;
            font-weight: 500;
        }

        .cart-link {
            position: relative;
            color: #212121 !important;
            font-size: 22px !important;
        }

        .cart-badge {
            position: absolute;
            top: -6px;
            right: -10px;
            min-width: 18px;
            height: 18px;
            border-radius: 50%;
            background: #ff6b6b;
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 5px;
        }

        .profile-btn {
            width: 38px;
            height: 38px;
            border: none;
            border-radius: 50%;
            background: #fff3f1;
            color: #ff6f61;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: 0.2s;
        }

        .profile-btn:hover {
            background: #ffebe8;
        }

        .dropdown-menu {
            border: none;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
            padding: 10px;
        }

        .dropdown-item {
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 14px;
        }

        .dropdown-item:hover {
            background: #f5f5f5;
        }

        .search-navbar {
            height: 60px;
            border-bottom: 1px solid #ececec;
            background: #fff;
            display: flex;
            align-items: center;
        }

        .search-navbar-inner {
            display: flex;
            align-items: center;
            gap: 16px;
            width: 100%;
        }

        .location-box {
            width: 250px;
            height: 42px;
            background: #f3f4f6;
            border-radius: 6px;
            display: flex;
            align-items: center;
            padding: 0 14px;
            gap: 10px;
            font-size: 14px;
            font-weight: 600;
        }

        .location-target {
            margin-left: auto;
            color: #777;
        }

        .search-wrapper {
            flex: 1;
            position: relative;
        }

        .search-input {
            width: 100%;
            height: 42px;
            border: 1px solid #ddd;
            background: #f8f8f8;
            border-radius: 6px;
            padding: 0 50px 0 18px;
            outline: none;
            font-size: 14px;
        }

        .search-input:focus {
            background: #fff;
            border-color: #ff6f61;
        }

        .search-btn {
            position: absolute;
            top: 0;
            right: 0;
            width: 48px;
            height: 42px;
            border: none;
            background: none;
            color: #666;
        }

        .quick-order-wrapper {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .quick-order-wrapper span {
            font-size: 14px;
            font-weight: 500;
            white-space: nowrap;
        }

        .quick-order-wrapper button {
            height: 42px;
            border: none;
            background: #ff6f61;
            color: #fff;
            border-radius: 6px;
            padding: 0 20px;
            font-size: 14px;
            font-weight: 700;
        }

        .category-navbar {
            height: 42px;
            border-bottom: 1px solid #ececec;
            background: #fff;
        }

        .category-scroll {
            height: 42px;
            display: flex;
            align-items: center;
            gap: 28px;
            overflow-x: auto;
        }

        .category-scroll::-webkit-scrollbar {
            display: none;
        }

        .category-scroll a {
            color: #3d3d3d;
            font-size: 14px;
            white-space: nowrap;
        }

        main {
            min-height: 70vh;
        }

        .footer {
            background: #fff;
            margin-top: 50px;
            border-top: 1px solid #ececec;
            padding: 50px 0 20px;
        }

        .footer-logo {
            height: 32px;
            width: auto;
        }

        .footer-text {
            margin-top: 14px;
            color: #666;
            line-height: 1.8;
            font-size: 14px;
        }

        .footer-title {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 16px;
        }

        .footer-links {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .footer-links a {
            color: #666;
            font-size: 14px;
        }

        .footer-bottom {
            margin-top: 35px;
            border-top: 1px solid #ececec;
            padding-top: 18px;
            text-align: center;
            color: #777;
            font-size: 13px;
        }

        @media (max-width: 991px) {
            .top-navbar {
                height: auto;
                padding: 12px 0;
            }
            .top-navbar-inner {
                flex-direction: column;
                gap: 14px;
                align-items: flex-start;
            }
            .top-left-menu {
                overflow-x: auto;
                width: 100%;
            }
            .top-right-menu {
                width: 100%;
                justify-content: flex-end;
            }
            .search-navbar {
                height: auto;
                padding: 14px 0;
            }
            .search-navbar-inner {
                flex-direction: column;
            }
            .location-box {
                width: 100%;
            }
            .quick-order-wrapper {
                width: 100%;
                justify-content: space-between;
            }
        }

        @media (max-width: 576px) {
            .container-fluid {
                padding-left: 14px !important;
                padding-right: 14px !important;
            }
            .quick-order-wrapper {
                display: none;
            }
            .top-left-menu {
                gap: 16px;
            }
        }

        .user-greeting {
            font-size: 14px;
            font-weight: 600;
            color: #212121;
            background: #fff3f1;
            padding: 6px 14px;
            border-radius: 30px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .user-greeting i {
            color: #ff6f61;
        }

        .login-link {
            color: #ff6f61 !important;
            font-weight: 600 !important;
        }
        
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <header class="main-header">
        <div class="top-navbar">
            <div class="container-fluid">
                <div class="top-navbar-inner">
                    <div class="top-left-menu">
                        <a href="{{ route('home') }}" class="logo-link">
                            <img src="{{ asset('images/mediquick-logo.png') }}" alt="MediQuick" class="main-logo">
                        </a>
                        <a href="#" class="active-menu">MEDICINES</a>
                        <a href="#">LAB TESTS</a>
                        <a href="#">CONSULT DOCTORS</a>
                        <a href="#">AYURVEDA</a>
                        <a href="#">CARE PLAN</a>
                        <span class="save-more-badge">SAVE MORE</span>
                    </div>

                    <div class="top-right-menu">
                        <a href="#">Offers</a>
                        <a href="{{ route('cart') }}" class="cart-link">
                            <i class="fa-solid fa-cart-shopping"></i>
                            <span class="cart-badge" id="cart-count">{{ session('cart_count', 0) }}</span>
                        </a>

                        @if(session()->has('user_token'))
                            <div class="dropdown">
                                <button class="profile-btn" data-bs-toggle="dropdown">
                                    <i class="fa-regular fa-user"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="#"><i class="fa-regular fa-user me-2"></i>{{ session('user_name') }}</a></li>
                                    <li><a class="dropdown-item" href="{{ route('orders') }}"><i class="fa-solid fa-box me-2"></i>My Orders</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="fa-solid fa-heart me-2"></i>Wishlist</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                <li>
    <button type="button" id="logoutBtn" class="dropdown-item text-danger">
        <i class="fa-solid fa-right-from-bracket me-2"></i>Logout
    </button>
</li>
                                </ul>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="login-link">
                                <i class="fa-regular fa-circle-user"></i> Login / Signup
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="search-navbar">
            <div class="container-fluid">
                <div class="search-navbar-inner">
                    <div class="location-box">
                        <i class="fa-solid fa-location-dot"></i>
                        <span>Ahmedabad</span>
                        <i class="fa-solid fa-crosshairs location-target"></i>
                    </div>
                    <div class="search-wrapper">
                        <form action="#" method="GET">
                            <input type="text" name="q" class="search-input" placeholder="Search for medicines and healthcare products">
                            <button type="submit" class="search-btn"><i class="fa-solid fa-magnifying-glass"></i></button>
                        </form>
                    </div>
                    <div class="quick-order-wrapper">
                        <span>⚡ Get medicines delivered in 30 minutes</span>
                        <button>Quick Order</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="category-navbar">
            <div class="container-fluid">
                <div class="category-scroll">
                    @if(isset($categories) && count($categories))
                        @foreach($categories as $category)
                            <a href="{{ url('/category/' . ($category['slug'] ?? $category['id'])) }}">
                                {{ $category['name'] ?? '' }}
                            </a>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <img src="{{ asset('images/mediquick-logo.png') }}" class="footer-logo" alt="MediQuick">
                    <p class="footer-text">India's trusted healthcare platform for medicines, healthcare products and online doctor consultations.</p>
                </div>
                <div class="col-lg-2 col-md-4 mb-4">
                    <h5 class="footer-title">Company</h5>
                    <div class="footer-links">
                        <a href="#">About Us</a>
                        <a href="#">Contact Us</a>
                        <a href="#">Careers</a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 mb-4">
                    <h5 class="footer-title">Categories</h5>
                    <div class="footer-links">
                        <a href="#">Medicines</a>
                        <a href="#">Healthcare</a>
                        <a href="#">Wellness</a>
                    </div>
                </div>
                <div class="col-lg-4">
                    <h5 class="footer-title">Support</h5>
                    <div class="footer-links">
                        <a href="#">support@rapidretail.com</a>
                        <a href="#">+91 9876543210</a>
                        <a href="#">Ahmedabad, Gujarat</a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                © {{ date('Y') }} Rapid Retail Pharmacy. All Rights Reserved.
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
   <script>

document
.getElementById(
    'logoutBtn'
)
?.addEventListener(
    'click',
    async function () {

        try {

            const response =
                await fetch(
                    '{{ route("logout") }}',
                    {
                        method: 'POST',

                        headers: {

                            'Accept':
                                'application/json',

                            'Content-Type':
                                'application/json',

                            'Authorization':
                                'Bearer ' +
                                localStorage.getItem(
                                    'auth_token'
                                ),

                            'X-CSRF-TOKEN':
                                document.querySelector(
                                    'meta[name="csrf-token"]'
                                ).getAttribute(
                                    'content'
                                ),

                            'X-Device-ID':
                                localStorage.getItem(
                                    'device_id'
                                )
                        }
                    }
                );

            const data =
                await response.json();

            if (data.success) {

                /*
                |--------------------------------------------------------------------------
                | REMOVE ONLY AUTH
                |--------------------------------------------------------------------------
                */

                localStorage.removeItem(
                    'auth_token'
                );

                localStorage.removeItem(
                    'user_data'
                );

                /*
                |--------------------------------------------------------------------------
                | DO NOT REMOVE DEVICE ID
                |--------------------------------------------------------------------------
                */

                // localStorage.removeItem('device_id');

                Swal.fire({

                    icon: 'success',

                    title: 'Logged Out',

                    text:
                        'You have been logged out successfully',

                    confirmButtonColor:
                        '#ff6f61'

                }).then(() => {

                    window.location.href =
                        '/login';
                });

            } else {

                Swal.fire({

                    icon: 'error',

                    title: 'Logout Failed',

                    text:
                        data.message ||
                        'Something went wrong',

                    confirmButtonColor:
                        '#ff6f61'
                });
            }

        } catch (error) {

            console.error(
                'Logout Error:',
                error
            );

            Swal.fire({

                icon: 'error',

                title: 'Error',

                text:
                    'Logout failed',

                confirmButtonColor:
                    '#ff6f61'
            });
        }
    }
);

</script>

    @stack('scripts')
</body>
</html>