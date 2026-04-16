{{-- resources/views/cart/index.blade.php --}}
@extends('layouts.app')

@php
    $s3Base = "https://pharma-catalog-assets.s3.us-east-1.amazonaws.com";
    
    // Check if cart requires prescription
    $requiresPrescription = false;
    foreach($cart['items'] as $item) {
        if(isset($item['need_prescription']) && $item['need_prescription'] == 1) {
            $requiresPrescription = true;
            break;
        }
    }
@endphp

@section('title', 'My Cart')

@section('content')
<div class="container py-4 py-md-5">

    <h1 class="fw-bold mb-4 d-flex align-items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
        Shopping Cart
        <span class="cart-count-badge ms-2">{{ count($cart['items'] ?? []) }}</span>
    </h1>

    @if(empty($cart['items']) || count($cart['items']) == 0)
        <div id="empty-cart-message" class="text-center py-5 bg-white rounded-4 shadow-sm">
            <div class="bg-light d-inline-flex p-4 rounded-circle mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#6c757d" stroke-width="1.5"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
            </div>
            <h4 class="fw-semibold mb-2">Your cart is empty</h4>
            <p class="text-muted mb-4">Looks like you haven't added anything yet</p>
            <a href="{{ route('home') }}" class="btn btn-primary rounded-pill px-5 py-2 shadow-sm hover-lift">
                Continue Shopping
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
            </a>
        </div>
    @else

    <div class="row g-4" id="cart-content-wrapper">

        {{-- LEFT: Cart Items --}}
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden hover-card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0" id="cart-table">
                            <thead class="bg-light">
                                <tr class="border-0">
                                    <th class="ps-4 py-3 border-0">Product</th>
                                    <th class="py-3 border-0">Price</th>
                                    <th class="py-3 border-0">Qty</th>
                                    <th class="py-3 border-0">Total</th>
                                    <th class="pe-4 py-3 border-0"></th>
                                </tr>
                            </thead>
                            <tbody id="cart-items-body">
                                @foreach($cart['items'] as $item)
                                <tr class="item-row cart-item-row" data-id="{{ $item['id'] }}" data-price="{{ $item['price'] }}">
                                    {{-- Product with Image --}}
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center gap-3">

    {{-- IMAGE --}}
@php
$image = asset('images/default-medicine.png');

if (!empty($item['main_image'])) {

    if (filter_var($item['main_image'], FILTER_VALIDATE_URL)) {

        // External URL
        $image = $item['main_image'];

    } else {

        try {
            // S3 image
            $image = Storage::disk('s3')->url($item['main_image']);
        } catch (\Exception $e) {
            $image = asset('images/default-medicine.png');
        }
    }
}
@endphp

    <img src="{{ $image }}"
         class="rounded-3 border"
         style="width: 64px; height: 64px; object-fit: cover;"
         alt="{{ $item['name'] }}"
         onerror="this.onerror=null;this.src='{{ asset('images/default-medicine.png') }}';">

    {{-- TEXT --}}
    <div>
        <strong class="fw-semibold d-block">{{ $item['name'] }}</strong>

        @if(!empty($item['generic_name']))
            <small class="text-muted d-block">{{ $item['generic_name'] }}</small>
        @endif

        @if(isset($item['need_prescription']) && $item['need_prescription'] == 1)
            <span class="badge bg-warning text-dark mt-1">
                Rx Required
            </span>
        @endif
    </div>

</div>
</td> 

                                    {{-- Price --}}
                                    <td class="text-success fw-semibold item-price" data-id="{{ $item['id'] }}">
                                        ₹{{ number_format($item['price'], 2) }}
                                    </td>

                                    {{-- Quantity with Flipkart Style Controls --}}
                                    <td class="qty-cell">
                                        <div class="quantity-control-wrapper" data-id="{{ $item['id'] }}">
                                            <button type="button" class="qty-btn qty-decrease" data-id="{{ $item['id'] }}" {{ $item['qty'] <= 1 ? 'disabled' : '' }}>−</button>
                                            <span class="qty-value" data-id="{{ $item['id'] }}">{{ $item['qty'] }}</span>
                                            <button type="button" class="qty-btn qty-increase" data-id="{{ $item['id'] }}">+</button>
                                        </div>
                                    </td>

                                    {{-- Total --}}
                                    <td class="fw-bold text-dark item-total" data-id="{{ $item['id'] }}">
                                        ₹{{ number_format($item['total_price'], 2) }}
                                    </td>

                                    {{-- Remove --}}
                                    <td class="pe-4">
                                        <button type="button" 
                                           class="btn btn-sm btn-outline-danger rounded-circle p-1 remove-item-btn" 
                                           style="width: 32px; height: 32px;"
                                           data-id="{{ $item['id'] }}"
                                           title="Remove">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- Cart Footer with Continue Shopping --}}
                <div class="card-footer bg-white border-0 p-4">
                    <a href="{{ route('home') }}" class="text-decoration-none fw-semibold hover-lift d-inline-flex align-items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                        Continue Shopping
                    </a>
                </div>
            </div>
        </div>

        {{-- RIGHT: Order Summary & Checkout --}}
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 rounded-4 p-4 hover-card mb-4">
                <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg>
                    Order Summary
                </h5>
                
                {{-- PRESCRIPTION WARNING --}}
                @if($requiresPrescription)
                <div class="alert alert-warning rounded-3 d-flex align-items-start gap-2 mb-3 p-3" role="alert">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="flex-shrink-0 mt-0"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="M12 8v4"/><path d="M12 16h.01"/></svg>
                    <div>
                        <strong>Prescription Required</strong><br>
                        <small>This order contains prescription items. Please upload a valid prescription to continue.</small>
                    </div>
                </div>
                @endif
                
                <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                    <span class="text-muted">Subtotal</span>
                    <strong id="summary-subtotal" class="text-success fs-5">₹{{ number_format($cart['summary']['total'], 2) }}</strong>
                </div>
                
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Shipping</span>
                    <span class="text-success fw-semibold">Free</span>
                </div>
                
                <div class="d-flex justify-content-between mb-4 pb-2 border-bottom">
                    <span class="text-muted">Tax (GST)</span>
                    <span class="text-muted">Included</span>
                </div>
                
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <span class="fw-bold fs-5">Total</span>
                    <span id="summary-total" class="fw-bold text-success fs-3">₹{{ number_format($cart['summary']['total'], 2) }}</span>
                </div>

                <hr class="my-2">

                {{-- ADDRESS DROPDOWN (API BASED) --}}
                <div class="mb-4">
                    <label class="fw-semibold mb-2 d-flex align-items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                        Select Address
                    </label>
                    <select id="address_id" class="form-select rounded-pill shadow-sm bg-light border-0 py-2 mb-2">
                        <option value="">Loading addresses...</option>
                    </select>
                    <a href="{{ url('/addresses') }}" class="btn btn-outline-primary rounded-pill w-100 py-2 hover-lift">
                        + Add / Manage Address
                    </a>
                </div>

                {{-- CONDITIONAL BUTTONS BASED ON PRESCRIPTION REQUIREMENT --}}
                @if($requiresPrescription)
                    {{-- Upload Prescription Button (replaces COD) --}}
                    <button id="upload-prescription-btn" class="btn btn-primary rounded-pill w-100 py-2 fw-semibold hover-lift mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" x2="12" y1="3" y2="15"/></svg>
                        Upload Prescription
                    </button>
                    
                    {{-- Disabled Pay Online Button --}}
                    <button id="pay-btn" class="btn btn-secondary rounded-pill w-100 py-2 fw-semibold" disabled style="opacity: 0.5; cursor: not-allowed;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                        Pay Online (Upload Prescription First)
                    </button>
                @else
                    {{-- Normal COD Form --}}
                    <form action="{{ route('orders.place') }}" method="POST" onsubmit="return setAddress()" class="mb-3">
                        @csrf
                        <input type="hidden" name="payment_mode" value="cod">
                        <input type="hidden" name="address_id" id="cod_address_id">
                        <button type="submit" class="btn btn-warning rounded-pill w-100 py-2 fw-semibold hover-lift">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                            Cash on Delivery
                        </button>
                    </form>

                    {{-- Pay Online Button --}}
                    <button id="pay-btn" class="btn btn-success rounded-pill w-100 py-2 fw-semibold hover-lift">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                        Pay Online
                    </button>
                @endif
            </div>

            {{-- Trust Badge --}}
            <div class="card shadow-sm border-0 rounded-4 p-3 bg-light text-center">
                <div class="d-flex justify-content-center gap-4">
                    <div class="text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#28a745" stroke-width="1.5"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg>
                        <small class="d-block text-muted">Secure</small>
                    </div>
                    <div class="text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#28a745" stroke-width="1.5"><path d="M20 12V8H6V4H4v16h16v-4"/><path d="m12 12 4 4-4 4"/><path d="M16 12v8"/></svg>
                        <small class="d-block text-muted">Free Shipping</small>
                    </div>
                    <div class="text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#28a745" stroke-width="1.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        <small class="d-block text-muted">Genuine</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @endif

</div>

{{-- Prescription Upload Modal (Hidden by default) --}}
<div class="modal fade" id="prescriptionModal" tabindex="-1" aria-labelledby="prescriptionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-0 px-4 pt-4">
                <h5 class="modal-title fw-bold" id="prescriptionModalLabel">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" x2="12" y1="3" y2="15"/></svg>
                    Upload Prescription
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 pb-4">
                <form id="prescription-upload-form" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Select Prescription File</label>
                        <input type="file" name="prescription" class="form-control rounded-3" accept=".jpg,.jpeg,.png,.pdf" required>
                        <small class="text-muted">Supported formats: JPG, PNG, PDF (Max 5MB)</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Notes (Optional)</label>
                        <textarea name="notes" class="form-control rounded-3" rows="2" placeholder="Any additional information..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary rounded-pill w-100 py-2 fw-semibold">
                        <span class="spinner-border spinner-border-sm d-none me-1" role="status" aria-hidden="true"></span>
                        Submit Prescription
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>
(function() {
    'use strict';
    
    // Configuration
    const API_BASE = "{{ env('API_BASE_URL') }}";
    const token = document.querySelector('meta[name="api-token"]')?.getAttribute('content');
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
    const UPDATE_URL = "{{ route('cart.update') }}";
    const REMOVE_BASE_URL = "{{ url('cart/remove') }}";
    const REQUIRES_PRESCRIPTION = {{ $requiresPrescription ? 'true' : 'false' }};
    
    // DOM Elements
    const loader = document.getElementById('cart-ajax-loader');
    const cartBody = document.getElementById('cart-items-body');
    const summarySubtotal = document.getElementById('summary-subtotal');
    const summaryTotal = document.getElementById('summary-total');
    const cartCountBadge = document.querySelector('.cart-count-badge');
    const emptyCartMessage = document.getElementById('empty-cart-message');
    const cartContentWrapper = document.getElementById('cart-content-wrapper');
    
    // Helper Functions
    function showLoader() {
        if (loader) loader.style.display = 'block';
    }
    
    function hideLoader() {
        if (loader) loader.style.display = 'none';
    }
    
    function formatIndianRupee(amount) {
        return '₹' + parseFloat(amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
    }
    
    // Disable/enable quantity buttons for a specific row
    function setButtonsState(itemId, disabled) {
        const wrapper = document.querySelector(`.quantity-control-wrapper[data-id="${itemId}"]`);
        if (wrapper) {
            const buttons = wrapper.querySelectorAll('.qty-btn');
            buttons.forEach(btn => btn.disabled = disabled);
        }
        // Also disable remove button
        const removeBtn = document.querySelector(`.remove-item-btn[data-id="${itemId}"]`);
        if (removeBtn) removeBtn.disabled = disabled;
    }
    
    // Update cart totals
    function updateCartSummary(subtotal) {
        if (summarySubtotal) {
            summarySubtotal.textContent = '₹' + parseFloat(subtotal).toFixed(2);
        }
        if (summaryTotal) {
            summaryTotal.textContent = '₹' + parseFloat(subtotal).toFixed(2);
        }
    }
    
    // Update cart count badge
    function updateCartCount() {
        const visibleRows = document.querySelectorAll('.cart-item-row:not(.removing)').length;
        if (cartCountBadge) {
            cartCountBadge.textContent = visibleRows;
        }
        
        // Update navbar cart count if exists
        const navbarCartCount = document.querySelector('.cart-count, #cart-count, .navbar-cart-count');
        if (navbarCartCount) {
            navbarCartCount.textContent = visibleRows;
        }
    }
    
    // Check if cart is empty and show/hide appropriate sections
    function checkEmptyState() {
        const visibleRows = document.querySelectorAll('.cart-item-row:not(.removing)').length;
        
        if (visibleRows === 0) {
            if (cartContentWrapper) cartContentWrapper.style.display = 'none';
            if (emptyCartMessage) emptyCartMessage.style.display = 'block';
        } else {
            if (cartContentWrapper) cartContentWrapper.style.display = 'flex';
            if (emptyCartMessage) emptyCartMessage.style.display = 'none';
        }
    }
    
    // AJAX Update Quantity
    async function updateQuantity(cartId, newQty) {
        // showLoader();
        setButtonsState(cartId, true);
        
        const formData = new FormData();
        formData.append('cart_id', cartId);
        formData.append('qty', newQty);
        // formData.append('_token', csrf);
        
        try {
            const response = await fetch(UPDATE_URL, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData
            });
            
const text = await response.text();
console.log("SERVER RESPONSE:", text);

let data;
try {
    data = JSON.parse(text);
} catch {
    alert("Server error - check console");
    return;
}            
            // Handle both response formats: {status: true} or {success: true}
            const isSuccess = data.status === true || data.success === true;
            
            if (isSuccess) {
                // Update quantity display
                const qtySpan = document.querySelector(`.qty-value[data-id="${cartId}"]`);
                if (qtySpan) {
                    qtySpan.textContent = newQty;
                }
                
                // Update decrease button state
                const decreaseBtn = document.querySelector(`.qty-decrease[data-id="${cartId}"]`);
                if (decreaseBtn) {
                    decreaseBtn.disabled = newQty <= 1;
                }
                
                // Update item total
                const row = document.querySelector(`.cart-item-row[data-id="${cartId}"]`);
                if (row) {
                    const price = parseFloat(row.dataset.price);
                    const totalElement = row.querySelector('.item-total');
                    if (totalElement) {
                        totalElement.textContent = formatIndianRupee(price * newQty);
                    }
                }
                
                // Calculate and update new subtotal
                let newSubtotal = 0;
                document.querySelectorAll('.cart-item-row').forEach(r => {
                    const rowPrice = parseFloat(r.dataset.price);
                    const rowQty = parseInt(r.querySelector('.qty-value')?.textContent || 0);
                    newSubtotal += rowPrice * rowQty;
                });
                
                updateCartSummary(newSubtotal);
                
            } else {
                alert(data.message || 'Failed to update quantity');
                // Revert quantity display if needed
                const qtySpan = document.querySelector(`.qty-value[data-id="${cartId}"]`);
                if (qtySpan) {
                    const currentQty = parseInt(qtySpan.textContent);
                    const decreaseBtn = document.querySelector(`.qty-decrease[data-id="${cartId}"]`);
                    if (decreaseBtn) {
                        decreaseBtn.disabled = currentQty <= 1;
                    }
                }
            }
        } catch (error) {
            console.error('Error updating quantity:', error);
            alert('Something went wrong. Please try again.');
        } finally {
            // hideLoader();
            setButtonsState(cartId, false);
        }
    }
    
    // AJAX Remove Item
    async function removeCartItem(cartId) {
        showLoader();
        setButtonsState(cartId, true);
        
        try {
            const response = await fetch(`${REMOVE_BASE_URL}/${cartId}`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            
            const data = await response.json();
            
            const isSuccess = data.status === true || data.success === true;
            
            if (isSuccess) {
                const row = document.querySelector(`.cart-item-row[data-id="${cartId}"]`);
                if (row) {
                    row.classList.add('removing');
                    row.style.transition = 'opacity 0.3s ease';
                    row.style.opacity = '0';
                    
                    setTimeout(() => {
                        row.remove();
                        
                        let newSubtotal = 0;
                        document.querySelectorAll('.cart-item-row:not(.removing)').forEach(r => {
                            const rowPrice = parseFloat(r.dataset.price);
                            const rowQty = parseInt(r.querySelector('.qty-value')?.textContent || 0);
                            newSubtotal += rowPrice * rowQty;
                        });
                        
                        updateCartSummary(newSubtotal);
                        updateCartCount();
                        checkEmptyState();
                        
                        // Reload page if prescription requirement changed
                        if (REQUIRES_PRESCRIPTION) {
                            setTimeout(() => {
                                window.location.reload();
                            }, 500);
                        }
                    }, 300);
                }
            } else {
                alert(data.message || 'Failed to remove item');
                setButtonsState(cartId, false);
            }
        } catch (error) {
            console.error('Error removing item:', error);
            alert('Something went wrong. Please try again.');
            setButtonsState(cartId, false);
        } finally {
            hideLoader();
        }
    }
    
    // Event Delegation for all cart interactions
    document.addEventListener('click', function(e) {
        // Quantity Increase
        if (e.target.closest('.qty-increase')) {
            e.preventDefault();
            const btn = e.target.closest('.qty-increase');
            const cartId = btn.dataset.id;
            const qtySpan = document.querySelector(`.qty-value[data-id="${cartId}"]`);
            
            if (qtySpan) {
                const newQty = parseInt(qtySpan.textContent) + 1;
                if (newQty <= 10) { // Optional max limit
                    updateQuantity(cartId, newQty);
                }
            }
        }
        
        // Quantity Decrease
        if (e.target.closest('.qty-decrease')) {
            e.preventDefault();
            const btn = e.target.closest('.qty-decrease');
            const cartId = btn.dataset.id;
            const qtySpan = document.querySelector(`.qty-value[data-id="${cartId}"]`);
            
            if (qtySpan) {
                const newQty = parseInt(qtySpan.textContent) - 1;
                if (newQty >= 1) {
                    updateQuantity(cartId, newQty);
                }
            }
        }
        
        // Remove Item
        if (e.target.closest('.remove-item-btn')) {
            e.preventDefault();
            const btn = e.target.closest('.remove-item-btn');
            const cartId = btn.dataset.id;
            removeCartItem(cartId);
        }
    });
    
    // ===============================
    // PRESCRIPTION UPLOAD HANDLER
    // ===============================
    const uploadBtn = document.getElementById('upload-prescription-btn');
    const prescriptionModal = document.getElementById('prescriptionModal');
    
    if (uploadBtn && prescriptionModal) {
        uploadBtn.addEventListener('click', function() {
            const addressId = document.getElementById('address_id').value;
            
            if (!addressId || addressId === "" || addressId === "null") {
                alert("⚠️ Please select a valid address first");
                return;
            }
            
            const modal = new bootstrap.Modal(prescriptionModal);
            modal.show();
        });
        
        // Handle prescription form submission
        const prescriptionForm = document.getElementById('prescription-upload-form');
        if (prescriptionForm) {
            prescriptionForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const addressId = document.getElementById('address_id').value;
                const fileInput = this.querySelector('input[type="file"]');
                const notesInput = this.querySelector('textarea[name="notes"]');
                const submitBtn = this.querySelector('button[type="submit"]');
                const spinner = submitBtn.querySelector('.spinner-border');
                
                if (!fileInput.files[0]) {
                    alert('Please select a prescription file');
                    return;
                }
                
                // Show loading state
                submitBtn.disabled = true;
                spinner.classList.remove('d-none');
                
                const formData = new FormData();
                formData.append('prescription', fileInput.files[0]);
                formData.append('notes', notesInput.value);
                formData.append('address_id', addressId);
                formData.append('_token', csrf);
                
              try {
    const response = await fetch(API_BASE + '/prescription/upload', {
        method: 'POST',
        headers: {
            'Authorization': 'Bearer ' + token,
            'Accept': 'application/json'
        },
        body: formData
    });

    const data = await response.json();

 if (data.status || data.success) {

    let prescriptionId = data.data.id; // 🔥 IMPORTANT

    // Save ID
    localStorage.setItem('prescription_id', prescriptionId);

    bootstrap.Modal.getInstance(prescriptionModal).hide();

    alert("Prescription uploaded successfully");

    // Enable Pay button
    let payBtn = document.getElementById('pay-btn');
    if (payBtn) {
        payBtn.disabled = false;
        payBtn.style.opacity = 1;
        payBtn.style.cursor = 'pointer';
    }

    } else {
        alert(data.message || 'Upload failed');
    }

} catch (error) {
    console.error('Upload error:', error);
    alert('Something went wrong. Please try again.');
}
});
}
    }
    
    // ===============================
    // LOAD ADDRESSES (KEEP EXACTLY SAME)
    // ===============================
    function loadAddresses() {
        const dropdown = document.getElementById("address_id");
        if (!dropdown) return;
    
        fetch(API_BASE + "/addresses", {
            headers: {
                "Authorization": "Bearer " + token,
                "Accept": "application/json"
            }
        })
        .then(res => res.json())
        .then(res => {
            dropdown.innerHTML = '<option value="">Select Address</option>';
            
            if (!res.data || res.data.length === 0) {
                dropdown.innerHTML = '<option value="">No Address Found</option>';
                return;
            }
            
            res.data.forEach(addr => {
                let option = document.createElement("option");
                option.value = addr.id;
                option.text = addr.name + " - " + addr.city;
                if (addr.is_default) {
                    option.selected = true;
                    localStorage.setItem("selected_address_id", addr.id);
                }
                dropdown.appendChild(option);
            });
            
            let saved = localStorage.getItem("selected_address_id");
            if (saved && document.querySelector(`option[value="${saved}"]`)) {
                dropdown.value = saved;
            }
        })
        .catch(err => console.error("Address load error:", err));
    }
    
    // Save selected address
    document.getElementById("address_id")?.addEventListener("change", function() {
        localStorage.setItem("selected_address_id", this.value);
    });
    
    // COD form handler
    window.setAddress = function() {
        let addr = document.getElementById('address_id').value;
        if (!addr) {
            alert("⚠️ Please select an address");
            return false;
        }
        document.getElementById('cod_address_id').value = addr;
        return true;
    };
    
    // ===============================
    // ONLINE PAYMENT (RAZORPAY) - KEEP EXACTLY SAME
    // ===============================
    document.getElementById('pay-btn')?.addEventListener('click', function() {
        // Don't proceed if button is disabled
        if (this.disabled) {
            alert("⚠️ Please upload prescription first");
            return;
        }
        
        let addressId = document.getElementById('address_id').value;
        
        if (!addressId || addressId === "" || addressId === "null") {
            alert("⚠️ Please select a valid address");
            return;
        }
        
        // Get current total from summary
        const totalText = document.getElementById('summary-total')?.textContent || '0';
        const amount = parseFloat(totalText.replace(/[^0-9.]/g, ''));
        
        fetch('/payment/create', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf
            },
            body: JSON.stringify({ amount: amount })
        })
        .then(res => res.json())
        .then(data => {
            if (!data.success) {
                alert("❌ Payment initialization failed");
                return;
            }
            
            var options = {
                key: data.key,
                amount: data.amount,
                order_id: data.order_id,
                name: "Pharma ERP",
                description: "Order Payment",
                handler: function(response) {
                    let formData = new FormData();
                    formData.append('address_id', addressId);
                    formData.append('payment_id', response.razorpay_payment_id);
                    formData.append('payment_mode', 'razorpay');
let prescriptionId = localStorage.getItem('prescription_id');

if (!prescriptionId || prescriptionId === "null") {
    alert("⚠️ Please upload prescription again");
    return;
}

formData.append('prescription_id', prescriptionId);
                    fetch(API_BASE + '/orders/place', {
    method: 'POST',
    headers: {
        'Authorization': 'Bearer ' + token,
        'Accept': 'application/json'
    },
    body: formData
})
                    .then(res => res.json())
                    .then(data => {
                        if (data.status) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Order Placed!',
                                text: 'Your order has been placed successfully 🎉',
                                timer: 2000,
                                showConfirmButton: false
                            });
                            
                            // cart count reset
                            const navbarCart = document.querySelector('#cart-count, .cart-count');
                            if (navbarCart) navbarCart.innerText = 0;
                            
                            setTimeout(() => {
                                window.location.href = "/orders";
                            }, 2000);
                        } else {
                            alert("❌ Order failed: " + data.message);
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert("Server error while placing order");
                    });
                },
                modal: {
                    ondismiss: function() {
                        alert("⚠️ Payment cancelled");
                    }
                }
            };
            var rzp = new Razorpay(options);
            rzp.open();
        })
        .catch(err => {
            console.error(err);
            alert("Server error during payment init");
        });
    });
    
    // Initialize
    loadAddresses();
    
    // Hide empty message initially if items exist
    @if(!empty($cart['items']) && count($cart['items']) > 0)
        if (emptyCartMessage) emptyCartMessage.style.display = 'none';
    @endif
    
})();
</script>
@endpush

@push('styles')
<style>
    /* Quantity Controls - Flipkart Style */
    .quantity-control-wrapper {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: #f5f5f5;
        padding: 4px 8px;
        border-radius: 24px;
    }
    
    .qty-btn {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        border: 1px solid #ddd;
        background: white;
        font-size: 18px;
        font-weight: bold;
        color: #333;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        padding: 0;
        line-height: 1;
    }
    
    .qty-btn:hover:not(:disabled) {
        background: #0d6efd;
        color: white;
        border-color: #0d6efd;
    }
    
    .qty-btn:disabled {
        opacity: 0.4;
        cursor: not-allowed;
        background: #e9ecef;
    }
    
    .qty-value {
        min-width: 24px;
        text-align: center;
        font-weight: 600;
        font-size: 15px;
    }
    
    .qty-cell {
        vertical-align: middle;
    }
    
    /* Cart Count Badge */
    .cart-count-badge {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2px 10px;
        border-radius: 50px;
        font-size: 16px;
        font-weight: 600;
    }
    
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
    
    .item-row {
        transition: background-color 0.2s ease;
    }
    .item-row:hover {
        background-color: #f8f9fa;
    }
    
    .table > :not(caption) > * > * {
        padding: 1rem 0.5rem;
    }
    
    .btn-outline-danger:hover svg {
        stroke: white;
    }
    
    .btn-outline-danger:hover {
        background-color: #dc3545;
        border-color: #dc3545;
    }
    
    /* Remove button */
    .remove-item-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        pointer-events: none;
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
    
    @media (max-width: 768px) {
        .table-responsive {
            border-radius: 1rem;
        }
        .table > :not(caption) > * > * {
            padding: 0.75rem 0.5rem;
        }
        .d-flex.align-items-center.gap-3 {
            flex-direction: column;
            align-items: flex-start !important;
        }
        
        .quantity-control-wrapper {
            gap: 6px;
            padding: 3px 6px;
        }
        
        .qty-btn {
            width: 24px;
            height: 24px;
            font-size: 16px;
        }
    }
</style>
@endpush