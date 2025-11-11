@extends('layouts.app')
@section('title', 'Sales-List')
@section('content')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Sales</h4>
    <a href="{{ route('sales.create') }}" class="btn btn-success">New Sale</a>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="table-responsive">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Slip</th>
          <th>Customer</th>
          <th>Sold by</th>
          <th>Total</th>
          <th>Date</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
      @foreach($sales as $sale)
        <tr>
          <td>{{ $sale->slip_no }}</td>
          <td>{{ $sale->customer_name ?? 'Walk-in' }}</td>
          <td>{{ $sale->seller->name ?? 'N/A' }}</td>
          <td>{{ number_format($sale->total_amount,2) }}</td>
          <td>{{ $sale->created_at->format('Y-m-d H:i') }}</td>
          <td class="text-end">
            <a href="{{ route('sales.show', $sale->id) }}" class="btn btn-sm btn-outline-primary">View</a>
            <a href="{{ route('sales.edit', $sale->id) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
            @if(auth()->user()->role === 'admin')
                <form method="POST" action="{{ route('sales.destroy', $sale->id) }}">
                    @csrf @method('DELETE')
                    <button class="btn btn-danger btn-sm" onclick="return confirm('Delete this sale and restore stock?')">Delete</button>
                </form>
            @endif
          </td>
        </tr>
      @endforeach
      </tbody>
    </table>
  </div>

  {{ $sales->links() }}
</div>
@endsection
