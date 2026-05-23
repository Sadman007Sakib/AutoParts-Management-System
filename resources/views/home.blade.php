@extends('layouts.erp')

@section('title', 'ERP Dashboard - AutoParts')

@section('content')

@php
    $user = auth()->user();
@endphp


<div class="container py-4">

    {{-- ================= ERP HEADER ================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="mb-0">ERP Dashboard</h3>

            <small class="text-muted">
                Welcome back, {{ $user->name }} ({{ ucfirst($user->role) }})
            </small>
        </div>

        @if(in_array($user->role, ['admin','coordinator']))
            <a href="{{ route('parts.create') }}" class="btn btn-primary">
                + Add Part
            </a>
        @endif

    </div>


    {{-- ================= ERP KPI LAYER ================= --}}
    <div class="row g-3 mb-4">

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">Total Parts</div>
                    <div class="fs-3 fw-bold">{{ $totalParts }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">Total Stock</div>
                    <div class="fs-3 fw-bold">{{ $totalStock }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card border-0 shadow-sm bg-warning-subtle">
                <div class="card-body">
                    <div class="text-muted small">Low Stock</div>
                    <div class="fs-3 fw-bold text-warning">{{ $lowStockCount }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card border-0 shadow-sm bg-danger-subtle">
                <div class="card-body">
                    <div class="text-muted small">Out of Stock</div>
                    <div class="fs-3 fw-bold text-danger">{{ $outOfStockCount }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card border-0 shadow-sm bg-success-subtle">
                <div class="card-body">
                    <div class="text-muted small">Today's Sales</div>
                    <div class="fs-5 fw-bold text-success">
                        {{ number_format($todaySales ?? 0, 2) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="card mt-4">
            <div class="card-header bg-white">
                <strong>Top Selling Products</strong>
            </div>

            <div class="card-body">
                <ul class="list-group">
                    @foreach($topProducts as $p)
                        <li class="list-group-item d-flex justify-content-between">
                            <span>{{ $p->name }}</span>
                            <span class="badge bg-primary">{{ $p->qty }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        
        @if(in_array($user->role, ['admin','coordinator']))
            <div class="card mt-4">
                <div class="card-body">
                    <h6>Total Profit</h6>
                    <h3 class="text-success">{{ number_format($profit ?? 0, 2) }}</h3>
                </div>
            </div>
        @endif
    </div>

    {{-- ================= INVITE CODE SECTION ================= --}}
    @if(in_array($user->role, ['admin','coordinator']))

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <strong>Invite Code Management</strong>
        </div>

        <div class="card-body">

            <p class="text-muted mb-3">
                Generate secure invite codes for staff/customer registrations.
            </p>

            {{-- GENERATE BUTTON --}}
            <form action="{{ route('invite.generate') }}" method="GET">

                <button class="btn btn-dark">
                    Generate Invite Code
                </button>

            </form>

            {{-- GENERATED CODE DISPLAY --}}
            @if(session('generated_code'))

                <div class="alert alert-success mt-4 mb-0">

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                        <div>
                            <strong>Generated Code:</strong>

                            <span id="inviteCodeText"
                                class="ms-2 fs-5 fw-bold text-dark">
                                {{ session('generated_code') }}
                            </span>
                        </div>

                        <button type="button"
                                class="btn btn-outline-secondary btn-sm"
                                onclick="copyInviteCode()">

                            Copy Code

                        </button>

                    </div>

                </div>

            @endif

        </div>

    </div>

    @endif


    {{-- ================= ERP ANALYTICS ================= --}}
    <div class="row g-4 mb-4">

        {{-- STOCK CHART --}}
        <div class="col-md-8">
            <div class="card shadow-sm border-0 h-100">

                <div class="card-header bg-white">
                    <strong>Stock Overview</strong>
                </div>

                <div class="card-body" style="height: 350px;">
                    <canvas id="stockChart"></canvas>
                </div>

            </div>
        </div>


        {{-- INVENTORY STATUS --}}
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">

                <div class="card-header bg-white">
                    <strong>Inventory Status</strong>
                </div>

                <div class="card-body" style="height: 350px;">
                    <canvas id="inventoryPie"></canvas>
                </div>

            </div>
        </div>

    </div>


    {{-- ================= MODULE SHORTCUTS ================= --}}
    <div class="row g-3 mb-4">

        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">

                    <h5>Inventory Management</h5>
                    <p class="text-muted">Stock, parts, pricing</p>

                    <a href="{{ route('parts.index') }}" class="btn btn-primary btn-sm">
                        Open Inventory
                    </a>

                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">

                    <h5>POS System</h5>
                    <p class="text-muted">Sales & billing</p>

                    <a href="{{ route('sales.create') }}" class="btn btn-success btn-sm">
                        New Sale
                    </a>

                    <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary btn-sm">
                        Sales History
                    </a>

                </div>
            </div>
        </div>

    </div>


    {{-- ================= RECENT ACTIVITY ================= --}}
    <div class="card shadow-sm border-0">

        <div class="card-header bg-white">
            <strong>Recent Inventory Activity</strong>
        </div>

        <div class="table-responsive">

            <table class="table table-hover mb-0 align-middle">

                <thead class="table-light">
                    <tr>
                        <th>SKU</th>
                        <th>Name</th>
                        <th>Stock</th>
                        <th>Sell Price</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach(\App\Models\Part::latest()->limit(10)->get() as $p)

                        <tr>
                            <td class="text-muted">{{ $p->sku }}</td>
                            <td>{{ $p->name }}</td>
                            <td>{{ $p->current_quantity }}</td>
                            <td>{{ number_format($p->sell_price ?? 0, 2) }}</td>
                            <td>
                                @if($p->current_quantity == 0)
                                    <span class="badge bg-danger">OUT</span>
                                @elseif($p->current_quantity <= 5)
                                    <span class="badge bg-warning text-dark">LOW</span>
                                @else
                                    <span class="badge bg-success">OK</span>
                                @endif
                            </td>
                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>

</div>


{{-- ================= ERP CHARTS ================= --}}
<script>

    // STOCK CHART
    new Chart(document.getElementById('stockChart'), {
        type: 'bar',
        data: {
            labels: @json($stockLabels ?? []),
            datasets: [{
                label: 'Stock Quantity',
                data: @json($stockValues ?? []),
                backgroundColor: '#3b82f6'
            }]
        }
    });


    // PIE CHART
    new Chart(document.getElementById('inventoryPie'), {
        type: 'doughnut',
        data: {
            labels: ['Healthy', 'Low', 'Out'],
            datasets: [{
                data: [
                    {{ $totalParts - ($lowStockCount + $outOfStockCount) }},
                    {{ $lowStockCount }},
                    {{ $outOfStockCount }}
                ]
            }]
        }
    });

</script>
<script>

new Chart(document.getElementById('monthlyRevenue'), {
    type: 'line',
    data: {
        labels: @json(array_keys($monthlySales->toArray())),
        datasets: [{
            label: 'Revenue',
            data: @json(array_values($monthlySales->toArray())),
            borderColor: 'blue',
            fill: true
        }]
    }
});

</script>

<script>
function copyInviteCode() {

    let code = document.getElementById('inviteCodeText').innerText;

    navigator.clipboard.writeText(code);

    alert('Invite code copied!');
}
</script>
@endsection