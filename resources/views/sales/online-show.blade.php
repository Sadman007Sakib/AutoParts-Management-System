@extends('layouts.erp')

@section('title', 'Online Order Management')

@section('content')

<div class="container-fluid py-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="mb-0 fw-bold">Manage Order #{{ $sale->slip_no }}</h3>
            <small class="text-muted">Update order status, payment and delivery</small>
        </div>

        <a href="{{ route('online-orders.index') }}" class="btn btn-light border rounded-3 px-4">
            Back
        </a>

    </div>

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
        <div class="alert alert-success rounded-3">
            {{ session('success') }}
        </div>
    @endif

    {{-- CARD --}}
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-body p-4">

            <form method="POST" action="{{ route('online-orders.update', $sale->id) }}">

                @csrf
                @method('PUT')

                {{-- ORDER STATUS --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Order Status</label>
                    <select name="status" class="form-select rounded-3">
                        <option value="pending" {{ $sale->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ $sale->status == 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="completed" {{ $sale->status == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>

                {{-- PAYMENT STATUS --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Payment Status</label>
                    <select name="payment_status" class="form-select rounded-3">
                        <option value="unpaid" {{ $sale->payment_status == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                        <option value="paid" {{ $sale->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                    </select>
                </div>

                {{-- DELIVERY STATUS --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold">Delivery Status</label>
                    <select name="delivery_status" class="form-select rounded-3">
                        <option value="pending" {{ $sale->delivery_status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="shipped" {{ $sale->delivery_status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="delivered" {{ $sale->delivery_status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                    </select>
                </div>

                {{-- ACTION --}}
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('online-orders.index') }}" class="btn btn-light border rounded-3">
                        Cancel
                    </a>

                    <button class="btn btn-primary rounded-3 px-4">
                        Update Order
                    </button>
                </div>

            </form>

        </div>

    </div>

</div>

@endsection