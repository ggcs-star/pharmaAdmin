@extends('layouts.app')

@section('content')

<div class="container mt-4">
    <h3>My Addresses</h3>

    <button class="btn btn-primary mb-3" onclick="openAddModal()">
        + Add Address
    </button>

    <div id="address-list"></div>
</div>

{{-- Add/Edit Modal --}}
<div class="modal fade" id="addressModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Add Address</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addressForm">
                    <input type="hidden" id="address_id">
                    <div class="mb-3">
                        <label>Name</label>
                        <input type="text" id="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Phone</label>
                        <input type="text" id="phone" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Address Line 1</label>
                        <input type="text" id="address_line_1" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Address Line 2 (Optional)</label>
                        <input type="text" id="address_line_2" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>City</label>
                        <input type="text" id="city" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>State</label>
                        <input type="text" id="state" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Pincode</label>
                        <input type="text" id="pincode" class="form-control" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveAddress()">Save</button>
            </div>
        </div>
    </div>
</div>

{{-- Hidden Template --}}
<div id="address-template" style="display:none;">
    <div class="card mb-3 p-3">
        <div class="d-flex justify-content-between align-items-start">
            <div class="flex-grow-1">
                <h5 class="name"></h5>
                <p class="phone"></p>
                <p class="full_address"></p>
                <span class="badge bg-success default-badge" style="display:none;">Default</span>
            </div>
            <div>
                <input type="radio" name="selected_address" class="address-radio" value="" style="transform: scale(1.2);">
            </div>
        </div>
        <div class="mt-2">
            <button class="btn btn-sm btn-warning edit-btn">Edit</button>
            <button class="btn btn-sm btn-danger delete-btn">Delete</button>
            <button class="btn btn-sm btn-info default-btn">Set Default</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
// API Configuration
const API_BASE_URL = "{{ env('API_BASE_URL', 'http://127.0.0.1:8000/api') }}";
const token = document.querySelector('meta[name="api-token"]')?.getAttribute('content') || '';

// Ensure API_BASE_URL doesn't have trailing slash
const BASE_URL = API_BASE_URL.replace(/\/$/, '');

console.log('API Base URL:', BASE_URL);

// Wait for DOM to be ready
document.addEventListener('DOMContentLoaded', function() {
    if (typeof $ !== 'undefined') {
        if (!token) {
            $("#address-list").html('<div class="alert alert-warning">Please login to manage addresses</div>');
        } else {
            loadAddresses();
        }
    } else {
        console.error('jQuery not loaded');
    }
});

function loadAddresses() {
    if (!$) {
        console.error('jQuery not available');
        return;
    }
    
    const url = `${BASE_URL}/addresses`;
    console.log('Loading addresses from:', url);
    
    $.ajax({
        url: url,
        method: "GET",
        headers: {
            Authorization: "Bearer " + token,
            Accept: "application/json"
        },
        success: function(res) {
            console.log('Addresses loaded:', res);
            $("#address-list").html("");
            
            if (!res.data || res.data.length === 0) {
                $("#address-list").html('<div class="alert alert-info">No addresses found. Add your first address!</div>');
                return;
            }
            
            res.data.forEach(addr => {
                let card = $("#address-template").clone().removeAttr("id").show();
                
                card.find(".name").text(addr.name);
                card.find(".phone").text("📞 " + addr.phone);
                card.find(".full_address").text(
                    "📍 " + addr.address_line_1 + ", " +
                    (addr.address_line_2 ? addr.address_line_2 + ", " : "") +
                    addr.city + ", " + addr.state + " - " + addr.pincode
                );
                
                if (addr.is_default) {
                    card.find(".default-badge").show();
                }
                
                // Set radio button value
                card.find(".address-radio").val(addr.id);
                
                // Bind buttons
                card.find(".edit-btn").click(() => openEditModal(addr));
                card.find(".delete-btn").click(() => deleteAddress(addr.id));
                card.find(".default-btn").click(() => setDefault(addr.id));
                
                $("#address-list").append(card);
            });
        },
        error: function(xhr) {
            console.error('Error loading addresses:', xhr);
            if (xhr.status === 401) {
                $("#address-list").html('<div class="alert alert-warning">Please login to view addresses</div>');
            } else if (xhr.status === 0) {
                $("#address-list").html('<div class="alert alert-danger">Cannot connect to API server. Please check if backend is running at ' + BASE_URL + '</div>');
            } else {
                $("#address-list").html('<div class="alert alert-danger">Error loading addresses: ' + (xhr.responseJSON?.message || xhr.statusText) + '</div>');
            }
        }
    });
}

function openAddModal() {
    $("#modalTitle").text("Add Address");
    $("#address_id").val("");
    $("#addressForm")[0].reset();
    $("#addressModal").modal("show");
}

function openEditModal(addr) {
    $("#modalTitle").text("Edit Address");
    $("#address_id").val(addr.id);
    $("#name").val(addr.name);
    $("#phone").val(addr.phone);
    $("#address_line_1").val(addr.address_line_1);
    $("#address_line_2").val(addr.address_line_2);
    $("#city").val(addr.city);
    $("#state").val(addr.state);
    $("#pincode").val(addr.pincode);
    $("#addressModal").modal("show");
}

function saveAddress() {
    const id = $("#address_id").val();
    const data = {
        name: $("#name").val(),
        phone: $("#phone").val(),
        address_line_1: $("#address_line_1").val(),
        address_line_2: $("#address_line_2").val(),
        city: $("#city").val(),
        state: $("#state").val(),
        pincode: $("#pincode").val()
    };
    
    if (!data.name || !data.phone || !data.address_line_1 || !data.city || !data.state || !data.pincode) {
        alert("Please fill all required fields");
        return;
    }
    
    const url = id ? `${BASE_URL}/addresses/${id}` : `${BASE_URL}/addresses`;
    const method = id ? "PUT" : "POST";
    
    console.log('Saving address to:', url);
    
    $.ajax({
        url: url,
        method: method,
        headers: {
            Authorization: "Bearer " + token,
            "Content-Type": "application/json",
            Accept: "application/json"
        },
        data: JSON.stringify(data),
        success: function(res) {
            alert(res.message || "Address saved successfully");
            $("#addressModal").modal("hide");
            loadAddresses();
        },
        error: function(xhr) {
            console.error('Save error:', xhr);
            alert(xhr.responseJSON?.message || "Error saving address");
        }
    });
}

function deleteAddress(id) {
    if (!confirm("Delete this address?")) return;
    
    const url = `${BASE_URL}/addresses/${id}`;
    console.log('Deleting address:', url);
    
    $.ajax({
        url: url,
        method: "DELETE",
        headers: {
            Authorization: "Bearer " + token,
            Accept: "application/json"
        },
        success: function(res) {
            alert(res.message);
            loadAddresses();
        },
        error: function(xhr) {
            console.error('Delete error:', xhr);
            alert(xhr.responseJSON?.message || "Error deleting address");
        }
    });
}

function setDefault(id) {
    const url = `${BASE_URL}/addresses/${id}/default`;
    console.log('Setting default address:', url);
    
    $.ajax({
        url: url,
        method: "POST",
        headers: {
            Authorization: "Bearer " + token,
            Accept: "application/json"
        },
        success: function(res) {
            alert(res.message);
            loadAddresses();
        },
        error: function(xhr) {
            console.error('Set default error:', xhr);
            alert(xhr.responseJSON?.message || "Error setting default address");
        }
    });
}
</script>
@endpush