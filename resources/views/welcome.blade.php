<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AutoParts | Welcome</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased">

    <!-- Background Section -->
    <div class="relative flex items-center justify-center min-h-screen bg-cover bg-center" 
         style="background-image: url('{{ asset('images/landing-bg.png') }}');">

        <!-- Overlay -->
        <div class="absolute inset-0 bg-black bg-opacity-60"></div>

        <!-- Content -->
        <div class="relative z-10 text-center text-white px-6">
            <h1 class="text-4xl md:text-6xl font-extrabold tracking-wide">
                Welcome to <span class="text-blue-400">AutoParts</span>
            </h1>

            <p class="mt-4 text-lg text-gray-200 max-w-2xl mx-auto">
                Streamline your automotive parts management — track inventory, manage sales, and grow efficiently.
            </p>

            <div class="mt-8 space-x-4">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" 
                           class="inline-block px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition">
                           Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" 
                           class="inline-block px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg shadow-md transition">
                           Login
                        </a>

                        <a href="{{ route('register') }}" 
                           class="inline-block px-6 py-3 bg-gray-100 text-gray-800 font-semibold rounded-lg shadow-md hover:bg-gray-200 transition">
                           Register
                        </a>
                    @endauth
                @endif
            </div>
        </div>
    </div>

</body>
</html>
