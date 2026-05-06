<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Welcome Banner --}}
            <div class="relative overflow-hidden bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500 rounded-2xl p-8 text-white shadow-xl">
                <div class="relative z-10">
                    <h1 class="text-3xl font-bold mb-2">Selamat datang, {{ Auth::user()->name }}! 👋</h1>
                    <p class="text-indigo-100 text-lg">Siap belajar hari ini? AI siap membantu perjalanan belajarmu.</p>
                    <a href="{{ route('chat.index') }}"
                       class="mt-5 inline-flex items-center gap-2 bg-white text-indigo-700 font-semibold px-6 py-3 rounded-xl hover:bg-indigo-50 transition shadow-lg">
                        🤖 Mulai Chat dengan AI
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
                <div class="absolute -right-10 -top-10 w-48 h-48 bg-white/10 rounded-full"></div>
                <div class="absolute -right-5 -bottom-5 w-32 h-32 bg-white/5 rounded-full"></div>
            </div>

            {{-- Feature Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <a href="{{ route('chat.index') }}"
                   class="group bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 dark:border-gray-700 hover:-translate-y-1">
                    <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900 rounded-xl flex items-center justify-center text-2xl mb-4">🤖</div>
                    <h3 class="font-bold text-gray-800 dark:text-gray-100 text-lg mb-1">Tanya Jawab AI</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Tanyakan apapun dan dapatkan penjelasan yang mudah dipahami.</p>
                </a>

                <a href="{{ route('chat.history') }}"
                   class="group bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 dark:border-gray-700 hover:-translate-y-1">
                    <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-xl flex items-center justify-center text-2xl mb-4">📜</div>
                    <h3 class="font-bold text-gray-800 dark:text-gray-100 text-lg mb-1">Riwayat Belajar</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Lihat kembali sesi belajar sebelumnya kapan saja.</p>
                </a>

                <div class="group bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 opacity-70">
                    <div class="w-12 h-12 bg-pink-100 dark:bg-pink-900 rounded-xl flex items-center justify-center text-2xl mb-4">📝</div>
                    <h3 class="font-bold text-gray-800 dark:text-gray-100 text-lg mb-1">Quiz Generator</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Segera hadir — latihan soal otomatis dari AI.</p>
                    <span class="inline-block mt-2 text-xs bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded-full font-medium">Coming Soon</span>
                </div>
            </div>

            {{-- Tips --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
                <h3 class="font-bold text-gray-800 dark:text-gray-100 mb-4">💡 Tips Belajar Efektif</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach([
                        ['🎯', 'Buat target belajar yang spesifik setiap hari'],
                        ['❓', 'Jangan takut bertanya — AI siap menjawab 24/7'],
                        ['🔁', 'Ulangi materi yang sulit dengan cara berbeda'],
                        ['📚', 'Minta AI untuk memberikan contoh nyata dari materi'],
                    ] as [$icon, $tip])
                    <div class="flex items-start gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                        <span class="text-xl">{{ $icon }}</span>
                        <p class="text-sm text-gray-600 dark:text-gray-300">{{ $tip }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
