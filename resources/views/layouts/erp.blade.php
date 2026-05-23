<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'ERP System')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            background: #f4f6f9;
        }

        .erp-sidebar {
            width: 240px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: #111827;
            color: white;
            padding: 20px;
            z-index: 1050;
        }

        .erp-sidebar a {
            display: block;
            padding: 10px;
            color: #cbd5e1;
            text-decoration: none;
            border-radius: 6px;
            margin-bottom: 5px;
        }

        .erp-sidebar a:hover {
            background: #1f2937;
            color: white;
        }

        .erp-main {
            margin-left: 260px;
            padding: 20px;
        }

        .erp-topbar {
            background: white;
            padding: 12px 20px;
            margin-bottom: 20px;
            border-radius: 10px;
            display: flex;
            justify-content: space-between;
        }

        .sidebar-logout {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border: none;
            border-radius: 6px;
            background: #dc2626;
            color: white;
            text-align: left;
        }

        .sidebar-logout:hover {
            background: #991b1b;
        }

        /* IMPORTANT PRINT RULE */
        @media print {

            .erp-sidebar,
            .erp-topbar,
            .sidebar-logout {
                display: none !important;
            }

            body {
                background: white !important;
            }

            .erp-main {
                margin: 0 !important;
                padding: 0 !important;
            }

            /* show only invoice */
            .invoice-page {
                width: 100% !important;
                position: absolute;
                left: 0;
                top: 0;
                padding: 20px;
            }

            body * {
                visibility: hidden !important;
            }

            .invoice-page,
            .invoice-page * {
                visibility: visible !important;
            }
        }
    </style>
</head>

<body>

    <!-- SIDEBAR -->
    <div class="erp-sidebar">

        <h4 class="mb-4">AUTO ERP</h4>

        <a href="{{ route('home') }}">📊 Dashboard</a>
        <a href="{{ route('parts.index') }}">📦 Inventory</a>
        <a href="{{ route('sales.index') }}">🧾 Sales</a>
        <a href="{{ route('sales.create') }}">➕ New Sale</a>
        <a href="{{ route('admin.reports.daily') }}">📈 Analytics</a>
        <a href="#">🏭 Warehouse -x- </a>
        <a href="#">👥 Customers -x-</a>
        <a href="#">🚚 Suppliers -x-</a>

        @if (auth()->user()->role === 'admin')
            <hr style="border-color:#374151;">
            <a href="{{ route('admin.users.index') }}">👤 Users</a>
        @endif

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="sidebar-logout">
                🚪 Logout
            </button>
        </form>

    </div>

    <!-- MAIN -->
    <div class="erp-main">

        <div class="erp-topbar d-flex justify-content-between align-items-center">

            <!-- LEFT -->
            <div>
                @yield('title')
            </div>

            <!-- RIGHT -->
            <div class="d-flex align-items-center gap-3">

                <!-- USER DROPDOWN (Jetstream - still valid) -->
                <x-dropdown align="right" width="48">

                    <x-slot name="trigger">
                        <button class="btn btn-light btn-sm d-flex align-items-center gap-2">

                            <span>{{ Auth::user()->name }}</span>

                            <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>

                        </button>
                    </x-slot>

                    <x-slot name="content">

                        <x-dropdown-link :href="route('profile.edit')">
                            My Profile
                        </x-dropdown-link>

                    </x-slot>

                </x-dropdown>

            </div>

        </div>

        <!-- CONTENT -->
        @yield('content')

    </div>

</body>
</html>