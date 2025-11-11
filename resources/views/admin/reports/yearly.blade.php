@extends('layouts.app')

@section('title', 'Yearly Report')

@section('content')
<div class="container py-6">
  <h3 class="mb-4">Yearly Report — {{ $period ?? now()->format('Y') }}</h3>

  <form class="mb-4 d-flex gap-2" method="GET" action="{{ route('admin.reports.yearly') }}">
    <input type="number" name="year" class="form-control" value="{{ $period ?? now()->format('Y') }}" min="2000" max="{{ now()->format('Y') }}">
    <button class="btn btn-primary">View</button>
  </form>
<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="card p-3"><div class="text-muted">Transactions</div><div class="h4">{{ $totalSalesCount ?? 0 }}</div></div></div>
    <div class="col-md-3"><div class="card p-3"><div class="text-muted">Revenue</div><div class="h4">{{ number_format($totalRevenue ?? 0, 2) }}</div></div></div>
    <div class="col-md-3"><div class="card p-3"><div class="text-muted">Discounts</div><div class="h4">{{ number_format($totalDiscounts ?? 0, 2) }}</div></div></div>
    <div class="col-md-3"><div class="card p-3"><div class="text-muted">Profit</div><div class="h4">{{ number_format($grossProfitAfterDiscount ?? 0, 2) }}</div></div></div>
  </div>
  <div class="grid md:grid-cols-2 gap-6">
    <div class="bg-white p-4 rounded shadow-sm">
      <h5 class="mb-3">Recent Sales</h5>
      <div class="table-responsive">
        <table class="table table-sm">
          <thead><tr><th>Slip</th><th>Date</th><th>Cashier</th><th>Total</th></tr></thead>
          <tbody>
            @forelse($recentSales as $s)
              <tr>
                <td><a href="{{ route('sales.show', $s->id) }}">{{ $s->slip_no }}</a></td>
                <td>{{ $s->created_at->format('d M, Y H:i') }}</td>
                <td>{{ $s->seller->name ?? 'N/A' }}</td>
                <td>{{ number_format($s->total_amount, 2) }}</td>
              </tr>
            @empty
              <tr><td colspan="4">No sales for this period.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <div class="bg-white p-4 rounded shadow-sm">
      <h5 class="mb-3">Low Stock (&lt; {{ $threshold }})</h5>
      @if($lowParts->isEmpty())
        <div class="text-sm text-gray-500">No low stock items.</div>
      @else
        <div class="table-responsive">
          <table class="table table-sm">
            <thead><tr><th>SKU</th><th>Name</th><th>Qty</th><th></th></tr></thead>
            <tbody>
              @foreach($lowParts as $p)
                <tr @if($p->current_quantity==0) class="table-danger" @endif>
                  <td>{{ $p->sku }}</td>
                  <td>{{ $p->name }}</td>
                  <td>{{ $p->current_quantity }}</td>
                  <td><a href="{{ route('parts.edit', $p->id) }}" class="btn btn-sm btn-outline-primary">Edit</a></td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>
  </div>
</div>
@endsection
