@extends('layouts.erp')

@section('title', 'Receipt')

@section('content')

<div class="container-fluid py-4">

    {{-- HEADER ACTION BAR --}}
    <div class="d-flex justify-content-between align-items-center mb-4 no-print">

        <div>
            <h3 class="mb-0 fw-bold">Receipt</h3>
            <small class="text-muted">Slip #{{ $sale->slip_no }}</small>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route('sales.index') }}" class="btn btn-light border rounded-3">
                Back
            </a>

            <a href="{{ route('sales.invoice', $sale) }}" class="btn btn-outline-success rounded-3">
                Invoice
            </a>

            <button class="btn btn-primary rounded-3" id="print-receipt">
                Print Receipt
            </button>

        </div>

    </div>

    {{-- ADMIN NOTES --}}
    @if($sale->notes)
        <div class="alert alert-light border no-print mb-3">
            <strong>Notes (admin only):</strong> {{ $sale->notes }}
        </div>
    @endif

    {{-- RECEIPT WRAPPER --}}
    <div id="receipt-wrapper" class="mx-auto" style="max-width:420px;">

        <div id="receipt"
             class="p-3 bg-white border rounded-3"
             style="font-family: Arial, Helvetica, sans-serif;">

            {{-- HEADER --}}
            <div class="text-center mb-2">

                <h4 class="mb-1 fw-bold">AutoParts By Saad</h4>

                <div class="small text-muted">
                    {{ config('app.address', '123 Grand Avenue NY') }}<br>
                    {{ config('app.phone', '0123-456789') }}
                </div>

                <hr class="my-2">

            </div>

            {{-- META --}}
            <div class="d-flex justify-content-between small mb-1">
                <div>Slip: <strong>{{ $sale->slip_no }}</strong></div>
                <div>{{ $sale->created_at->format('Y-m-d H:i') }}</div>
            </div>

            <div class="d-flex justify-content-between small mb-2">
                <div>Cashier: <strong>{{ $sale->seller->name ?? 'N/A' }}</strong></div>
                <div>Customer: <strong>{{ $sale->customer_name ?? 'Walk-in' }}</strong></div>
            </div>

            {{-- ITEMS --}}
            <table class="table table-sm table-borderless mb-2 small">

                <thead class="border-bottom">
                    <tr>
                        <th>Item</th>
                        <th class="text-end">Qty</th>
                        <th class="text-end">Price</th>
                        <th class="text-end">Total</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($sale->items as $it)

                        <tr>

                            <td>
                                <div class="fw-semibold">
                                    {{ Str::limit($it->part->name, 28) }}
                                </div>
                                <div class="text-muted" style="font-size:11px;">
                                    SKU: {{ $it->part->sku }}
                                </div>
                            </td>

                            <td class="text-end">{{ $it->quantity }}</td>
                            <td class="text-end">{{ number_format($it->sold_price,2) }}</td>
                            <td class="text-end">{{ number_format($it->line_total,2) }}</td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

            <hr class="my-2">

            {{-- TOTALS --}}
            @php
                $subtotal = $sale->subtotal ?? $sale->items->sum('line_total');
                $discount = $sale->discount_amount ?? 0;
                $tax = $sale->tax_amount ?? 0;
                $grand = $sale->total_amount ?? ($subtotal - $discount + $tax);
            @endphp

            <div class="d-flex justify-content-between small">
                <span>Subtotal</span>
                <strong>{{ number_format($subtotal,2) }}</strong>
            </div>

            @if($discount > 0)
                <div class="d-flex justify-content-between small text-danger">
                    <span>Discount</span>
                    <span>-{{ number_format($discount,2) }}</span>
                </div>
            @endif

            @if($tax > 0)
                <div class="d-flex justify-content-between small">
                    <span>Tax</span>
                    <span>{{ number_format($tax,2) }}</span>
                </div>
            @endif

            <hr class="my-2">

            <div class="d-flex justify-content-between fw-bold fs-6">
                <span>Total</span>
                <span>{{ number_format($grand,2) }}</span>
            </div>

            <div class="text-center mt-3 small text-muted">
                Thank you for your purchase!
            </div>

        </div>

    </div>

</div>

{{-- PRINT --}}
<style>
@media print {
    .no-print { display:none !important; }

    body {
        background:#fff !important;
    }

    #receipt-wrapper {
        max-width: 80mm !important;
        width: 80mm !important;
    }

    @page {
        margin: 3mm;
    }
}
</style>

<script>
document.getElementById('print-receipt').addEventListener('click', function () {
    const content = document.getElementById('receipt').outerHTML;

    const win = window.open('', '_blank');
    win.document.write(`
        <html>
        <head>
            <title>Receipt</title>
            <style>
                body { font-family: Arial; padding: 8px; }
                table { width:100%; border-collapse: collapse; }
                th, td { padding: 4px 0; }
                hr { border:none; border-top:1px dashed #ccc; margin:10px 0; }
            </style>
        </head>
        <body>${content}</body>
        </html>
    `);

    win.document.close();
    win.focus();

    setTimeout(() => win.print(), 300);
});
</script>

@endsection