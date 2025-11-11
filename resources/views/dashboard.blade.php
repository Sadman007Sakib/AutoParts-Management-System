@extends('layouts.app')
@section('title', 'Dash')
@section('content')
<div class="flex justify-center items-center min-h-[calc(100vh-80px)] bg-gray-50 py-10">
  <div class="bg-white shadow-lg rounded-2xl p-8 w-full max-w-md text-center border border-gray-100">
    <div class="col-12">
      <h2 class="text-2xl font-bold text-gray-800">Dashboard</h2>
      <p class="text-muted">Welcome, {{ auth()->user()->name }} — Role: <strong class="font-semibold text-blue-600">{{ ucfirst(auth()->user()->role) }}</strong></p>
      <h5 class="mt-4 text-gray-500">Parts</h5>
          <p class="card-text">Total parts: {{ \App\Models\Part::count() }}</p>
          <a href="{{ route('parts.index') }}" class="block w-full py-3 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 transition duration-200">
               🧩 Manage Parts</a>
    </div>
  </div>

  <div class="row mt-4">
    <div class="col-md-4">
      <div class="card">
        <div class="card-body">
          
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
