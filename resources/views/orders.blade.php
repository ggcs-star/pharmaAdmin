@extends('layouts.app')
@php
    $s3Base = "https://pharma-catalog-assets.s3.us-east-1.amazonaws.com";
@endphp
@section('content')

<div class="container py-4 py-md-5">

    {{-- 🔥 DETAIL PAGE --}}
    @if(isset($order))

        {{-- Page Header --}}
        <div class="d-flex align-items-center gap-3 mb-4">
            <a href="{{ url('/orders') }}" class="btn btn-light rounded-circle p-2 lh-1 shadow-sm border-0 hover-lift">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
            </a>
            <div>
                <h3 class="mb-0 fw-bold">Order Details</h3>
                <p class="text-muted small mb-0">Order #{{ $order['id'] }}</p>
            </div>
        </div>

        <div class="row g-4">
            {{-- Left Column --}}
            <div class="col-lg-8">
                
                {{-- Order Summary Card --}}
                <div class="card shadow-sm border-0 rounded-4 p-4 mb-4 hover-card">
                    <div class="d-flex flex-wrap align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-gradient-primary p-3 rounded-3 text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.91 8.84 8.56 2.23a1.93 1.93 0 0 0-1.81 0L3.1 4.13a2.12 2.12 0 0 0-.95 1.68v6.7c0 .73.39 1.4.98 1.77l12.31 7.11c.6.35 1.34.35 1.94 0l4.57-2.64a2.12 2.12 0 0 0 .98-1.77v-6.7c0-.73-.38-1.4-.98-1.77Z"/><path d="M12 5.5v12"/><path d="m20 5-8 4.5L4 5"/></svg>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1 small text-uppercase fw-semibold tracking-wide">Order ID</h6>
                                <h5 class="fw-bold mb-0">#{{ $order['id'] }}</h5>
                                <span class="text-muted small">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                                    Placed on {{ isset($order['created_at']) ? \Carbon\Carbon::parse($order['created_at'])->format('d M, Y') : 'Just now' }}
                                </span>
                            </div>
                        </div>
                        <div class="text-md-end mt-3 mt-md-0">
                            <h6 class="text-muted mb-1 small text-uppercase fw-semibold tracking-wide">Total Amount</h6>
                            <h4 class="fw-bold text-success mb-0">₹{{ number_format($order['total'], 2) }}</h4>
                            <span class="badge bg-success bg-opacity-10 text-success px-3 py-1 rounded-pill mt-2">
                                {{ $order['payment_status'] ?? 'Paid' }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- 🔥 ORDER TRACKING --}}
                <div class="card shadow-sm border-0 rounded-4 p-4 mb-4 hover-card">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="mb-0 fw-semibold">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2 text-primary"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                            Order Tracking
                        </h5>
                        @if($order['status'] != 'delivered' && $order['status'] != 'cancelled')
                            <span class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                                Est. Delivery: {{ isset($order['created_at']) ? \Carbon\Carbon::parse($order['created_at'])->addDays(5)->format('d M, Y') : 'In 5 days' }}
                            </span>
                        @endif
                    </div>

                    @php
                        $steps = ['pending','confirmed','processing','shipped','delivered'];
                        $current = $order['status'];
                        $currentIndex = array_search($current, $steps);
                        if ($currentIndex === false) $currentIndex = 0;
                        
                        $stepIcons = [
                            'pending' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>',
                            'confirmed' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="M22 4 12 14.01l-3-3"/></svg>',
                            'processing' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>',
                            'shipped' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2z"/><path d="M22 18v-8a2 2 0 0 0-2-2h-4v10h4a2 2 0 0 0 2-2z"/><path d="M8 18h8"/></svg>',
                            'delivered' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>'
                        ];
                        
                        $stepLabels = [
                            'pending' => 'Order Placed',
                            'confirmed' => 'Confirmed',
                            'processing' => 'Processing',
                            'shipped' => 'Shipped',
                            'delivered' => 'Delivered'
                        ];
                    @endphp

                    <div class="position-relative px-2">
                        {{-- Progress Bar Background --}}
                        <div class="progress bg-light rounded-pill" style="height: 8px;">
                            <div class="progress-bar bg-gradient-success rounded-pill" role="progressbar" 
                                 style="width: {{ $currentIndex == 0 ? 10 : ($currentIndex / (count($steps) - 1)) * 100 }}%;" 
                                 aria-valuenow="{{ ($currentIndex / (count($steps) - 1)) * 100 }}" 
                                 aria-valuemin="0" aria-valuemax="100"></div>
                        </div>

                        {{-- Steps --}}
                        <div class="d-flex justify-content-between position-relative mt-2">
                            @foreach($steps as $step)
                                @php
                                    $stepIndex = array_search($step, $steps);
                                    $isCompleted = $stepIndex <= $currentIndex;
                                    $isActive = $stepIndex == $currentIndex;
                                @endphp
                                <div class="text-center" style="width: 80px; margin-top: -32px;">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2
                                        {{ $isCompleted ? 'bg-gradient-success text-white' : 'bg-light text-secondary' }}"
                                        style="width: 48px; height: 48px; 
                                               box-shadow: {{ $isActive ? '0 0 0 5px rgba(25, 135, 84, 0.2)' : '0 2px 8px rgba(0,0,0,0.05)' }};
                                               transition: all 0.3s ease;">
                                        {!! $stepIcons[$step] !!}
                                    </div>
                                    <small class="d-block fw-medium {{ $isCompleted ? 'text-dark' : 'text-muted' }}" style="font-size: 0.75rem;">
                                        {{ $stepLabels[$step] }}
                                    </small>
                                    @if($isActive)
                                        <span class="badge bg-success mt-1 px-2 py-0 small">Current</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    {{-- Tracking Details --}}
                    <div class="mt-5 pt-3 border-top">
                        <div class="d-flex gap-3">
                            <div class="bg-gradient-success p-3 rounded-3 text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                            </div>
                            <div>
                                <p class="mb-1 fw-semibold">Current Status</p>
                                <p class="text-muted small mb-0">
                                    @switch($order['status'])
                                        @case('pending')
                                            Your order has been received and is awaiting confirmation.
                                            @break
                                        @case('confirmed')
                                            Order confirmed! We are preparing your items for shipment.
                                            @break
                                        @case('processing')
                                            Your order is being processed and packed with care.
                                            @break
                                        @case('shipped')
                                            Your package is on the way! Track with ID: <strong>TRK{{ str_pad($order['id'], 8, '0', STR_PAD_LEFT) }}</strong>
                                            @break
                                        @case('delivered')
                                            Delivered on {{ isset($order['updated_at']) ? \Carbon\Carbon::parse($order['updated_at'])->format('d M, Y') : 'Today' }}. Enjoy!
                                            @break
                                        @default
                                            Status: {{ ucfirst($order['status']) }}
                                    @endswitch
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 🔥 ITEMS --}}
                <div class="card shadow-sm border-0 rounded-4 p-4 hover-card">
                    <h5 class="mb-4 fw-semibold d-flex align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2 text-primary"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                        Order Items ({{ count($order['items']) }})
                    </h5>
                    
                    @foreach($order['items'] as $item)
                        <div class="d-flex flex-column flex-sm-row gap-3 border-bottom pb-3 mb-3 item-row">
                            <div class="flex-shrink-0">
                                <img src="{{ 
                                    !empty($item['item']['main_image']) 
                                        ? $s3Base.'/'.$item['item']['main_image']
                                        : asset('no-image.png') 
                                }}" 
                                     class="rounded-3 border"
                                     style="width: 100px; height: 100px; object-fit: cover;"
                                     alt="{{ $item['product']['name'] ?? 'Item' }}"
                                     onerror="this.src='{{ asset('no-image.png') }}'">
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex flex-wrap justify-content-between align-items-start">
                                    <div>
                                        <h6 class="fw-semibold mb-1">{{ $item['product']['name'] ?? 'Item' }}</h6>
                                        <p class="text-muted small mb-2">
                                            <span class="badge bg-light text-dark me-2">Qty: {{ $item['qty'] }}</span>
                                            <span>₹{{ number_format($item['price'], 2) }} each</span>
                                        </p>
                                    </div>
                                    <span class="fw-bold text-dark fs-5">₹{{ number_format($item['qty'] * $item['price'], 2) }}</span>
                                </div>
                                @if(!empty($item['status']))
                                    <span class="badge bg-warning bg-opacity-10 text-warning mt-2">{{ $item['status'] }}</span>
                                @endif
                            </div>
                        </div>
                    @endforeach

                    {{-- Price Breakdown --}}
                    <div class="mt-4 pt-2 bg-light rounded-4 p-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Subtotal</span>
                            <span class="fw-medium">₹{{ number_format($order['subtotal'] ?? $order['total'], 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Shipping</span>
                            <span class="text-success fw-medium">Free</span>
                        </div>
                        @if(isset($order['discount']) && $order['discount'] > 0)
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Discount</span>
                            <span class="text-danger">-₹{{ number_format($order['discount'], 2) }}</span>
                        </div>
                        @endif
                        <hr class="my-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold fs-5">Total</span>
                            <span class="fw-bold text-success fs-4">₹{{ number_format($order['total'], 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column --}}
            <div class="col-lg-4">
                
                {{-- 🔥 CUSTOMER DETAILS --}}
                <div class="card shadow-sm border-0 rounded-4 p-4 mb-4 hover-card">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="bg-gradient-secondary p-3 rounded-circle text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-semibold">{{ $order['user']['name'] ?? 'Customer' }}</h5>
                            <span class="text-muted small">Customer Profile</span>
                        </div>
                    </div>
                    
                    <hr class="my-3">
                    
                    <div class="vstack gap-3">
                        <div class="d-flex align-items-center gap-3 p-2 rounded-3 bg-light">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#0d6efd" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-10 7L2 7"/></svg>
                            <div>
                                <small class="text-muted d-block">Email</small>
                                <span class="fw-medium">{{ $order['user']['email'] ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3 p-2 rounded-3 bg-light">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#0d6efd" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.362 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.574 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            <div>
                                <small class="text-muted d-block">Mobile</small>
                                <span class="fw-medium">{{ $order['user']['mobile'] ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 🔥 SHIPPING ADDRESS --}}
                <div class="card shadow-sm border-0 rounded-4 p-4 mb-4 hover-card">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="bg-gradient-danger p-2 rounded-3 text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                        </div>
                        <h6 class="mb-0 fw-semibold">Shipping Address</h6>
                    </div>
                    
                    @if(!empty($order['address']))
                        <div class="bg-light p-3 rounded-3">
                            <p class="fw-semibold mb-1">{{ $order['user']['name'] ?? 'Customer' }}</p>
                            <p class="mb-1">{{ $order['address']['line1'] ?? 'N/A' }}</p>
                            @if(!empty($order['address']['line2']))
                                <p class="mb-1">{{ $order['address']['line2'] }}</p>
                            @endif
                            <p class="mb-0">
                                {{ $order['address']['city'] ?? '' }}, {{ $order['address']['state'] ?? '' }} - {{ $order['address']['pincode'] ?? '' }}
                            </p>
                            <p class="mt-3 mb-0 text-muted small d-flex align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.362 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.574 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                                {{ $order['user']['mobile'] ?? 'N/A' }}
                            </p>
                        </div>
                    @else
                        <p class="text-muted">No shipping address provided.</p>
                    @endif
                </div>

                {{-- 🔥 PAYMENT DETAILS --}}
                <div class="card shadow-sm border-0 rounded-4 p-4 hover-card">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="bg-gradient-primary p-2 rounded-3 text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                        </div>
                        <h6 class="mb-0 fw-semibold">Payment Details</h6>
                    </div>
                    
                    <div class="bg-light p-3 rounded-3">
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Method</span>
                            <span class="fw-medium">
                                @if(($order['payment_method'] ?? '') == 'cod')
                                    <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2">Cash on Delivery</span>
                                @else
                                    <span class="badge bg-info bg-opacity-10 text-info px-3 py-2">{{ $order['payment_method'] ?? 'Online' }}</span>
                                @endif
                            </span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Status</span>
                            @php
                                $paymentStatus = $order['payment_status'] ?? 'Paid';
                                $badgeClass = match(strtolower($paymentStatus)) {
                                    'paid' => 'bg-success',
                                    'pending' => 'bg-warning',
                                    'failed' => 'bg-danger',
                                    default => 'bg-secondary'
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }} px-3 py-2">
                                {{ $order['payment_status'] ?? 'Paid' }}
                            </span>
                        </div>
                    </div>
                    
                    {{-- Action Buttons --}}
                    <div class="mt-4 d-grid gap-2">
                        <button class="btn btn-outline-primary rounded-pill py-2 hover-lift">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="m7 10 5 5 5-5"/><path d="M12 15V3"/></svg>
                            Download Invoice
                        </button>
                        @if($order['status'] != 'delivered' && $order['status'] != 'cancelled')
                            <button class="btn btn-outline-danger rounded-pill py-2 hover-lift">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1"><circle cx="12" cy="12" r="10"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/></svg>
                                Cancel Order
                            </button>
                        @endif
                        <button class="btn btn-outline-secondary rounded-pill py-2 hover-lift">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1"><path d="M22 6 12 13 2 6"/><path d="M2 18h20"/></svg>
                            Need Help?
                        </button>
                    </div>
                </div>
            </div>
        </div>

    {{-- 🔥 LIST PAGE --}}
    @elseif(isset($orders))

        {{-- Page Header --}}
        <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
            <div>
                <h3 class="mb-0 fw-bold">My Orders</h3>
                <p class="text-muted small mb-0">Track and manage your orders</p>
            </div>
            <div class="d-flex gap-2 mt-3 mt-sm-0">
                <select class="form-select form-select-sm rounded-pill w-auto shadow-sm border-0 bg-light">
                    <option>All Orders</option>
                    <option>Last 30 days</option>
                    <option>Last 6 months</option>
                    <option>2024</option>
                </select>
            </div>
        </div>

        @if(count($orders) == 0)
            <div class="card shadow-sm border-0 rounded-4 p-5 text-center">
                <div class="py-5">
                    <div class="bg-light d-inline-flex p-4 rounded-circle mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#6c757d" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                    </div>
                    <h4 class="fw-semibold mb-2">No orders found 😕</h4>
                    <p class="text-muted mb-4">When you place an order, it will appear here.</p>
                    <a href="{{ route('home') }}" class="btn btn-primary rounded-pill px-5 py-2 shadow-sm hover-lift">
                        Shop Now
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ms-1"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
        @else

            {{-- Modern Card Grid for Orders --}}
            <div class="row g-4">
                @foreach($orders as $order)
                    <div class="col-md-6 col-xl-4">
                        <div class="card shadow-sm border-0 rounded-4 h-100 hover-shadow transition hover-lift">
                            <div class="card-body p-4">
                                {{-- Order Header --}}
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <span class="text-muted small text-uppercase fw-semibold tracking-wide">Order ID</span>
                                        <h6 class="fw-bold mb-0">#{{ $order['id'] }}</h6>
                                        <span class="text-muted small">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                                            {{ isset($order['created_at']) ? \Carbon\Carbon::parse($order['created_at'])->format('d M, Y') : 'Today' }}
                                        </span>
                                    </div>
                                    <span class="badge px-3 py-2 rounded-pill
                                        @if($order['status'] == 'delivered') bg-success bg-opacity-10 text-success border border-success
                                        @elseif($order['status'] == 'pending') bg-warning bg-opacity-10 text-warning border border-warning
                                        @elseif($order['status'] == 'cancelled') bg-danger bg-opacity-10 text-danger border border-danger
                                        @else bg-info bg-opacity-10 text-info border border-info
                                        @endif">
                                        {{ ucfirst($order['status']) }}
                                    </span>
                                </div>
                                
                                {{-- Order Preview --}}
                                <div class="d-flex align-items-center gap-3 mb-3 p-2 bg-light rounded-3">
                                    <div class="bg-white rounded-3 p-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#0d6efd" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                                    </div>
                                    <div>
                                        <span class="fw-semibold">{{ count($order['items']) }}</span> item(s)
                                        <span class="text-muted small d-block">{{ $order['payment_method'] ?? 'Online' }}</span>
                                    </div>
                                </div>
                                
                                {{-- Total --}}
                                <div class="mb-4 p-3 bg-light rounded-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-muted small text-uppercase fw-semibold">Total Amount</span>
                                        <span class="fw-bold text-success fs-4">₹{{ number_format($order['total'], 2) }}</span>
                                    </div>
                                </div>
                                
                                {{-- Action Button --}}
                                <a href="{{ route('orders.show', $order['id']) }}"
                                   class="btn btn-primary rounded-pill w-100 py-2 hover-lift">
                                    View Order Details
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ms-1"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            {{-- Pagination --}}
            @if(count($orders) > 6)
                <div class="d-flex justify-content-center mt-5">
                    <nav>
                        <ul class="pagination">
                            <li class="page-item disabled">
                                <a class="page-link rounded-pill px-3 mx-1" href="#">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                                </a>
                            </li>
                            <li class="page-item active"><a class="page-link rounded-pill px-3 mx-1" href="#">1</a></li>
                            <li class="page-item"><a class="page-link rounded-pill px-3 mx-1" href="#">2</a></li>
                            <li class="page-item"><a class="page-link rounded-pill px-3 mx-1" href="#">3</a></li>
                            <li class="page-item">
                                <a class="page-link rounded-pill px-3 mx-1" href="#">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            @endif

        @endif

    @endif

</div>

@endsection

@push('styles')
<style>
    /* Smooth Transitions */
    .hover-lift {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .hover-lift:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.1) !important;
    }
    
    .hover-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .hover-card:hover {
        box-shadow: 0 12px 30px rgba(0,0,0,0.1) !important;
    }
    
    .hover-shadow {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .hover-shadow:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.12) !important;
    }
    
    /* Gradient Backgrounds */
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .bg-gradient-success {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    }
    
    .bg-gradient-secondary {
        background: linear-gradient(135deg, #868f96 0%, #596164 100%);
    }
    
    .bg-gradient-danger {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    }
    
    /* Progress Bar Animation */
    .progress-bar {
        transition: width 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        background: linear-gradient(90deg, #11998e 0%, #38ef7d 100%);
    }
    
    /* Background Opacity */
    .bg-opacity-10 {
        --bs-bg-opacity: 0.1;
    }
    
    /* Cards */
    .card {
        border: 1px solid rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }
    
    /* Buttons */
    .btn {
        font-weight: 500;
        transition: all 0.2s ease;
        position: relative;
        overflow: hidden;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
    }
    
    .btn-primary:hover {
        background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    }
    
    .btn-outline-primary {
        border-color: #667eea;
        color: #667eea;
    }
    
    .btn-outline-primary:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-color: transparent;
    }
    
    /* Form Elements */
    .form-select {
        background-color: #f8f9fa;
        border: 1px solid #e9ecef;
        padding: 0.6rem 2.2rem 0.6rem 1.2rem;
        font-weight: 500;
        font-size: 0.875rem;
        cursor: pointer;
    }
    
    .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    
    /* Typography */
    .tracking-wide {
        letter-spacing: 0.5px;
    }
    
    /* Item Row Hover */
    .item-row {
        transition: background-color 0.2s ease;
    }
    
    .item-row:hover {
        background-color: #f8f9fa;
        border-radius: 12px;
        padding-left: 8px;
        padding-right: 8px;
    }
    
    /* Badges */
    .badge {
        font-weight: 500;
        letter-spacing: 0.3px;
    }
    
    /* Pagination */
    .page-link {
        border: none;
        color: #6c757d;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    
    .page-link:hover {
        background-color: #f8f9fa;
        color: #667eea;
        transform: translateY(-2px);
    }
    
    .page-item.active .page-link {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .container {
            padding-left: 16px;
            padding-right: 16px;
        }
        
        .card-body {
            padding: 1.25rem;
        }
        
        h3 {
            font-size: 1.5rem;
        }
    }
    
    /* Smooth animations */
    * {
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }
    
    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    
    ::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    ::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 10px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
    }
</style>
@endpush