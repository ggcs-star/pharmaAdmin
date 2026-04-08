@extends('layouts.app')

@section('title', 'My Cart')

@section('content')
<div class="container py-5">

    <h1 class="fw-bold mb-4">Shopping Cart</h1>

    @if(empty($cart['items']) || count($cart['items']) == 0)
        <div class="text-center py-5 bg-white rounded shadow-sm">
            <h4>Your cart is empty</h4>
            <a href="{{ route('home') }}" class="btn btn-success mt-3">
                Continue Shopping
            </a>
        </div>
    @else

    <div class="row">

        {{-- LEFT --}}
        <div class="col-lg-8 mb-4">
            <div class="bg-white rounded shadow-sm">

                <table class="table align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th>Total</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($cart['items'] as $item)
                        <tr>
                            <td><strong>{{ $item['name'] }}</strong></td>
                            <td>₹{{ number_format($item['total_price'], 2) }}</td>

                            <td>
                                <form action="{{ route('cart.update') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="cart_id" value="{{ $item['id'] }}">
                                    <input type="number" name="qty" value="{{ $item['qty'] }}" min="1" class="form-control" style="width:80px;">
                                </form>
                            </td>

                            <td class="text-success fw-bold">
                                ₹{{ number_format($item['total_price'], 2) }}
                            </td>

                            <td>
                                <a href="{{ route('cart.remove', $item['id']) }}"
                                   class="btn btn-sm btn-danger">
                                   Remove
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>

        {{-- RIGHT --}}
        <div class="col-lg-4">
            <div class="bg-white rounded shadow-sm p-4">

                <h5 class="fw-bold mb-3">Order Summary</h5>

                <div class="d-flex justify-content-between">
                    <span>Total</span>
                    <strong class="text-success">
                        ₹{{ number_format($cart['summary']['total'], 2) }}
                    </strong>
                </div>

                <hr>

                {{-- 🔥 ADDRESS DROPDOWN (API BASED) --}}
                <div class="mb-3">
                    <label class="fw-bold">Select Address</label>

                    <select id="address_id" class="form-control mb-2">
                        <option value="">Loading...</option>
                    </select>

                    <a href="{{ url('/addresses') }}" class="btn btn-outline-primary w-100">
                        + Add / Manage Address
                    </a>
                </div>

                {{-- COD --}}
                <form action="{{ route('orders.place') }}" method="POST" onsubmit="return setAddress()">
                    @csrf
                    <input type="hidden" name="payment_mode" value="cod">
                    <input type="hidden" name="address_id" id="cod_address_id">

                    <button class="btn btn-warning w-100 mb-2">
                        💵 Cash on Delivery
                    </button>
                </form>

                {{-- ONLINE --}}
                <button id="pay-btn" class="btn btn-success w-100">
                    💳 Pay Online
                </button>

            </div>
        </div>

    </div>

    @endif

</div>
@endsection

@push('scripts')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>

const API_BASE = "{{ env('API_BASE_URL') }}";
const token = document.querySelector('meta[name="api-token"]')?.getAttribute('content');
const csrf = document.querySelector('meta[name="csrf-token"]').content;

// ===============================
// LOAD ADDRESSES
// ===============================
function loadAddresses() {

    fetch(API_BASE + "/addresses", {
        headers: {
            "Authorization": "Bearer " + token,
            "Accept": "application/json"
        }
    })
    .then(res => res.json())
    .then(res => {

        let dropdown = document.getElementById("address_id");

        if (!dropdown) return;

        dropdown.innerHTML = '<option value="">Select Address</option>';

        if (!res.data || res.data.length === 0) {
            dropdown.innerHTML = '<option value="">No Address Found</option>';
            return;
        }

        res.data.forEach(addr => {

            let option = document.createElement("option");
            option.value = addr.id;
            option.text = addr.name + " - " + addr.city;

            dropdown.appendChild(option);

            // default select
            if (addr.is_default) {
                dropdown.value = addr.id;
                localStorage.setItem("selected_address_id", addr.id);
            }
        });

        // restore selected
        let saved = localStorage.getItem("selected_address_id");
        if (saved) dropdown.value = saved;

    })
    .catch(err => {
        console.error("Address load error:", err);
    });
}

// ===============================
// SAVE SELECTED ADDRESS
// ===============================
document.getElementById("address_id")?.addEventListener("change", function () {
    localStorage.setItem("selected_address_id", this.value);
});

// ===============================
// COD ORDER
// ===============================
function setAddress() {
    let addr = document.getElementById('address_id').value;

    if (!addr) {
        alert("⚠️ Please select address");
        return false;
    }

    document.getElementById('cod_address_id').value = addr;
    return true;
}

// ===============================
// ONLINE PAYMENT (RAZORPAY)
// ===============================
document.getElementById('pay-btn')?.addEventListener('click', function () {

    let addressId = document.getElementById('address_id').value;

    if (!addressId) {
        alert("⚠️ Please select address");
        return;
    }

    // STEP 1: Create Razorpay Order
    fetch('/payment/create', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrf
        },
        body: JSON.stringify({
            amount: {{ $cart['summary']['total'] }}
        })
    })
    .then(res => res.json())
    .then(data => {

        if (!data.success) {
            alert("❌ Payment init failed");
            return;
        }

        // STEP 2: Open Razorpay
      var options = {
    key: data.key,
    amount: data.amount,
    order_id: data.order_id,

    name: "Pharma ERP",
    description: "Order Payment",

    handler: function (response) {

        let formData = new FormData();
        formData.append('address_id', addressId);
        formData.append('payment_id', response.razorpay_payment_id);
        formData.append('payment_mode', 'razorpay');

        fetch('/orders/place', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrf
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {

            if (data.status) {
                alert("✅ Order placed successfully");
                window.location.href = "/orders";
            } else {
                alert("❌ Order failed: " + data.message);
            }

        })
        .catch(err => {
            console.error(err);
            alert("Server error while placing order");
        });

    }, // 🔥 handler END here

    modal: {
        ondismiss: function () {
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

// ===============================
// INIT
// ===============================
loadAddresses();

</script>
@endpush