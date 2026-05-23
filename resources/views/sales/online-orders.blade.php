@extends('layouts.erp')
@section('title', 'Sales-List (Online)')
@section('content')

    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Online Orders</h2>
            <a href="{{ route('sales.index') }}" class="btn btn-primary">Back</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        {{-- SEARCH --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body">

                <form method="GET" action="{{ route('online-orders.index') }}">

                    <div class="row g-2">

                        <div class="col-md-6">
                            <input type="text" name="search" class="form-control rounded-3"
                                placeholder="Search slip / customer / seller..." value="{{ request('search') }}">
                        </div>

                        <div class="col-md-2">
                            <button class="btn btn-primary w-100 rounded-3">
                                Search
                            </button>
                        </div>

                        <div class="col-md-2">
                            <a href="{{ route('online-orders.index') }}" class="btn btn-outline-secondary w-100 rounded-3">
                                Reset
                            </a>
                        </div>

                    </div>

                </form>

            </div>
        </div>
        <div class="card">
            <div class="card-body table-responsive">

                <table class="table table-bordered align-middle">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Slip No</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Order Status</th>
                            <th>Payment</th>
                            <th>Delivery</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($sales as $sale)
                            <tr>

                                <td>{{ $sale->id }}</td>

                                <td>{{ $sale->slip_no }}</td>

                                <td>{{ $sale->customer_name }}</td>

                                <td>{{ number_format($sale->total_amount, 2) }}</td>

                                <td>
                                    <span class="badge bg-primary">
                                        {{ ucfirst($sale->status) }}
                                    </span>
                                </td>

                                <td>
                                    <span class="badge bg-warning text-dark">
                                        {{ ucfirst($sale->payment_status) }}
                                    </span>
                                </td>

                                <td>
                                    <span class="badge bg-info text-dark">
                                        {{ ucfirst($sale->delivery_status) }}
                                    </span>
                                </td>

                                <td>{{ $sale->created_at->format('Y-m-d H:i') }}</td>

                                <td>

                                    <a href="{{ route('online-orders.show', $sale->id) }}" class="btn btn-sm btn-dark">
                                        Manage
                                    </a>
                                    <a href="{{ route('sales.invoice', $sale->id) }}" class="btn btn-sm btn-primary">
                                        Invoice
                                    </a>
                                    @if (auth()->user()->role === 'admin')
                                        <form method="POST" action="{{ route('sales.destroy', $sale->id) }}"
                                            class="d-inline">

                                            @csrf
                                            @method('DELETE')

                                            <button class="btn btn-sm btn-danger"
                                                onclick="return confirm('Delete this sale and restore stock?')">
                                                Delete
                                            </button>

                                        </form>
                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="9" class="text-center">
                                    No online orders found.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>

                <div class="mt-3">
                    {{ $sales->appends(request()->query())->links() }}
                </div>

            </div>
        </div>

    </div>

@endsection
