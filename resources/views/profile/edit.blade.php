@extends('layouts.app')
@section('title', 'Profile-Update')
@section('content')
<div class="container py-6">
  <div class="max-w-2xl mx-auto">

    <h2 class="mb-4">My Profile</h2>

    {{-- success / error flashes --}}
    @if(session('success'))
      <div id="success-alert" class="alert alert-success transition-opacity" role="alert">
        {{ session('success') }}
      </div>
    @endif

    @if($errors->any())
      <div class="alert alert-danger">
        <strong>There were some problems with your input.</strong>
        <ul class="mb-0 mt-2">
          @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('profile.update') }}">
      @csrf
      @method('PUT')

      <div class="mb-3">
        <label class="form-label">Name</label>
        <input name="name" value="{{ old('name', auth()->user()->name) }}" class="form-control @error('name') is-invalid @enderror" required>
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">Email</label>
        <input name="email" value="{{ old('email', auth()->user()->email) }}" class="form-control @error('email') is-invalid @enderror" required>
        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      <hr>

      <p class="text-muted small">To save any changes you must confirm with your current password. To change your password, enter a new one below (and confirm it).</p>

      <div class="mb-3">
        <label class="form-label">Current password <span class="text-danger">*</span></label>
        <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" placeholder="Enter current password" required>
        @error('current_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">New password (optional)</label>
        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter new password">
        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">Confirm new password</label>
        <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm new password">
      </div>

      <div class="d-flex gap-2">
        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">Cancel</a>
        <button class="btn btn-primary">Save changes</button>
      </div>
    </form>

    <div class="mt-4 pt-3 border-top">
  <h5 class="mb-2">Danger zone — Delete account</h5>
  <p class="text-muted small mb-3">
    Permanently delete your account. This will remove your profile and cannot be undone.
    To confirm, enter your current password and type your email address below.
  </p>

  <form method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Are you absolutely sure? This action will delete your account.')">
    @csrf
    @method('DELETE')

    <div class="mb-3">
      <label class="form-label">Current password <span class="text-danger">*</span></label>
      <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required>
      @error('current_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
      <label class="form-label">Confirm your email <span class="text-danger">*</span></label>
      <input type="email" name="confirm_email" class="form-control @error('confirm_email') is-invalid @enderror" placeholder="Type your full email to confirm" required>
      @error('confirm_email') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="d-flex gap-2">
      <button type="submit" class="btn btn-danger">Yes — Delete my account</button>
      <a href="{{ route('home') }}" class="btn btn-outline-secondary">Cancel</a>
    </div>
  </form>
</div>


  </div>
</div>

<style>
  /* small transition for alert fade */
  .transition-opacity { opacity: 1; transition: opacity 0.6s ease; }
  .hidden-opacity { opacity: 0 !important; }
</style>

<script>
  // Auto-hide success alert after 3 seconds with fade
  (function(){
    const alert = document.getElementById('success-alert');
    if (!alert) return;
    setTimeout(() => {
      alert.classList.add('hidden-opacity');
      // remove from DOM after transition
      setTimeout(() => { alert.remove(); }, 700);
    }, 3000);
  })();
</script>
@endsection
