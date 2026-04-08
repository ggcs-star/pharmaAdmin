<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;


/*
|--------------------------------------------------------------------------
| Web Routes (Frontend Only)
|--------------------------------------------------------------------------
*/

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');

// Auth Routes
Route::get('/login', [AuthController::class, 'index'])->name('login');

Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('verify.otp');
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot.password');

Route::post('/resend-otp', [AuthController::class, 'resendOtp'])->name('resend.otp');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/category/{slug}', [ProductController::class, 'categoryProducts']);
// Cart Routes (Requires Auth - Checked in Controller)
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::get('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

Route::post('/payment/verify', [PaymentController::class, 'verify'])->name('payment.verify');
Route::get('/orders', [OrderController::class, 'index'])->name('orders');
Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
Route::post('/payment/create', [PaymentController::class, 'create']);
Route::post('/orders/place', [OrderController::class, 'place']);
Route::post('/orders/place', [OrderController::class, 'place'])->name('orders.place');
Route::post('/orders/cancel/{id}', [OrderController::class, 'cancel'])->name('orders.cancel');

// Checkout Route
Route::get('/addresses', function () {
    if (!session('user_token')) {
        return redirect()->route('login');
    }

    return view('addresses');
});