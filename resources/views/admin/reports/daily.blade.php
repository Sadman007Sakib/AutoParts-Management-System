@extends('layouts.app')

@section('title', 'Daily Report')

@section('content')
<div class="container py-6">
  <h3>Daily Report — {{ \Carbon\Carbon::parse($date ?? now())->format('F j, Y') }}</h3>

  <form class="mb-4 d-flex gap-2" method="GET" action="{{ route('admin.reports.daily') }}">
    <input type="date" name="date" class="form-control" value="{{ $date ?? now()->toDateString() }}">
    <button class="btn btn-primary">View</button>
  </form>

  <div class="row g-3 mb-4">
    <div class="col-md-3"><div class="card p-3"><div class="text-muted">Transactions</div><div class="h4">{{ $totalSalesCount ?? 0 }}</div></div></div>
    <div class="col-md-3"><div class="card p-3"><div class="text-muted">Revenue</div><div class="h4">{{ number_format($totalRevenue ?? 0, 2) }}</div></div></div>
    <div class="col-md-3"><div class="card p-3"><div class="text-muted">Discounts</div><div class="h4">{{ number_format($totalDiscounts ?? 0, 2) }}</div></div></div>
    <div class="col-md-3"><div class="card p-3"><div class="text-muted">Profit</div><div class="h4">{{ number_format($grossProfitAfterDiscount ?? 0, 2) }}</div></div></div>
  </div>

  <div class="row">
    <div class="col-md-7">
      <h5>Recent Sales</h5>
      <table class="table table-sm">
        <thead><tr><th>Slip</th><th>Time</th><th>Cashier</th><th>Total</th></tr></thead>
        <tbody>
          @forelse($recentSales as $s)
            <tr>
              <td><a href="{{ route('sales.show', $s->id) }}">{{ $s->slip_no }}</a></td>
              <td>{{ $s->created_at->format('H:i') }}</td>
              <td>{{ $s->seller->name ?? 'N/A' }}</td>
              <td>{{ number_format($s->total_amount, 2) }}</td>
            </tr>
          @empty
            <tr><td colspan="4">No sales</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="col-md-5">
      <h5>Low Stock (&lt; {{ $threshold }})</h5>
      @if($lowParts->isEmpty())
        <div class="text-muted">No low stock items.</div>
      @else
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
      @endif
    </div>
  </div>
</div>
@endsection
