@extends('layouts.erp')

@section('title', 'Sales-List (Walk-In)')

@section('content')

<div class="container-fluid py-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">

        <div>
            <h3 class="fw-bold mb-0">Sales</h3>
            <small class="text-muted">Walk-in & Online sales management</small>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('online-orders.index') }}"
               class="btn btn-warning rounded-3 px-3">
                Online Orders
            </a>

            <a href="{{ route('sales.create') }}"
               class="btn btn-primary rounded-3 px-3">
                + New Sale
            </a>
        </div>

    </div>

    {{-- KPI CARDS --}}
    <div class="row g-3 mb-4">

        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3">
                <div class="text-muted small">Total Sales</div>
                <h4 class="fw-bold mb-0">{{ $sales->total() }}</h4>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3">
                <div class="text-muted small">Online Orders</div>
                <h4 class="fw-bold mb-0">
                    {{ \App\Models\Sale::where('source', 'online')->count() }}
                </h4>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3">
                <div class="text-muted small">Walk-in Sales</div>
                <h4 class="fw-bold mb-0">
                    {{ \App\Models\Sale::where('source', 'offline')->count() }}
                </h4>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3">
                <div class="text-muted small">Total Revenue</div>
                <h4 class="fw-bold mb-0">
                    {{ number_format(\App\Models\Sale::sum('total_amount'), 2) }}
                </h4>
            </div>
        </div>

    </div>

    {{-- SUCCESS --}}
    @if (session('success'))
        <div class="alert alert-success rounded-3">
            {{ session('success') }}
        </div>
    @endif

    {{-- SEARCH --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body">

            <form method="GET" action="{{ route('sales.index') }}">

                <div class="row g-2">

                    <div class="col-md-6">
                        <input type="text"
                               name="search"
                               class="form-control rounded-3"
                               placeholder="Search slip / customer / seller..."
                               value="{{ request('search') }}">
                    </div>

                    <div class="col-md-2">
                        <button class="btn btn-primary w-100 rounded-3">
                            Search
                        </button>
                    </div>

                    <div class="col-md-2">
                        <a href="{{ route('sales.index') }}"
                           class="btn btn-outline-secondary w-100 rounded-3">
                            Reset
                        </a>
                    </div>

                </div>

            </form>

        </div>
    </div>

    {{-- TABLE --}}
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table align-middle mb-0">

                    <thead class="table-light">
                        <tr>
                            <th>Type</th>
                            <th>Slip</th>
                            <th>Customer</th>
                            <th>Sold By</th>
                            <th>Total</th>
                            <th>Date</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($sales as $sale)
                            <tr>

                                <td class="ps-3">
                                    @if ($sale->source === 'online')
                                        <span class="badge bg-success">ONLINE</span>
                                    @else
                                        <span class="badge bg-secondary">WALK-IN</span>
                                    @endif
                                </td>

                                <td>{{ $sale->slip_no }}</td>
                                <td>{{ $sale->customer_name ?? 'Walk-in' }}</td>
                                <td>{{ $sale->seller->name ?? 'N/A' }}</td>
                                <td>{{ number_format($sale->total_amount, 2) }}</td>
                                <td>{{ $sale->created_at->format('Y-m-d H:i') }}</td>

                                <td class="text-end pe-3">

                                    <a href="{{ route('sales.show', $sale->id) }}"
                                       class="btn btn-sm btn-outline-secondary rounded-3">
                                        Receipt
                                    </a>

                                    <a href="{{ route('sales.invoice', $sale->id) }}"
                                       class="btn btn-sm btn-primary rounded-3">
                                        Invoice
                                    </a>

                                    <a href="{{ route('sales.edit', $sale->id) }}"
                                       class="btn btn-sm btn-outline-primary rounded-3">
                                        Edit
                                    </a>

                                    @if (auth()->user()->role === 'admin')
                                        <form method="POST"
                                              action="{{ route('sales.destroy', $sale->id) }}"
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')

                                            <button class="btn btn-sm btn-danger rounded-3"
                                                    onclick="return confirm('Delete this sale and restore stock?')">
                                                Delete
                                            </button>
                                        </form>
                                    @endif

                                </td>

                            </tr>
                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    {{-- PAGINATION --}}
    <div class="mt-3">
        {{ $sales->appends(request()->query())->links() }}
    </div>

</div>

@endsection







{{--sales keno plus hocche na mane online order soho keno add hocche na--}}
