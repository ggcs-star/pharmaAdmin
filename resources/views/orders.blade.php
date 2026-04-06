@extends('layouts.app')

@section('content')

<div class="container py-5">
    <h2 class="mb-4">My Orders</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if(empty($orders))
        <p>No orders found</p>
    @else

        <table class="table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td>#{{ $order['id'] }}</td>
                    <td>₹{{ $order['total'] }}</td>
                    <td>{{ $order['status'] }}</td>
                    <td>
                        <a href="{{ route('orders.show', $order['id']) }}" class="btn btn-sm btn-primary">
                            View
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    @endif
</div>

@endsection