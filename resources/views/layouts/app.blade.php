<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    </head>
    <body class="font-sans antialiased" x-data="{ mobileMenuOpen: false, sidebarCollapsed: localStorage.getItem('sidebar_collapsed') === 'true' }">
        <div class="min-h-screen bg-gray-50 flex overflow-hidden">
            <!-- Sidebar -->
            @include('layouts.sidebar')

            <!-- Main Content -->
            <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
                
                <!-- Mobile Header -->
                <header class="md:hidden bg-white border-b border-gray-100 flex items-center justify-between px-4 h-16 shrink-0 z-30">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-8 h-8 object-contain" style="filter: invert(15%) sepia(95%) saturate(3000%) hue-rotate(350deg) brightness(85%) contrast(100%);">
                        <span class="font-bold text-xl text-primary">Sinau</span>
                    </div>
                    <button @click="mobileMenuOpen = true" class="p-2 rounded-lg text-gray-500 hover:bg-gray-50">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                </header>

                <!-- Page Heading -->
                @isset($header)
                    <header class="bg-white shadow z-10 relative">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main class="flex-1 overflow-y-auto">
                    {{ $slot }}
                </main>
            </div>
        <x-chatbot />
    </body>
</html>
