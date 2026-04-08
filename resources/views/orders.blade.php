@extends('layouts.app')

@section('content')

<div class="container py-5">

    {{-- 🔥 DETAIL PAGE --}}
    @if(isset($order))

        <h3 class="mb-4 fw-bold">Order Details</h3>

        {{-- Order Summary --}}
        <div class="card shadow-sm border-0 rounded-4 p-4 mb-4">
            <p><strong>Order ID:</strong> #{{ $order['id'] }}</p>

            <p>
                <strong>Total:</strong> 
                <span class="text-success fw-bold">₹{{ $order['total'] }}</span>
            </p>

            <p>
                <strong>Status:</strong> 
                <span class="badge 
                    @if($order['status'] == 'delivered') bg-success
                    @elseif($order['status'] == 'pending') bg-warning
                    @else bg-info
                    @endif">
                    {{ ucfirst($order['status']) }}
                </span>
            </p>
        </div>

        {{-- 🔥 CUSTOMER DETAILS --}}
        <div class="card shadow-sm border-0 rounded-4 p-4 mb-4">
            <h5 class="mb-3">Customer Details</h5>

            <div class="row">
                <div class="col-md-4">
                    <p class="mb-1 text-muted">Name</p>
                    <p class="fw-semibold">{{ $order['user']['name'] ?? 'N/A' }}</p>
                </div>

                <div class="col-md-4">
                    <p class="mb-1 text-muted">Mobile</p>
                    <p class="fw-semibold">{{ $order['user']['mobile'] ?? 'N/A' }}</p>
                </div>

                <div class="col-md-4">
                    <p class="mb-1 text-muted">Email</p>
                    <p class="fw-semibold">{{ $order['user']['email'] ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        {{-- 🔥 SHIPPING ADDRESS (NEW) --}}
        <div class="card shadow-sm border-0 rounded-4 p-4 mb-4">
            <h5 class="mb-3">Shipping Address</h5>

            <p class="fw-semibold mb-1">
                {{ $order['address']['line1'] ?? 'N/A' }}
            </p>

            <p class="text-muted mb-0">
                {{ $order['address']['city'] ?? '' }},
                {{ $order['address']['state'] ?? '' }} - 
                {{ $order['address']['pincode'] ?? '' }}
            </p>
        </div>

        {{-- 🔥 ITEMS --}}
        <h5 class="mb-3">Items</h5>

        <div class="card shadow-sm border-0 rounded-4">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Item</th>
                            <th width="120">Qty</th>
                            <th width="150">Price</th>
                            <th width="150">Subtotal</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($order['items'] as $item)
                        <tr>
                            <td>
                                {{ $item['product']['name'] ?? 'Item #' . ($item['item_id'] ?? '') }}
                            </td>
                            <td>{{ $item['qty'] }}</td>
                            <td>₹{{ $item['price'] }}</td>
                            <td class="fw-bold">
                                ₹{{ $item['qty'] * $item['price'] }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    {{-- 🔥 LIST PAGE --}}
    @elseif(isset($orders))

        <h3 class="mb-4 fw-bold">My Orders</h3>

        @if(count($orders) == 0)
            <div class="text-center py-5">
                <h5>No orders found 😕</h5>
                <a href="{{ route('home') }}" class="btn btn-primary mt-3">
                    Shop Now
                </a>
            </div>
        @else

        <div class="card shadow-sm border-0 rounded-4">
            <div class="table-responsive">

                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th width="120">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td class="fw-semibold">#{{ $order['id'] }}</td>

                            <td class="text-success fw-bold">
                                ₹{{ $order['total'] }}
                            </td>

                            <td>
                                <span class="badge 
                                    @if($order['status'] == 'delivered') bg-success
                                    @elseif($order['status'] == 'pending') bg-warning
                                    @else bg-info
                                    @endif">
                                    {{ ucfirst($order['status']) }}
                                </span>
                            </td>

                            <td>
                                <a href="{{ route('orders.show', $order['id']) }}"
                                   class="btn btn-sm btn-dark rounded-pill px-3">
                                    View
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>

            </div>
        </div>

        @endif

    @endif

</div>

@endsection