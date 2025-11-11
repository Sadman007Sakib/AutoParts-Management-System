@extends('layouts.app')

@section('title', 'User Role Update')

@section('content')
<div class="container py-4">
  <h3>Edit User — {{ $user->name }}</h3>

  @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

  <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
    @csrf
    @method('PUT')

    <div class="mb-3">
      <label class="form-label">Name</label>
      <input class="form-control" value="{{ $user->name }}" disabled>
    </div>

    <div class="mb-3">
      <label class="form-label">Email</label>
      <input class="form-control" value="{{ $user->email }}" disabled>
    </div>

    <div class="mb-3">
      <label class="form-label">Role</label>
      <select name="role" class="form-select" required>
        @foreach($roles as $r)
          <option value="{{ $r }}" @if($user->role === $r) selected @endif>{{ ucfirst($r) }}</option>
        @endforeach
      </select>
    </div>

    <div class="d-flex gap-2">
      <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Back</a>
      <button class="btn btn-primary">Save Role</button>
    </div>
  </form>
</div>
@endsection
