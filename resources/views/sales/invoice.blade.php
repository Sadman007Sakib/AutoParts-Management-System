@extends('layouts.erp')

@section('title', 'Invoice')

@section('content')

    <div class="invoice-page">

        <div class="mb-3 no-print d-flex justify-content-end gap-2">

            <a href="{{ route('sales.index') }}" class="btn btn-secondary">
                Back
            </a>

            <button onclick="window.print()" class="btn btn-primary">
                Print Invoice
            </button>
        </div>

        <!-- HEADER -->
        <div class="invoice-header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-1">Invoice</h5>
                <div><strong>Invoice Number:</strong> INV-{{ $sale->slip_no }}</div>
            </div>
            <div class="text-end">
                <h5 class="mb-1">Invoice Amount</h5>
                <h4 class="text-primary">${{ number_format($sale->total_amount, 2) }}</h4>
            </div>
        </div>

        <hr>

        <!-- FROM / TO -->
        <div class="row mt-3 mb-3">

            <!-- FROM -->
            <div class="col-6 position-relative">
                <h6 class="section-title">FROM</h6>
                <!-- WATERMARK -->
                <img src="{{ asset('images/logo.png') }}" class="invoice-watermark" alt="Watermark">

                <!-- BRAND HEADER -->
                <div class="brand-box">

                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="brand-logo">

                    <div>
                        <h3 class="brand-title">
                            AutoParts By Saad
                        </h3>

                        <div class="brand-sub">
                            Premium Auto Parts & Services
                        </div>
                    </div>

                </div>

                <!-- INFO -->
                <div class="box mt-3">
                    {{ config('app.address') }}<br>

                    Email: crownautoparts21@gmail.com<br>

                    Phone: {{ config('app.phone') }}<br>

                    Website: www.autopartsbysaad.com
                </div>

            </div>

            <!-- TO -->
            <div class="col-6">
                <h6 class="section-title">TO</h6>
                <div class="box">
                    Customer Name: {{ $sale->customer_name ?? 'N/A' }}<br>
                    Payment Date: {{ $sale->created_at }}<br>
                    Delivery Status: <span class="text-danger">{{ $sale->delivery_status }}</span><br>
                    Payment: COD<br>
                    Invoice Date: {{ $sale->created_at->format('Y-m-d') }}
                </div>
            </div>

        </div>

        <!-- TABLE -->
        <table class="invoice-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>PRODUCT DESCRIPTION</th>
                    <th>UNIT PRICE</th>
                    <th>QTY</th>
                    <th>TOTAL</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($sale->items as $index => $it)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <strong>{{ $it->part->name }}</strong><br>
                            <small>Part#: {{ $it->part->sku }}</small>
                        </td>
                        <td>${{ number_format($it->sold_price, 2) }}</td>
                        <td>{{ $it->quantity }}</td>
                        <td>${{ number_format($it->line_total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- TOTAL BOX -->
        @php
            $subtotal = $sale->subtotal ?? $sale->items->sum('line_total');
            $tax = $sale->tax_amount ?? 0;
            $discount = $sale->discount_amount ?? 0;
            $grand = $sale->total_amount ?? $subtotal - $discount + $tax;
        @endphp

        <div class="d-flex justify-content-end mt-4">
            <div class="total-box">

                <div class="d-flex justify-content-between">
                    <span>Total Amount</span>
                    <span>${{ number_format($subtotal, 2) }}</span>
                </div>

                <div class="d-flex justify-content-between">
                    <span>Tax (13%)</span>
                    <span>${{ number_format($tax, 2) }}</span>
                </div>

                <div class="d-flex justify-content-between">
                    <span>Discount</span>
                    <span>${{ number_format($discount, 2) }}</span>
                </div>

                <hr>

                <div class="d-flex justify-content-between grand">
                    <span>Grand Total</span>
                    <span>${{ number_format($grand, 2) }}</span>
                </div>

            </div>
        </div>

        <!-- SIGNATURE AREA -->
        <div class="row mt-5">

            <div class="col-6 text-center">
                <div class="signature-line"></div>
                <strong>Authorized Signature</strong>
            </div>
            <div class="col-6 text-center">

                <div class="qr-stamp-box">

                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=140x140&data={{ url()->current() }}"
                        alt="QR Code" class="qr-stamp-image">

                </div>

                <div class="qr-stamp-text">
                    Scan to Verify Invoice
                </div>

            </div>

        </div>

        <!-- FOOTER -->
        <div class="footer text-center mt-4">
            <div class="thank-box">Thanks for Purchasing</div>
            <small>Please Note: Returns accepted within 15 days.</small>
        </div>
        <!-- <div class="footer text-center mt-2">Thank you for your business</div> -->
    </div>

    </div>

    <!-- STYLE -->
    <style>
        @media print {

            .no-print {
                display: none !important;
            }

            body {
                background: white;
            }

            #invoice-wrapper {
                width: 100%;
                max-width: 100%;
            }

        }

        body {
            background: #f8f9fa;
        }

        /* HEADER */
        .invoice-header {
            border-bottom: 2px solid #ccc;
            padding-bottom: 10px;
        }

        /* SECTION TITLES */
        .section-title {
            font-weight: 600;
            border-bottom: 2px solid #ddd;
            padding-bottom: 4px;
        }

        /* BOX */
        .box {
            font-size: 13px;
            margin-top: 5px;
            line-height: 1.6;
        }

        /* TABLE */
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        .invoice-table th {
            border-bottom: 2px solid #333;
            text-align: left;
            padding: 8px;
        }

        .invoice-table td {
            border-bottom: 1px solid #ddd;
            padding: 8px;
        }

        /* TOTAL BOX */
        .total-box {
            width: 280px;
            border: 1px solid #ccc;
            padding: 12px;
            font-size: 14px;
        }

        .total-box .grand {
            font-weight: bold;
            color: red;
            font-size: 16px;
        }

        /* FOOTER */
        .footer {
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }

        .thank-box {
            border: 2px solid green;
            padding: 8px;
            color: green;
            font-weight: 600;
            margin-bottom: 5px;
        }

        /* PRINT */
        /* @media print {
      body {
        background: #fff;
      }
      #invoice-wrapper {
        width: 100%;
      }
    } */


        @media print {

            /* Hide everything except invoice */
            nav,
            .navbar,
            .sidebar,
            footer,
            .no-print,
            .btn,
            aside {
                display: none !important;
            }

            body {
                background: white !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            /* Remove container spacing */
            .container,
            .container-fluid {
                width: 100% !important;
                max-width: 100% !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            /* Print only invoice */
            #invoice-wrapper {
                width: 100% !important;
                max-width: 100% !important;
                margin: 0 auto !important;
                padding: 20px !important;
            }

            /* Preserve colors */
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

        }

        /* QR STAMP */
        .qr-stamp-box {
            width: 150px;
            height: 150px;
            /* border:2px dashed #c1121f; */
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: auto;
            padding: 10px;
            background: #fff;
        }

        .qr-stamp-image {
            width: 80px;
            height: 80px;
            object-fit: contain;
        }

        .qr-stamp-text {
            margin-top: 8px;
            font-size: 12px;
            color: #666;
            font-weight: 600;
        }



        /* BRAND AREA */
        .brand-box {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .brand-logo {
            width: 75px;
            height: 75px;
            object-fit: contain;
        }

        .brand-title {
            margin: 0;
            font-weight: 800;
            color: #c1121f;
            letter-spacing: 0.5px;
        }

        .brand-sub {
            font-size: 13px;
            color: #555;
        }

        /* WATERMARK */
        .invoice-watermark {
            position: absolute;
            top: 20px;
            left: 40px;
            width: 180px;
            opacity: 0.05;
            z-index: 0;
        }

        /* SIGNATURE */
        .signature-line {
            width: 220px;
            border-top: 1px solid #000;
            margin: 0 auto 8px;
            margin-top: 50px;
        }

        /* STAMP */
        .stamp-box {
            width: 140px;
            height: 140px;
            border: 3px dashed #000000;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: auto;
            color: #c1121f;
            font-weight: bold;
            transform: rotate(-15deg);
            opacity: 0.7;
        }

        /* AUTOMOTIVE THEME */
        .invoice-header h5 {
            color: #111;
        }

        .text-primary {
            color: #c1121f !important;
        }

        .invoice-table thead {
            background: #111;
            color: #fff;
        }

        .invoice-table th {
            padding: 10px;
        }

        .thank-box {
            border: 2px solid #2fc112;
            color: #c1121f;
            font-weight: 700;
        }
    </style>

@endsection
