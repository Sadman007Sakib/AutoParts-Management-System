@extends('layouts.app')

@section('title', 'Dashboard - AutoParts')

@section('content')
<div class="min-h-[calc(100vh-80px)] flex items-start justify-center bg-gray-50 py-12">
  <div class="w-full max-w-5xl px-4">

    <!-- Welcome Section -->
    <div class="mb-6 bg-white border border-gray-100 shadow-sm rounded-2xl p-6 flex flex-col items-center justify-center text-center">
      <div>
        <h2 class="text-xl font-semibold text-gray-800">
          Welcome, {{ auth()->user()->name ?? 'User' }} 👋
        </h2>
        <p class="text-sm text-gray-500 mt-1">
          Ask the admin for higher access if needed.
        </p>
        <p class="text-sm text-gray-500 mt-1">
          Your current role is 
          <span class="font-medium text-gray-800">{{ ucfirst(auth()->user()->role ?? 'Guest') }}</span>.
        </p>
      </div>

      <div class="hidden sm:block text-gray-400 text-sm">
        <svg class="h-10 w-10 text-blue-500 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path d="M5 13l4 4L19 7" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </div>
    </div>

    <!-- Top row: two cards side-by-side -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

      <!-- Parts Card -->
      <div class="relative overflow-hidden rounded-2xl shadow-lg border border-gray-100 bg-gradient-to-b from-white to-gray-50">
        <div class="p-6 flex items-start gap-4">
          <div class="flex-shrink-0">
            <!-- icon circle -->
            <div class="h-12 w-12 rounded-lg bg-blue-50 flex items-center justify-center">
              <!-- parts icon -->
              <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 2v6M5 12H2m20 0h-3M5 12l3 9h8l3-9M7 12v-3a5 5 0 0110 0v3" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </div>
          </div>

          <div class="flex-1">
            <div class="flex items-start justify-between">
              <div>
                <h3 class="text-lg font-semibold text-gray-800">Parts</h3>
                <p class="mt-1 text-sm text-gray-500">Manage inventory: add, edit, and restore parts.</p>
              </div>

              <div class="text-xs text-gray-400">Inventory</div>
            </div>

            <div class="mt-6 grid grid-cols-2 gap-4">
              <div class="bg-white p-3 rounded-lg border border-gray-100 flex items-center gap-3">
                <div class="p-2 bg-blue-100 rounded">
                  <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 7h18M5 7v12a2 2 0 002 2h10a2 2 0 002-2V7" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
                <div>
                  <div class="text-lg font-semibold text-gray-800">{{ \App\Models\Part::count() }}</div>
                  <div class="text-xs text-gray-500">Total parts</div>
                </div>
              </div>

              <div class="bg-white p-3 rounded-lg border border-gray-100 flex items-center gap-3">
                <div class="p-2 bg-green-100 rounded">
                  <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 12h18M8 12v6m8-6v6" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
                <div>
                  <div class="text-lg font-semibold text-gray-800">{{ \App\Models\Part::sum('current_quantity') }}</div>
                  <div class="text-xs text-gray-500">In stock</div>
                </div>
              </div>
            </div>

            <div class="mt-6 flex gap-3">
              <a href="{{ route('parts.index') }}"
                 class="inline-flex items-center justify-center gap-2 flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg shadow-sm hover:bg-blue-700 transition">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 7h18" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                View parts
              </a>

              @if(auth()->user() && in_array(auth()->user()->role, ['admin','coordinator']))
                <a href="{{ route('parts.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 border border-gray-200 rounded-lg text-sm text-gray-700 hover:bg-gray-100 transition">
                  Add part
                </a>
              @endif
            </div>
          </div>
        </div>

        <!-- subtle bottom gradient -->
        <div class="pointer-events-none absolute inset-x-0 bottom-0 h-8 bg-gradient-to-t from-white to-transparent"></div>
      </div>

      <!-- Sales Card -->
      @if(auth()->user() && in_array(auth()->user()->role, ['staff','admin','coordinator']))
      <div class="mt-8 relative overflow-hidden rounded-2xl shadow-lg border border-gray-100 bg-gradient-to-b from-white to-gray-50">
        <div class="p-6 flex items-start gap-4">
          <div class="flex-shrink-0">
            <div class="h-12 w-12 rounded-lg bg-green-50 flex items-center justify-center">
              <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 10h18M12 4v6m0 6v6" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </div>
          </div>

          <div class="flex-1">
            <div class="flex items-start justify-between">
              <div>
                <h3 class="text-lg font-semibold text-gray-800">Sales (POS)</h3>
                <p class="mt-1 text-sm text-gray-500">Record a sale, generate slip, and update inventory automatically.</p>
              </div>

              <div class="text-xs text-gray-400">POS</div>
            </div>

            <div class="mt-6">
            <div class="text-sm text-gray-600 mb-2">Quick actions</div>

            <div class="flex flex-wrap gap-2">
                <!-- Record Sale (primary) -->
                <a href="{{ route('sales.create') }}" 
                class="inline-flex items-center gap-2 px-3 py-2 bg-green-600 text-gray600 text-sm font-medium rounded-lg shadow-sm hover:bg-green-700 transition">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Record sale
                </a>

                <!-- Recent Receipts (secondary) -->
                <a href="{{ route('sales.index') }}" 
                class="inline-flex items-center gap-2 px-3 py-2 bg-white border border-gray-200 text-sm text-gray-700 rounded-lg shadow-sm hover:bg-gray-50 transition">
                <svg class="h-4 w-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Sales list
                </a>

                <!-- Prefill Sell (example: pass ?prefill=ID)
                <a href="{{ route('sales.create') }}" 
                class="inline-flex items-center gap-2 px-3 py-2 bg-white border border-gray-200 text-sm text-gray-700 rounded-lg shadow-sm hover:bg-gray-50 transition"
                title="Open empty sale form">
                <svg class="h-4 w-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 7h18M5 7v12a2 2 0 002 2h10a2 2 0 002-2V7" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Open sale form
                </a> -->

                <!-- (Optional) Quick add: Open modal or direct to create part
                @if(auth()->user() && in_array(auth()->user()->role, ['admin','coordinator']))
                <a href="{{ route('parts.create') }}" 
                    class="inline-flex items-center gap-2 px-3 py-2 bg-white border border-gray-200 text-sm text-gray-700 rounded-lg shadow-sm hover:bg-gray-50 transition">
                    <svg class="h-4 w-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16M4 12h16" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Quick add part
                </a>
                @endif -->
            </div>
            </div>


            <!-- <div class="mt-6 flex gap-3">
              <a href="{{ route('sales.create') }}" 
                 class="inline-flex items-center justify-center gap-2 flex-1 px-4 py-2 bg-green-600 text-dark rounded-lg shadow-sm hover:bg-green-700 transition">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Record sale
              </a>

              @if(auth()->user() && in_array(auth()->user()->role, ['admin','coordinator']))
                <a href="{{ route('sales.index') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 border border-gray-200 rounded-lg text-sm text-gray-700 hover:bg-gray-100 transition">
                  Sales list
                </a>
              @endif
            </div> -->
          </div>
        </div>

        <div class="pointer-events-none absolute inset-x-0 bottom-0 h-8 bg-gradient-to-t from-white to-transparent"></div>
      </div>
      @endif

    </div>

    <!-- Recent parts (single row under cards) -->
    <div class="mt-8 bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
      <div class="flex items-center justify-between mb-3">
        <h4 class="text-sm font-semibold text-gray-700">Recent parts</h4>
        <a href="{{ route('parts.index') }}" class="text-sm text-blue-600 hover:underline">View all</a>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
          <thead class="text-xs text-gray-500 uppercase">
            <tr>
              <th class="py-2 px-3">SKU</th>
              <th class="py-2 px-3">Name</th>
              <th class="py-2 px-3">Brand</th>
              <th class="py-2 px-3">Qty</th>
              @if(auth()->user() && in_array(auth()->user()->role, ['admin','coordinator']))
                <th class="py-2 px-3">Cost</th>
              @endif
              <th class="py-2 px-3">Sell</th>
            </tr>
          </thead>
          <tbody class="divide-y">
            @foreach(\App\Models\Part::latest()->limit(6)->get() as $p)
            <tr class="hover:bg-gray-50">
              <td class="py-2 px-3 text-xs text-gray-600">{{ $p->sku }}</td>
              <td class="py-2 px-3 font-medium text-gray-800">{{ Str::limit($p->name, 28) }}</td>
              <td class="py-2 px-3 text-gray-600">{{ $p->brand ?? '-' }}</td>
              <td class="py-2 px-3 text-gray-700">{{ $p->current_quantity }}</td>
              @if(auth()->user() && in_array(auth()->user()->role, ['admin','coordinator']))
                <td class="py-2 px-3 text-gray-700">{{ number_format($p->cost_price ?? 0, 2) }}</td>
              @endif
              <td class="py-2 px-3 text-gray-700">{{ number_format($p->sell_price ?? 0, 2) }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

  </div>
</div>
@endsection
