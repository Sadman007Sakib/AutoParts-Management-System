@extends('layouts.app')
@section('title', 'Add-Parts')
@section('content')
<div class="container py-4">
  <div class="row justify-content-center">
    <div class="col-12 col-lg-10"> <!-- change col-lg to make form narrower/wider -->
      <div class="card shadow-sm">
        <div class="card-body">
          <h4 class="card-title mb-3">Add / Edit Part</h4>

          <form method="POST" action="{{ route('parts.store') }}" enctype="multipart/form-data">
            @csrf
            {{-- If edit page, use: @method('PUT') and set action to route('parts.update', $part) --}}

            <div class="col-12 col-md-6">
              <label class="form-label">SKU</label>
              <input type="text" name="sku" value="{{ old('sku', $part->sku ?? '') }}" class="form-control" readonly placeholder="(auto-generated)">
              <div class="form-text">SKU is generated automatically. You can edit it on the edit page if needed.</div>
              @error('sku') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>


              <div class="col-12 col-md-6">
                <label class="form-label">Name</label>
                <input name="name" value="{{ old('name', $part->name ?? '') }}" class="form-control" required>
                @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
              </div>

              <div class="col-12 col-md-6">
                <label class="form-label">Brand</label>
                <input name="brand" value="{{ old('brand', $part->brand ?? '') }}" class="form-control">
                @error('brand') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
              </div>

              <div class="col-12 col-md-6">
                <label class="form-label">Current Quantity</label>
                <input name="current_quantity" value="{{ old('current_quantity', $part->current_quantity ?? 0) }}" class="form-control" type="number" min="0" required>
                @error('current_quantity') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
              </div>

              <div class="col-12 col-md-6">
                <label class="form-label">Sell Price</label>
                <input name="sell_price" value="{{ old('sell_price', $part->sell_price ?? '') }}" class="form-control" type="number" step="0.01">
                @error('sell_price') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
              </div>

              <div class="col-12 col-md-6">
                <label class="form-label">Cost Price</label>
                <input name="cost_price" value="{{ old('cost_price', $part->cost_price ?? '') }}" class="form-control" type="number" step="0.01">
                @error('cost_price') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
              </div>

              <div class="col-12">
                <label class="form-label">Description</label>
                <textarea name="description" rows="3" class="form-control">{{ old('description', $part->description ?? '') }}</textarea>
                @error('description') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label">Product Images</label>
              <input type="file" name="images[]" class="form-control" multiple accept="image/*">
              <div class="form-text">You may upload multiple images (max 4MB each).</div>
                @error('images.*') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
              <a href="{{ route('parts.index') }}" class="btn btn-secondary">Cancel</a>
              <button type="submit" class="btn btn-success">Save</button>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
