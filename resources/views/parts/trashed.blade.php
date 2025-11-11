@extends('layouts.app')
@section('title', 'Trash-Parts')
@section('content')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Trashed Parts</h3>
    <a href="{{ route('parts.index') }}" class="btn btn-primary">Back to Parts</a>
  </div>

  @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif

  @if($parts->count())
    <table class="table table-striped">
      <thead>
        <tr><th>SKU</th><th>Name</th><th>Deleted At</th><th>Actions</th></tr>
      </thead>
      <tbody>
        @foreach($parts as $p)
        <tr>
          <td>{{ $p->sku }}</td>
          <td>{{ $p->name }}</td>
          <td>{{ $p->deleted_at }}</td>
          <td>
            <form action="{{ route('parts.restore', $p->id) }}" method="POST" style="display:inline">
              @csrf
              <button class="btn btn-sm btn-success">Restore</button>
            </form>

            <form action="{{ route('parts.forceDelete', $p->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Permanently delete?');">
              @csrf
              @method('DELETE')
              <button class="btn btn-sm btn-danger">Delete Permanently</button>
            </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>

    {{ $parts->links() }}
  @else
    <div class="alert alert-info">No trashed parts found.</div>
  @endif
</div>
@endsection
