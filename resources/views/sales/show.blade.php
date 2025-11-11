@extends('layouts.app')

@section('title', 'Receipt')

@section('content')
<div class="container py-4">
  <div class="d-flex justify-content-end mb-3 no-print">
      {{-- Notes are intentionally not shown on the printed receipt --}}
       {{-- If you still want to keep notes in admin UI (not printed), you can show it inside a no-print block: --}}
        @if($sale->notes)
          <div class="no-print" style="margin-top:8px; font-size:12px; color:#444;">
            <strong>Notes (admin-only):</strong> {{ $sale->notes }}
          </div>
        @endif
        <div>-</div>
    <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary">Back</a>
    <button class="btn btn-primary ms-2" id="print-receipt">Print Receipt</button>
  </div>

  <div id="receipt-wrapper" class="mx-auto" style="max-width:420px;">
    <!-- receipt card -->
    <div id="receipt" class="p-3 border" style="background:#fff; font-family: Arial, Helvetica, sans-serif;">
      <!-- header -->
      <div class="text-center mb-2">
        <h3 style="margin:0; font-size:18px; letter-spacing:0.5px;">AutoParts By Saad</h3>
        <div style="font-size:12px; color:#555; line-height:1.2;">
          {{ config('app.address', '123 Grand Avenue NY USBD') }}<br>
          {{ config('app.phone', 'Phone: 0123-456789') }}
        </div>
        <hr style="border:none;border-top:1px dashed #ccc;margin:8px 0;">
      </div>

      <!-- meta -->
      <div style="font-size:12px; display:flex; justify-content:space-between; margin-bottom:6px;">
        <div>Slip: <strong>{{ $sale->slip_no }}</strong></div>
        <div>{{ $sale->created_at->format('Y-m-d H:i') }}</div>
      </div>
      <div style="font-size:12px; display:flex; justify-content:space-between; margin-bottom:8px;">
        <div>Cashier: <strong>{{ $sale->seller->name ?? 'N/A' }}</strong></div>
        <div>Customer: <strong>{{ $sale->customer_name ?? 'Walk-in' }}</strong></div>
      </div>

      <table style="width:100%; font-size:12px; border-collapse:collapse; margin-top:6px;">
        <thead>
          <tr>
            <th style="text-align:left; font-size:11px; padding-bottom:6px;">Item</th>
            <th style="text-align:right; font-size:11px; padding-bottom:6px; width:50px;">Qty</th>
            <th style="text-align:right; font-size:11px; padding-bottom:6px; width:70px;">Price</th>
            <th style="text-align:right; font-size:11px; padding-bottom:6px; width:80px;">Total</th>
          </tr>
        </thead>
        <tbody>
          @foreach($sale->items as $it)
            <tr>
              <td style="padding:4px 0; vertical-align:top;">
                <div style="font-weight:600; font-size:12px;">{{ Str::limit($it->part->name, 28) }}</div>
                <div style="font-size:10px; color:#666;">SKU: {{ $it->part->sku }}</div>
              </td>
              <td style="text-align:right; vertical-align:top; padding-left:6px;">{{ $it->quantity }}</td>
              <td style="text-align:right; vertical-align:top; padding-left:6px;">{{ number_format($it->sold_price,2) }}</td>
              <td style="text-align:right; vertical-align:top; padding-left:6px;">{{ number_format($it->line_total,2) }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>

      <hr style="border:none;border-top:1px dashed #ccc;margin:10px 0;">

      {{-- Subtotal (use stored subtotal if available, else sum lines) --}}
      @php
        $computedSubtotal = $sale->subtotal ?? $sale->items->sum('line_total');
        $discountAmount = $sale->discount_amount ?? 0;
        $discountValue = $sale->discount_value ?? null;
        $discountType = $sale->discount_type ?? null;
        $taxAmount = $sale->tax_amount ?? 0;
        $taxRate = $sale->tax_rate ?? config('shop.tax_rate', 0);
        $grandTotal = $sale->total_amount ?? ($computedSubtotal - $discountAmount + $taxAmount);
      @endphp

      <div style="font-size:13px; display:flex; justify-content:space-between; margin-bottom:4px;">
        <div>Subtotal</div>
        <div><strong>{{ number_format($computedSubtotal, 2) }}</strong></div>
      </div>

      {{-- Discount line (show only if discount > 0) --}}
      @if(($discountAmount ?? 0) > 0)
        <div style="font-size:12px; display:flex; justify-content:space-between; margin-bottom:4px;">
          <div>
            Discount
            @if($discountType === 'percent' && $discountValue)
              <small style="display:block; color:#666;">({{ number_format($discountValue,2) }}%)</small>
            @elseif($discountType === 'fixed' && $discountValue)
              <small style="display:block; color:#666;">(Fixed)</small>
            @endif
          </div>
          <div>-{{ number_format($discountAmount, 2) }}</div>
        </div>
      @endif

      {{-- Tax line (if any) --}}
      @if(($taxAmount ?? 0) > 0)
        <div style="font-size:12px; display:flex; justify-content:space-between; margin-bottom:4px;">
          <div>Tax ({{ number_format($taxRate, 2) }}%)</div>
          <div>{{ number_format($taxAmount, 2) }}</div>
        </div>
      @endif

      <div style="font-size:16px; font-weight:700; display:flex; justify-content:space-between; margin-top:6px;">
        <div>Total</div>
        <div>{{ number_format($grandTotal,2) }}</div>
      </div>

      <div style="text-align:center; font-size:11px; color:#666; margin-top:12px;">
        Thank you for your purchase!
      </div>

      <div style="text-align:center; font-size:10px; color:#999; margin-top:6px;">
        Powered by Mohammad Sadman Chowdhury
      </div>
    </div>
  </div>
</div>

<!-- print styles -->
<style>
  /* Hide non-print elements */
  @media print {
    .no-print { display:none !important; }
    body, html {
      background: #fff;
    }
    /* Make receipt narrow for thermal: default to 80mm width */
    #receipt-wrapper { max-width: 80mm !important; width: 80mm !important; }
    /* remove margins */
    @page { margin: 3mm; }
    body { margin: 0; padding: 0; -webkit-print-color-adjust: exact; }
  }

  /* On-screen styling to match receipt look */
  #receipt { box-shadow:none; border:1px solid #eee; background:#fff; }
  .small { font-size:12px; color:#666; }
</style>

<script>
document.getElementById('print-receipt').addEventListener('click', function () {
  const content = document.getElementById('receipt').outerHTML;
  const css = `
    <style>
      body{font-family:Arial,Helvetica,sans-serif; color:#111; padding:8px;}
      table{width:100%; border-collapse:collapse;}
      th,td{padding:6px 0; text-align:left;}
      hr{border:none;border-top:1px dashed #ccc;margin:10px 0;}
    </style>
  `;
  const win = window.open('', '_blank', 'toolbar=0,location=0,menubar=0');
  win.document.open();
  win.document.write('<html><head><title>Receipt</title>' + css + '</head><body>' + content + '</body></html>');
  win.document.close();
  win.focus();
  setTimeout(() => { win.print(); /* win.close(); */ }, 300);
});
</script>
@endsection
