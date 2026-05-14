<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Welcome Banner 3D --}}
            <div style="background: linear-gradient(135deg, #450A0A 0%, #B91C1C 100%); box-shadow: 0 10px 0 #450A0A, 0 20px 25px -5px rgba(0, 0, 0, 0.4);" 
                 class="relative overflow-hidden rounded-3xl p-8 text-white border-t border-white/20 transform transition-all hover:-translate-y-1">
                <div class="relative z-10">
                    <h1 class="text-3xl font-bold mb-2 text-white drop-shadow-md">Selamat datang, {{ Auth::user()->name }}! 👋</h1>
                    <p class="text-white/90 text-lg italic font-medium drop-shadow-sm">Si-Nau: siap membantu perjalanan belajarmu.</p>
                    <a href="{{ route('chat.index') }}"
                       style="background-color: #B91C1C; box-shadow: 0 4px 0 #450A0A;" 
                       class="mt-6 inline-flex items-center gap-2 text-white font-black px-8 py-4 rounded-2xl hover:translate-y-[2px] hover:shadow-none transition-all shadow-lg active:translate-y-[4px]">
                        🤖 MULAI CHAT DENGAN AI
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
                {{-- Decorative 3D Orbs --}}
                <div class="absolute -right-10 -top-10 w-48 h-48 bg-white/10 rounded-full blur-3xl"></div>
                <div class="absolute right-10 bottom-0 w-32 h-32 bg-black/20 rounded-full blur-2xl"></div>
            </div>

            {{-- Today's Schedule Widget --}}
            @if($todayTasks->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                    <h3 class="font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                        📅 Jadwal Hari Ini
                        <span class="text-xs bg-red-100 text-red-700 px-2.5 py-0.5 rounded-full font-bold">{{ $todayTasks->count() }}</span>
                    </h3>
                    <a href="{{ route('calendar.index') }}" class="text-xs text-accent hover:text-primary font-bold transition">
                        Lihat Calendar →
                    </a>
                </div>
                <div class="divide-y divide-gray-50 dark:divide-gray-700">
                    @foreach($todayTasks as $task)
                        <div class="px-6 py-3 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                            <div class="flex items-center gap-3">
                                <div class="w-2 h-8 rounded-full {{ $task->bar_class }}"></div>
                                <div>
                                    <p class="font-semibold text-gray-800 dark:text-gray-100 text-sm {{ $task->status === 'done' ? 'line-through text-gray-400' : '' }}">
                                        {{ $task->emoji }} {{ $task->title }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $task->type_label }}
                                        @if($task->subject) · {{ $task->subject }} @endif
                                        @if($task->due_time) · {{ \Carbon\Carbon::parse($task->due_time)->format('H:i') }} @endif
                                    </p>
                                </div>
                            </div>
                            @if($task->status !== 'done')
                                <form method="POST" action="{{ route('tasks.update', $task) }}">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="status" value="done">
                                    <button type="submit" id="submitBtn" style="background-color: #991B1B;" class="hover:opacity-95 text-white px-8 py-4 rounded-xl font-bold shadow-lg transition-all flex items-center gap-2 mx-auto border-b-4 border-[#450A0A] disabled:opacity-50 disabled:cursor-not-allowed">
                                        ✓ Selesai
                                    </button>
                                </form>
                            @else
                                <span class="text-xs text-green-500 font-semibold">✓ Done</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Upcoming Tasks Widget --}}
            @if($upcomingTasks->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                    <h3 class="font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                        📌 Segera Datang
                    </h3>
                    <a href="{{ route('tasks.index') }}" class="text-xs text-accent hover:text-primary font-bold transition">
                        Lihat Semua Tasks →
                    </a>
                </div>
                <div class="divide-y divide-gray-50 dark:divide-gray-700">
                    @foreach($upcomingTasks as $task)
                        <div class="px-6 py-3 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                            <div class="flex items-center gap-3">
                                <div class="w-2 h-8 rounded-full {{ $task->bar_class }}"></div>
                                <div>
                                    <p class="font-semibold text-gray-800 dark:text-gray-100 text-sm">{{ $task->title }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $task->type_label }} @if($task->subject) · {{ $task->subject }} @endif
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-semibold text-gray-600 dark:text-gray-300">
                                    @if($task->due_date->isToday()) <span class="text-red-600">Hari ini</span>
                                    @elseif($task->due_date->isTomorrow()) <span class="text-yellow-600">Besok</span>
                                    @else {{ $task->due_date->translatedFormat('d M') }}
                                    @endif
                                </p>
                                @if($task->due_time)
                                    <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($task->due_time)->format('H:i') }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Feature Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <a href="{{ route('chat.index') }}"
                   class="group bg-white rounded-2xl p-6 shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 hover:-translate-y-1">
                    <div class="w-12 h-12 bg-accent/10 rounded-xl flex items-center justify-center text-2xl mb-4">🤖</div>
                    <h3 class="font-bold text-primary text-lg mb-1">Tanya Jawab AI</h3>
                    <p class="text-sm text-gray-500">Tanyakan apapun dan dapatkan penjelasan yang mudah dipahami.</p>
                </a>

                <a href="{{ route('chat.history') }}"
                   class="group bg-white rounded-2xl p-6 shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 hover:-translate-y-1">
                    <div class="w-12 h-12 bg-accent/10 rounded-xl flex items-center justify-center text-2xl mb-4">📜</div>
                    <h3 class="font-bold text-primary text-lg mb-1">Riwayat Belajar</h3>
                    <p class="text-sm text-gray-500">Lihat kembali sesi belajar sebelumnya kapan saja.</p>
                </a>

                <div class="group bg-white rounded-2xl p-6 shadow-sm border border-gray-100 opacity-70">
                    <div class="w-12 h-12 bg-accent/10 rounded-xl flex items-center justify-center text-2xl mb-4">📝</div>
                    <h3 class="font-bold text-primary text-lg mb-1">Quiz Generator</h3>
                    <p class="text-sm text-gray-500">Segera hadir — latihan soal otomatis dari AI.</p>
                    <span class="inline-block mt-2 text-xs bg-primary/10 text-primary px-2 py-0.5 rounded-full font-medium">Coming Soon</span>
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
