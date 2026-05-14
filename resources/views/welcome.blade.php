<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Sinau — AI Learning Assistant</title>
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        
        <!-- Tailwind CSS (via CDN for guaranteed styling without Vite) -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Inter', 'sans-serif'],
                        },
                        colors: {
                            primary: '#0F172A',
                            accent: '#3B82F6'
                        }
                    }
                }
            }
        </script>
        <style>
            body { font-family: 'Inter', sans-serif; }
            .glass {
                background: rgba(255, 255, 255, 0.85);
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
                border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            }
        </style>
    </head>
    <body class="bg-gray-50 text-gray-900 antialiased selection:bg-accent selection:text-white">
        
        <!-- Navbar -->
        <nav class="fixed w-full z-50 glass">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo -->
                    <div class="flex-shrink-0 flex items-center gap-2">
                        <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center text-white font-bold text-lg shadow-sm">
                            S
                        </div>
                        <span class="font-bold text-xl tracking-tight text-primary">Sinau.</span>
                    </div>

                    <!-- Navigation Links -->
                    <div class="flex items-center gap-4">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="text-sm font-semibold text-gray-700 hover:text-accent transition">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-600 hover:text-primary transition hidden sm:block">Masuk</a>
                                
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="bg-primary hover:bg-black text-white px-5 py-2.5 rounded-lg text-sm font-medium transition shadow-sm hover:shadow-md">
                                        Daftar Gratis
                                    </a>
                                @endif
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <main class="pt-32 pb-16 sm:pt-40 sm:pb-24 lg:pb-32 px-4 mx-auto max-w-6xl text-center">
            <div class="inline-block mb-6 px-4 py-1.5 rounded-full bg-blue-50 border border-blue-100 text-blue-700 text-xs font-bold tracking-wide uppercase shadow-sm">
                Asisten Belajar AI
            </div>
            
            <h1 class="mx-auto max-w-4xl font-extrabold tracking-tight text-slate-900 text-5xl sm:text-6xl lg:text-7xl leading-tight">
                Belajar lebih cerdas, <br class="hidden sm:block">
                <span class="text-accent">bukan lebih keras.</span>
            </h1>
            
            <p class="mx-auto mt-6 max-w-2xl text-lg sm:text-xl text-slate-600 leading-relaxed">
                Sinau membantu kamu merangkum dokumen, mengelola jadwal tugas, dan meningkatkan produktivitas belajarmu dengan kekuatan Artificial Intelligence.
            </p>
            
            <div class="mt-10 flex justify-center gap-4 flex-col sm:flex-row">
                @auth
                    <a href="{{ url('/dashboard') }}" class="bg-primary hover:bg-black text-white px-8 py-4 rounded-xl text-base font-semibold transition shadow-lg hover:shadow-xl hover:-translate-y-0.5 flex items-center justify-center gap-2">
                        Buka Dashboard
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                @else
                    <a href="{{ route('register') }}" class="bg-primary hover:bg-black text-white px-8 py-4 rounded-xl text-base font-semibold transition shadow-lg hover:shadow-xl hover:-translate-y-0.5 flex items-center justify-center">
                        Mulai Sekarang
                    </a>
                    <a href="#features" class="bg-white hover:bg-gray-50 text-slate-900 border border-slate-200 px-8 py-4 rounded-xl text-base font-semibold transition shadow-sm hover:shadow flex items-center justify-center">
                        Pelajari Fitur
                    </a>
                @endauth
            </div>
        </main>

        <!-- Features Section -->
        <section id="features" class="py-24 bg-white border-t border-slate-100">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl font-extrabold text-slate-900 sm:text-4xl">Semua yang kamu butuhkan.</h2>
                    <p class="mt-4 text-lg text-slate-600 max-w-2xl mx-auto">Satu platform yang dirancang khusus untuk mempermudah hidup pelajar dan mahasiswa.</p>
                </div>

                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div class="p-8 rounded-2xl bg-slate-50 border border-slate-100 hover:border-slate-200 hover:shadow-sm transition">
                        <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center text-xl mb-6">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3">Manajemen Tugas</h3>
                        <p class="text-slate-600 leading-relaxed">Catat tugas, atur tenggat waktu, dan biarkan AI memecahnya menjadi langkah-langkah kecil yang mudah diselesaikan.</p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="p-8 rounded-2xl bg-slate-50 border border-slate-100 hover:border-slate-200 hover:shadow-sm transition">
                        <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-xl flex items-center justify-center text-xl mb-6">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3">Chat PDF dengan AI</h3>
                        <p class="text-slate-600 leading-relaxed">Punya jurnal atau modul tebal? Unggah dan tanyakan langsung inti materinya kepada AI tanpa perlu membaca semuanya.</p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="p-8 rounded-2xl bg-slate-50 border border-slate-100 hover:border-slate-200 hover:shadow-sm transition">
                        <div class="w-12 h-12 bg-green-100 text-green-600 rounded-xl flex items-center justify-center text-xl mb-6">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3">Konversi Dokumen</h3>
                        <p class="text-slate-600 leading-relaxed">Ubah file dokumenmu dari satu format ke format lain dengan mudah dan cepat, langsung dari dashboard belajarmu.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-white py-10 border-t border-slate-100">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 bg-primary rounded bg-primary flex items-center justify-center text-white font-bold text-xs">S</div>
                    <span class="font-bold text-slate-900 text-lg">Sinau.</span>
                </div>
                <p class="text-sm text-slate-500 font-medium">
                    &copy; {{ date('Y') }} Sinau AI. Dirancang untuk pelajar.
                </p>
                <div class="flex gap-6">
                    <a href="#" class="text-sm font-medium text-slate-500 hover:text-slate-900 transition">Privasi</a>
                    <a href="#" class="text-sm font-medium text-slate-500 hover:text-slate-900 transition">Ketentuan</a>
                </div>
            </div>
        </footer>

    </body>
</html>
