@extends('layouts.erp')

@section('title', 'Inventory Parts')

@section('content')
@php
    use Illuminate\Support\Str;
@endphp

<div class="container-fluid py-4">

    {{-- PAGE HEADER --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">

        <div>
            <h2 class="fw-bold mb-1">Inventory Management</h2>
            <p class="text-muted mb-0">
                Manage parts, stock levels, pricing and inventory status.
            </p>
        </div>

        <div class="d-flex gap-2 flex-wrap">

            @if(auth()->user()->role === 'admin')
                <a href="{{ route('parts.trashed') }}"
                   class="btn btn-outline-warning">
                    Trashed Parts
                </a>
            @endif

            @if(in_array(auth()->user()->role, ['admin','coordinator']))
                <a href="{{ route('parts.create') }}"
                   class="btn btn-primary">
                    + Add Part
                </a>
            @endif

        </div>

    </div>

    {{-- DASHBOARD CARDS --}}
    <div class="row g-3 mb-4">

        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="text-muted small mb-1">
                        TOTAL PARTS
                    </div>

                    <h3 class="fw-bold mb-0">
                        {{ $stats['total_parts'] }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">

                    <div class="text-muted small mb-1">
                        LOW STOCK
                    </div>

                    <h3 class="fw-bold text-warning mb-0">
                        {{ $stats['low_stock'] }}
                    </h3>

                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">

                    <div class="text-muted small mb-1">
                        OUT OF STOCK
                    </div>

                    <h3 class="fw-bold text-danger mb-0">
                        {{ $stats['out_of_stock'] }}
                    </h3>

                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">

                    <div class="text-muted small mb-1">
                        INVENTORY VALUE
                    </div>

                    <h4 class="fw-bold text-success mb-0">
                        ${{ number_format($stats['inventory_value'] ?? 0, 2) }}
                    </h4>

                </div>
            </div>
        </div>

    </div>

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
        <div class="alert alert-success shadow-sm border-0">
            {{ session('success') }}
        </div>
    @endif

    {{-- FILTER BAR --}}
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-body">

            <form method="GET" action="{{ route('parts.index') }}">

                <div class="row g-3 align-items-end">

                    {{-- SEARCH --}}
                    <div class="col-lg-4">
                        <label class="form-label small fw-semibold">
                            Search
                        </label>

                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               class="form-control"
                               placeholder="Search SKU, name or brand">
                    </div>

                    {{-- BRAND --}}
                    <div class="col-lg-2">
                        <label class="form-label small fw-semibold">
                            Brand
                        </label>

                        <select name="brand" class="form-select">

                            <option value="">All Brands</option>

                            @foreach($brands as $brand)
                                <option value="{{ $brand }}"
                                    {{ request('brand') == $brand ? 'selected' : '' }}>
                                    {{ $brand }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    {{-- STOCK --}}
                    <div class="col-lg-2">
                        <label class="form-label small fw-semibold">
                            Stock
                        </label>

                        <select name="stock" class="form-select">

                            <option value="">All</option>

                            <option value="low"
                                {{ request('stock') == 'low' ? 'selected' : '' }}>
                                Low Stock
                            </option>

                            <option value="out"
                                {{ request('stock') == 'out' ? 'selected' : '' }}>
                                Out of Stock
                            </option>

                        </select>
                    </div>

                    {{-- SORT --}}
                    <div class="col-lg-2">
                        <label class="form-label small fw-semibold">
                            Sort
                        </label>

                        <select name="sort" class="form-select">

                            <option value="latest">Latest</option>

                            <option value="name_asc"
                                {{ request('sort') == 'name_asc' ? 'selected' : '' }}>
                                Name A-Z
                            </option>

                            <option value="qty_low"
                                {{ request('sort') == 'qty_low' ? 'selected' : '' }}>
                                Qty Low-High
                            </option>

                            <option value="qty_high"
                                {{ request('sort') == 'qty_high' ? 'selected' : '' }}>
                                Qty High-Low
                            </option>

                        </select>
                    </div>

                    {{-- BUTTONS --}}
                    <div class="col-lg-2 d-grid">
                        <button class="btn btn-dark">
                            Apply Filters
                        </button>
                    </div>

                </div>

            </form>

        </div>

    </div>

    {{-- TABLE CARD --}}
    <div class="card shadow-sm border-0">

        <div class="card-body p-0">

            @if($parts->count())

                <div class="table-responsive">

                    <table class="table align-middle mb-0">

                        <thead class="table-light">

                            <tr>
                                <th>Image</th>
                                <th>SKU</th>
                                <th>Part</th>
                                <th>Brand</th>
                                <th>Qty</th>
                                <th>Status</th>
                                <th>Sell</th>

                                @if(in_array(auth()->user()->role, ['admin','coordinator']))
                                    <th>Cost</th>
                                @endif

                                <th>Added By</th>
                                <th width="220">Actions</th>
                            </tr>

                        </thead>

                        <tbody>

                            @foreach($parts as $p)

                                <tr>

                                    {{-- IMAGE --}}
                                    <td width="90">

                                        @if($p->primary_image)

                                            <a href="{{ Storage::url($p->primary_image->path) }}"
                                               target="_blank">

                                                <img src="{{ Storage::url($p->primary_image->path) }}"
                                                     class="rounded border"
                                                     style="width:70px;height:70px;object-fit:cover;">

                                            </a>

                                        @else

                                            <div class="bg-light border rounded d-flex align-items-center justify-content-center"
                                                 style="width:70px;height:70px;font-size:12px;">

                                                No Image

                                            </div>

                                        @endif

                                    </td>

                                    {{-- SKU --}}
                                    <td>
                                        <span class="fw-semibold">
                                            {{ $p->sku }}
                                        </span>
                                    </td>

                                    {{-- NAME --}}
                                    <td>

                                        <div class="fw-semibold">
                                            {{ $p->name }}
                                        </div>

                                        @if($p->description)
                                            <small class="text-muted">
                                                {{ Str::limit($p->description, 50) }}
                                            </small>
                                        @endif

                                    </td>

                                    {{-- BRAND --}}
                                    <td>
                                        {{ $p->brand ?? '-' }}
                                    </td>

                                    {{-- QTY --}}
                                    <td>
                                        <span class="fw-bold">
                                            {{ $p->current_quantity }}
                                        </span>
                                    </td>

                                    {{-- STATUS --}}
                                    <td>

                                        @if($p->current_quantity == 0)

                                            <span class="badge bg-danger">
                                                OUT OF STOCK
                                            </span>

                                        @elseif($p->current_quantity <= 5)

                                            <span class="badge bg-warning text-dark">
                                                LOW STOCK
                                            </span>

                                        @else

                                            <span class="badge bg-success">
                                                IN STOCK
                                            </span>

                                        @endif

                                    </td>

                                    {{-- SELL --}}
                                    <td>
                                        ${{ number_format($p->sell_price ?? 0, 2) }}
                                    </td>

                                    {{-- COST --}}
                                    @if(in_array(auth()->user()->role, ['admin','coordinator']))
                                        <td>
                                            ${{ number_format($p->cost_price ?? 0, 2) }}
                                        </td>
                                    @endif

                                    {{-- CREATOR --}}
                                    <td>
                                        {{ $p->creator->name ?? '-' }}
                                    </td>

                                    {{-- ACTIONS --}}
                                    <td>

                                        <div class="d-flex flex-wrap gap-2">

                                            @if($p->current_quantity > 0)

                                                <a href="{{ route('sales.create', ['prefill' => $p->id]) }}"
                                                   class="btn btn-success btn-sm">
                                                    Sell
                                                </a>

                                            @else

                                                <button class="btn btn-secondary btn-sm" disabled>
                                                    Out
                                                </button>

                                            @endif

                                            @if(in_array(auth()->user()->role, ['admin','coordinator']))

                                                <a href="{{ route('parts.edit', $p) }}"
                                                   class="btn btn-outline-primary btn-sm">
                                                    Edit
                                                </a>

                                                <form action="{{ route('parts.destroy', $p) }}"
                                                      method="POST"
                                                      onsubmit="return confirm('Delete this part?')">

                                                    @csrf
                                                    @method('DELETE')

                                                    <button class="btn btn-danger btn-sm">
                                                        Delete
                                                    </button>

                                                </form>

                                            @endif

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="p-5 text-center">

                    <h5 class="mb-2">
                        No Parts Found
                    </h5>

                    <p class="text-muted mb-0">
                        Try adjusting your filters or add new inventory.
                    </p>

                </div>

            @endif

        </div>

    </div>

    {{-- PAGINATION --}}
    <div class="mt-4">
        {{ $parts->links() }}
    </div>

</div>

@endsection