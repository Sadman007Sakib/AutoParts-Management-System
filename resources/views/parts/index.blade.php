@extends('layouts.app')
@section('title', 'Inventory-parts')
@section('content')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Parts</h3>
    @if(auth()->user() && in_array(auth()->user()->role, ['admin','coordinator']))
      <a href="{{ route('parts.create') }}" class="btn btn-primary">Add New Part</a>
      @if(auth()->user() && in_array(auth()->user()->role, ['admin']))
      <a href="{{ route('parts.trashed') }}" class="btn btn-warning">View Trashed</a>@endif
    @endif
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  @if($parts->count())
    <table class="table table-striped">
      <thead>
        <tr>
          <th>SKU</th>
          <th>Name</th>
          <th>Brand</th>
          <th>Qty</th>
          <th>Sell Price</th>
          @if(auth()->user()->role === 'admin' || auth()->user()->role === 'coordinator')
                <th>Cost Price</th>
            @endif
          <th>Added By</th>
          <th>Actions</th>
          <th>--</th>
        </tr>
      </thead>
      <tbody>
        @foreach($parts as $p)
        <tr>
          <td>{{ $p->sku }}</td>
          <td>{{ $p->name }}</td>
          <td>{{ $p->brand }}</td>
          <td>{{ $p->current_quantity }}</td>
          <td>{{ number_format($p->sell_price ?? 0, 2) }}</td>
          @if(auth()->user()->role === 'admin' || auth()->user()->role === 'coordinator')
                    <td>${{ $p->cost_price }}</td>
          @endif
          <td>{{ $p->creator ? $p->creator->name : '-' }}</td>
          
          <td>
            @if(auth()->user() && in_array(auth()->user()->role, ['admin','coordinator']))
              <a href="{{ route('parts.edit', $p) }}" class="btn btn-sm btn-outline-primary">Edit</a>
              <form action="{{ route('parts.destroy', $p) }}" method="POST" style="display:inline" onsubmit="return confirm('Delete this part?');">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-danger">Delete</button>
              </form>
            @else
              <span class="text-muted">—</span>
            @endif
          </td>
          <td>
            <!-- existing edit/delete buttons -->
            <a href="{{ route('sales.create', ['prefill' => $p->id]) }}" class="btn btn-sm btn-success" title="Sell this part">
              Sell
            </a>
          </td>
          <!-- put this inside the <tr> for each $p -->
          <td style="min-width:180px;">
            <div class="d-flex flex-wrap gap-2 align-items-center">
              @if($p->images && $p->images->count())
                @foreach($p->images as $img)
                  <div class="position-relative" style="width:72px;">
                    <img src="{{ Storage::url($img->path) }}" alt="{{ $p->name }}" class="img-thumbnail" style="width:72px;height:48px;object-fit:cover;">
                    @if(auth()->user() && in_array(auth()->user()->role, ['admin','coordinator']))
                      <form method="POST" action="{{ route('parts.images.destroy', $img->id) }}" style="position:absolute; top:2px; right:2px;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger p-0" style="line-height:1; width:20px; height:20px;" onclick="return confirm('Delete this image?')">&times;</button>
                      </form>
                    @endif
                  </div>
                @endforeach
              @else
                <div class="text-muted small">No image</div>
              @endif
            </div>
          </td>

        </tr>
        @endforeach
      </tbody>
    </table>

    {{ $parts->links() }}
  @else
    <div class="alert alert-info">No parts yet.</div>
  @endif
</div>
@endsection
