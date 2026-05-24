<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sinau — Asisten Belajar AI</title>
    <meta name="description" content="Platform belajar cerdas berbasis AI. Tanya jawab, kuis otomatis, dan pembelajaran adaptif untuk pelajar Indonesia.">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        .glass-nav {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased selection:bg-accent selection:text-white">

    <!-- NAVBAR -->
    <nav id="navbar" class="fixed w-full z-50 glass-nav transition-all duration-300">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-24">
                <!-- Logo -->
                <a href="/" class="flex-shrink-0 flex items-center gap-4 group">
                    <div class="w-20 h-20 rounded-xl flex items-center justify-center bg-transparent group-hover:scale-105 transition-transform">
                        <img src="{{ asset('images/logo.png') }}" alt="Sinau Logo" class="w-full h-full object-contain" style="filter: invert(15%) sepia(95%) saturate(3000%) hue-rotate(350deg) brightness(85%) contrast(100%);">
                    </div>
                    <span class="font-extrabold text-4xl tracking-tight text-[#B91C1C] mt-1">Sinau.</span>
                </a>

                <!-- Nav Links -->
                <div class="hidden md:flex items-center gap-8">
                    <a href="#features" class="text-sm font-medium text-slate-600 hover:text-accent transition">Fitur</a>
                    <a href="#how" class="text-sm font-medium text-slate-600 hover:text-accent transition">Cara Kerja</a>
                </div>

                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="bg-[#B91C1C] hover:opacity-90 text-white px-5 py-2.5 rounded-lg text-sm font-bold transition shadow-sm">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-bold text-slate-600 hover:text-[#B91C1C] transition hidden sm:block">Masuk</a>
                        <a href="{{ route('register') }}" class="bg-[#B91C1C] hover:opacity-90 text-white px-5 py-2.5 rounded-lg text-sm font-bold transition shadow-sm">Daftar Gratis</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- HERO -->
    <main class="pt-32 pb-16 sm:pt-40 sm:pb-24 lg:pb-32 px-4 mx-auto max-w-6xl">
        <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-8">
            <!-- Text Content -->
            <div class="flex-1 text-center lg:text-left">
                <div class="inline-flex items-center gap-2 mb-6 px-4 py-1.5 rounded-full bg-accent/5 border border-accent/10 text-accent text-xs font-bold tracking-wide shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-accent animate-pulse"></span>
                    Powered by OpenRouter & Ollama
                </div>
                
                <h1 class="font-extrabold tracking-tight text-slate-900 text-5xl sm:text-6xl lg:text-7xl leading-[1.1]">
                    Belajar Lebih Cerdas <br class="hidden lg:block">
                    dengan <span class="text-[#B91C1C]">Sinau</span>
                </h1>
                
                <p class="mt-6 text-lg sm:text-xl text-slate-600 leading-relaxed max-w-2xl mx-auto lg:mx-0">
                    Sinau adalah platform belajar berbasis AI yang membantu pelajar Indonesia memahami materi dengan cara yang interaktif, personal, dan menyenangkan.
                </p>
                
                <div class="mt-10 flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4">
                    @auth
                        <a href="{{ route('chat.index') }}" class="bg-accent hover:opacity-90 text-white px-8 py-4 rounded-xl text-base font-semibold transition shadow-lg hover:-translate-y-0.5 flex items-center gap-2">
                            🤖 Mulai Belajar
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="bg-accent hover:opacity-90 text-white px-8 py-4 rounded-xl text-base font-semibold transition shadow-lg hover:-translate-y-0.5 flex items-center">
                            Mulai Gratis →
                        </a>
                        <a href="#features" class="bg-white hover:bg-accent/5 text-accent border border-accent/20 px-8 py-4 rounded-xl text-base font-semibold transition shadow-sm">
                            Pelajari Lebih
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Hero Visual (Chat Mockup) -->
            <div class="flex-1 w-full max-w-lg lg:max-w-none relative">
                <!-- Decorative blob -->
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-72 h-72 bg-red-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20"></div>
                
                <div class="relative bg-white rounded-2xl shadow-2xl border border-slate-100 overflow-hidden transform transition hover:scale-[1.02] duration-300">
                    <!-- Mac-like Header -->
                    <div class="bg-slate-50 px-4 py-3 border-b border-slate-100 flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-red-400"></div>
                        <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                        <div class="w-3 h-3 rounded-full bg-green-400"></div>
                        <div class="ml-4 text-xs font-medium text-slate-400">Sinau AI Chat</div>
                    </div>
                    <!-- Chat Body -->
                    <div class="p-6 space-y-6 text-sm">
                        <!-- User Msg -->
                        <div class="flex gap-4">
                            <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-lg flex-shrink-0">👤</div>
                            <div class="bg-slate-100 text-slate-800 p-4 rounded-2xl rounded-tl-none leading-relaxed">
                                Jelaskan rumus luas lingkaran dengan analogi sederhana
                            </div>
                        </div>
                        <!-- AI Msg -->
                        <div class="flex gap-4">
                            <div class="w-8 h-8 rounded-full bg-accent/10 flex items-center justify-center text-lg flex-shrink-0">🤖</div>
                            <div class="bg-accent/5 text-accent border border-accent/10 p-4 rounded-2xl rounded-tr-none leading-relaxed">
                                Bayangkan kamu punya pizza bulat 🍕 Luas lingkaran = π × r² artinya kamu menghitung berapa banyak "kotak kecil" yang bisa masuk ke dalam pizza itu. <strong>r</strong> adalah jarak dari tengah pizza ke pinggirnya, dan <strong>π ≈ 3.14</strong> adalah angka ajaib yang selalu muncul di lingkaran!
                            </div>
                        </div>
                        <!-- User Msg -->
                        <div class="flex gap-4">
                            <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-lg flex-shrink-0">👤</div>
                            <div class="bg-slate-100 text-slate-800 p-4 rounded-2xl rounded-tl-none leading-relaxed">
                                Wah paham! Kalau r = 7cm, luasnya berapa?
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- FEATURES -->
    <section id="features" class="py-24 bg-white border-t border-slate-100">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <div class="text-primary font-semibold tracking-wide uppercase text-sm mb-3">Fitur Unggulan</div>
                <h2 class="text-3xl font-extrabold text-primary sm:text-4xl">Semua yang Kamu Butuhkan untuk Belajar</h2>
                <p class="mt-4 text-lg text-slate-600 max-w-2xl mx-auto">Teknologi AI terbaru dipadukan dengan pendekatan edukatif yang menyenangkan.</p>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="p-8 rounded-2xl bg-white border border-accent/10 hover:border-accent/30 hover:shadow-lg transition group">
                    <div class="w-12 h-12 bg-accent text-white shadow-md rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Tanya Jawab AI</h3>
                    <p class="text-slate-600 leading-relaxed">Tanyakan materi apapun dan dapatkan penjelasan yang mudah dipahami, lengkap dengan contoh dan analogi.</p>
                </div>
                <!-- Feature 2 -->
                <div class="p-8 rounded-2xl bg-white border border-accent/10 hover:border-accent/30 hover:shadow-lg transition group">
                    <div class="w-12 h-12 bg-accent text-white shadow-md rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Multi Mata Pelajaran</h3>
                    <p class="text-slate-600 leading-relaxed">Matematika, IPA, Bahasa, Sejarah, dan banyak lagi — semua dalam satu platform untuk kebutuhanmu.</p>
                </div>
                <!-- Feature 3 -->
                <div class="p-8 rounded-2xl bg-white border border-accent/10 hover:border-accent/30 hover:shadow-lg transition group">
                    <div class="w-12 h-12 bg-accent text-white shadow-md rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Percakapan Kontekstual</h3>
                    <p class="text-slate-600 leading-relaxed">AI mengingat konteks percakapanmu, sehingga kamu bisa bertanya lanjutan tanpa harus mengulang konteks.</p>
                </div>
                <!-- Feature 4 -->
                <div class="p-8 rounded-2xl bg-white border border-accent/10 hover:border-accent/30 hover:shadow-lg transition group">
                    <div class="w-12 h-12 bg-accent text-white shadow-md rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Riwayat Belajar</h3>
                    <p class="text-slate-600 leading-relaxed">Semua sesi belajarmu tersimpan rapi. Kembali kapan saja untuk me-review materi dan memperdalam pemahaman.</p>
                </div>
                <!-- Feature 5 -->
                <div class="p-8 rounded-2xl bg-white border border-accent/10 hover:border-accent/30 hover:shadow-lg transition group">
                    <div class="w-12 h-12 bg-accent text-white shadow-md rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Bahasa Indonesia</h3>
                    <p class="text-slate-600 leading-relaxed">AI berbicara dalam Bahasa Indonesia yang alami, ramah, dan sangat mudah dimengerti oleh pelajar.</p>
                </div>
                <!-- Feature 6 -->
                <div class="p-8 rounded-2xl bg-white border border-accent/10 hover:border-accent/30 hover:shadow-lg transition group relative overflow-hidden">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-accent/5 rounded-full mix-blend-multiply opacity-50 group-hover:scale-150 transition-transform"></div>
                    <div class="w-12 h-12 bg-accent text-white shadow-md rounded-xl flex items-center justify-center mb-6 relative z-10">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3 relative z-10">Quiz Generator</h3>
                    <p class="text-slate-600 leading-relaxed relative z-10">Segera hadir — latihan soal otomatis yang dibuat secara real-time dan disesuaikan dengan kemampuanmu.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- HOW IT WORKS -->
    <section id="how" class="py-24 bg-slate-50 border-t border-slate-200">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <div class="text-primary font-semibold tracking-wide uppercase text-sm mb-3">Cara Kerja</div>
                <h2 class="text-3xl font-extrabold text-primary sm:text-4xl">Mulai Belajar dalam 3 Langkah</h2>
                <p class="mt-4 text-lg text-slate-600 max-w-2xl mx-auto">Tidak perlu setup rumit. Daftar, tanya, dan langsung pelajari dengan cara yang seru.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8 text-center">
                <!-- Step 1 -->
                <div class="relative">
                    <div class="w-16 h-16 mx-auto bg-primary text-white rounded-full flex items-center justify-center text-2xl font-bold mb-6 shadow-lg z-10 relative">1</div>
                    <div class="hidden md:block absolute top-8 left-[60%] w-[80%] h-[2px] bg-accent/10"></div>
                    <h3 class="text-xl font-bold text-primary mb-3">Buat Akun</h3>
                    <p class="text-slate-600">Daftar gratis hanya dengan email dan password. Tidak perlu kartu kredit.</p>
                </div>
                <!-- Step 2 -->
                <div class="relative">
                    <div class="w-16 h-16 mx-auto bg-accent text-white rounded-full flex items-center justify-center text-2xl font-bold mb-6 shadow-lg z-10 relative">2</div>
                    <div class="hidden md:block absolute top-8 left-[60%] w-[80%] h-[2px] bg-accent/10"></div>
                    <h3 class="text-xl font-bold text-primary mb-3">Tanyakan Materi</h3>
                    <p class="text-slate-600">Ketik pertanyaanmu tentang pelajaran apapun. AI akan menjawab dengan jelas.</p>
                </div>
                <!-- Step 3 -->
                <div class="relative">
                    <div class="w-16 h-16 mx-auto bg-primary/80 text-white rounded-full flex items-center justify-center text-2xl font-bold mb-6 shadow-lg z-10 relative">3</div>
                    <h3 class="text-xl font-bold text-primary mb-3">Pahami & Ulangi</h3>
                    <p class="text-slate-600">Baca jawaban, tanyakan lanjutan, dan review kapan saja dari riwayat belajar.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="py-24 bg-[#B91C1C] text-center relative overflow-hidden">
        <div class="absolute inset-0 bg-white/5 mix-blend-overlay"></div>
        <div class="max-w-4xl mx-auto px-4 sm:px-6">
            <h2 class="text-3xl font-bold text-white sm:text-4xl mb-6">Siap Belajar Lebih Cerdas?</h2>
            <p class="text-lg text-white/80 mb-10">Bergabung sekarang dan rasakan pengalaman belajar yang dipersonalisasi oleh AI terdepan.</p>
            @auth
                <a href="{{ route('chat.index') }}" class="inline-block bg-white text-[#B91C1C] hover:bg-slate-100 px-8 py-4 rounded-xl text-base font-bold transition shadow-lg hover:-translate-y-0.5">
                    🤖 Buka Chat AI
                </a>
            @else
                <a href="{{ route('register') }}" class="inline-block bg-white text-[#B91C1C] hover:bg-slate-100 px-8 py-4 rounded-xl text-base font-bold transition shadow-lg hover:-translate-y-0.5">
                    Daftar Gratis Sekarang →
                </a>
            @endauth
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-white py-8 border-t border-slate-200">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-sm text-slate-500 font-medium">
                &copy; {{ date('Y') }} Sinau. Hak Cipta Dilindungi.
            </p>
        </div>
    </footer>

    <x-chatbot />
</body>
</html>