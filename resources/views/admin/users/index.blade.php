@extends('layouts.app')

@section('title', 'Users Role')

@section('content')
<div class="container py-4">
  <h3>Users</h3>

  <div class="mb-3 d-flex justify-content-between">
    <form class="d-flex" method="GET" action="{{ route('admin.users.index') }}">
      <input type="text" name="q" value="{{ $q ?? '' }}" class="form-control" placeholder="Search name or email" />
      <button class="btn btn-secondary ms-2">Search</button>
    </form>
    <div>
      <a href="{{ route('register') }}" class="btn btn-primary">Create User</a>
    </div>
  </div>

  @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
  @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

  <div class="table-responsive">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Email</th>
          <th>Role</th>
          <th>Joined</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($users as $u)
          <tr>
            <td>{{ $u->id }}</td>
            <td>{{ $u->name }}</td>
            <td>{{ $u->email }}</td>
            <td>{{ $u->role }}</td>
            <td>{{ $u->created_at->format('Y-m-d') }}</td>
            <td>
              <a href="{{ route('admin.users.edit', $u->id) }}" class="btn btn-sm btn-outline-primary">Change Role</a>

              @if(auth()->id() !== $u->id)
                <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Remove user access?')">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-sm btn-outline-danger">Remove</button>
                </form>
              @endif

              @if(method_exists($u,'trashed') && $u->trashed())
                <form action="{{ route('admin.users.restore', $u->id) }}" method="POST" style="display:inline-block;">
                  @csrf
                  <button class="btn btn-sm btn-outline-success">Restore</button>
                </form>
              @endif
            </td>
            <td>
                @if($u->trashed())
                    <span class="badge bg-danger">Deleted</span>
                @else
                    <span class="badge bg-success">Active</span>
                @endif
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <div class="mt-3">
    {{ $users->links() }}
  </div>
</div>
@endsection
