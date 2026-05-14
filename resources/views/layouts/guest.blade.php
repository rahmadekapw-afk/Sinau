<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-slate-50 selection:bg-accent selection:text-white">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative overflow-hidden">
            
            <!-- Decorative background elements -->
            <div class="absolute top-0 left-0 w-full h-96 bg-gradient-to-b from-primary/5 to-transparent -z-10"></div>
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-accent/5 rounded-full mix-blend-multiply filter blur-3xl opacity-50 -z-10"></div>
            <div class="absolute top-48 -left-24 w-72 h-72 bg-primary/5 rounded-full mix-blend-multiply filter blur-3xl opacity-50 -z-10"></div>



            <div class="w-full sm:max-w-md px-10 py-10 bg-white shadow-2xl shadow-gray-200/50 overflow-hidden rounded-3xl border border-gray-100 backdrop-blur-xl">
                <div class="flex items-center justify-center gap-4 mb-10 transform transition hover:scale-105 duration-300">
                    <a href="/" class="flex items-center gap-3 no-underline group">
                        <div class="w-12 h-12 flex items-center justify-center bg-transparent">
                            <img src="{{ asset('images/logo.png') }}" alt="Sinau Logo" class="w-full h-full object-contain" style="filter: invert(18%) sepia(84%) saturate(4683%) hue-rotate(337deg) brightness(87%) contrast(100%);">
                        </div>
                        <span class="text-3xl font-extrabold text-primary tracking-tight mt-1">Sinau.</span>
                    </a>
                </div>
                {{ $slot }}
            </div>
            
            <p class="mt-8 text-sm text-gray-500">
                &copy; {{ date('Y') }} Sinau. Hak Cipta Dilindungi.
            </p>
        </div>
    </body>
</html>
