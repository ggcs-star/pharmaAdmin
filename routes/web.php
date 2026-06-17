<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PrescriptionController;

/*
|--------------------------------------------------------------------------
| FRONTEND ROUTES
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| HOME
|--------------------------------------------------------------------------
*/

Route::get('/', [
    HomeController::class,
    'index'
])->name('home');

Route::get('/auth/check', [
    AuthController::class,
    'checkAuth'
])->name('auth.check');
/*
|--------------------------------------------------------------------------
| AUTH SUCCESS
|--------------------------------------------------------------------------
*/

Route::get('/auth-success', [
    AuthController::class,
    'googleLogin'
]);

/*
|--------------------------------------------------------------------------
| PROFILE
|--------------------------------------------------------------------------
*/

Route::get('/profile', [
    OrderController::class,
    'profile'
])->name('profile');

/*
|--------------------------------------------------------------------------
| LOGIN PAGE
|--------------------------------------------------------------------------
*/

Route::get('/login', function () {

    return view('auth.login-register');

})->name('login');

/*
|--------------------------------------------------------------------------
| DASHBOARD
|--------------------------------------------------------------------------
*/
Route::get('/', [
    HomeController::class,
    'index'
])->name('home');

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/

Route::post(
    '/auth/login',
    [AuthController::class, 'login']
)->name('login.submit');

Route::post(
    '/auth/register',
    [AuthController::class, 'register']
)->name('register.submit');

Route::post(
    '/verify-otp',
    [AuthController::class, 'verifyOtp']
)->name('verify.otp');

Route::post(
    '/send-device-otp',
    [AuthController::class, 'sendDeviceOtp']
)->name('send.device.otp');

Route::post(
    '/verify-device-otp',
    [AuthController::class, 'verifyDeviceOtp']
)->name('verify.device.otp');
Route::post(
    '/forgot-password',
    [AuthController::class, 'forgotPassword']
)->name('forgot.password');

Route::post(
    '/resend-otp',
    [AuthController::class, 'resendOtp']
)->name('resend.otp');

Route::post(
    '/reset-password',
    [AuthController::class, 'resetPassword']
)->name('reset.password');

Route::post(
    '/logout',
    [AuthController::class, 'logout']
)->name('logout');

/*
|--------------------------------------------------------------------------
| PRODUCT ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/product/{id}', [
    ProductController::class,
    'show'
])->name('product.show');

Route::get('/category/{slug}', [
    ProductController::class,
    'categoryProducts'
])->name('category.products');

Route::get('/brands', [
    HomeController::class,
    'brands'
])->name('brands.index');

Route::get('/brand/{brand}', [
    HomeController::class,
    'brandProducts'
]);

/*
|--------------------------------------------------------------------------
| CART ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/cart', [
    CartController::class,
    'index'
])->name('cart');

Route::post('/cart/add', [
    CartController::class,
    'addToCart'
])->name('cart.add');

Route::post('/cart/update', [
    CartController::class,
    'update'
])->name('cart.update');

Route::get('/cart/remove/{id}', [
    CartController::class,
    'remove'
])->name('cart.remove');

Route::get('/cart/clear', [
    CartController::class,
    'clear'
])->name('cart.clear');

/*
|--------------------------------------------------------------------------
| PRESCRIPTION
|--------------------------------------------------------------------------
*/

Route::post('/prescription/upload', [
    PrescriptionController::class,
    'upload'
]);

/*
|--------------------------------------------------------------------------
| PAYMENT
|--------------------------------------------------------------------------
*/

Route::post('/payment/create', [
    PaymentController::class,
    'create'
]);

Route::post('/payment/verify', [
    PaymentController::class,
    'verify'
])->name('payment.verify');

/*
|--------------------------------------------------------------------------
| ORDERS
|--------------------------------------------------------------------------
*/

Route::get('/orders', [
    OrderController::class,
    'index'
])->name('orders');

Route::get('/orders/{id}', [
    OrderController::class,
    'show'
])->name('orders.show');

Route::post('/orders/place', [
    OrderController::class,
    'place'
])->name('orders.place');

Route::post('/orders/cancel/{id}', [
    OrderController::class,
    'cancel'
])->name('orders.cancel');

/*
|--------------------------------------------------------------------------
| ADDRESS PAGE
|--------------------------------------------------------------------------
*/

Route::get('/addresses', function () {

    if (!session('user_token')) {

        return redirect()->route('login');
    }

    return view('addresses');

});

/*
|--------------------------------------------------------------------------
| SAVE SESSION
|--------------------------------------------------------------------------
*/

Route::post('/save-user-session', function (
    Request $request
) {

    session([

        'user_token' =>
            $request->token,

        'user_name' =>
            $request->user_name
    ]);

    return response()->json([

        'status' => true
    ]);
});