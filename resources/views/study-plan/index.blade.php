<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
                Rencana Belajar AI <span class="text-3xl">✨</span>
            </h2>
            <form action="{{ route('study-plan.generate') }}" method="POST" id="generatePlanForm">
                @csrf
                <button type="submit" onclick="showLoadingState()"
                        style="background-color: #B91C1C;" class="hover:opacity-95 text-white px-5 py-2.5 rounded-xl font-bold shadow-md transition-all flex items-center gap-2 text-sm border-b-4 border-[#450A0A]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    {{ $user->study_plan ? 'Regenerasi Rencana AI' : 'Buat Rencana Belajar AI' }}
                </button>
            </form>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Success & Error Messages --}}
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center gap-2 text-sm font-medium">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl flex items-center gap-2 text-sm font-medium">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    {{ session('error') }}
                </div>
            @endif

            {{-- Loading State (Hidden by default) --}}
            <div id="loadingState" class="hidden bg-white dark:bg-gray-800 rounded-3xl p-12 text-center border border-gray-100 dark:border-gray-700 shadow-sm space-y-6">
                <div class="relative w-24 h-24 mx-auto">
                    <div class="absolute inset-0 rounded-full border-4 border-purple-100 animate-spin" style="border-top-color: #B91C1C;"></div>
                    <div class="absolute inset-2 bg-white dark:bg-gray-800 rounded-full flex items-center justify-center text-4xl">🤖</div>
                </div>
                <div class="space-y-2">
                    <h3 class="font-extrabold text-2xl text-gray-800 dark:text-gray-100">Sinau AI Sedang Merancang Rencanamu...</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 max-w-md mx-auto">Kami menganalisis mata pelajaran, kurikulum kelas {{ $user->kelas_label }}, dan membagi materi menjadi jadwal belajar mingguan yang seimbang.</p>
                </div>
                <div class="flex justify-center gap-2 text-xs font-bold text-gray-400">
                    <span class="animate-pulse">Mengakses OpenRouter...</span> · 
                    <span class="animate-pulse delay-75">Merancang kurikulum...</span> · 
                    <span class="animate-pulse delay-150">Sinkronisasi Kalender Pintar...</span>
                </div>
            </div>

            {{-- Main Dashboard Layout --}}
            <div id="mainDashboard" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- Left/Center: Rencana Belajar RoadMap --}}
                <div class="lg:col-span-2 space-y-6">
                    @if($user->study_plan)
                        <div class="bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-sm border border-gray-100 dark:border-gray-700">
                            <div class="flex items-center justify-between pb-6 mb-6 border-b border-gray-100 dark:border-gray-700">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 bg-red-50 dark:bg-red-950/20 rounded-xl flex items-center justify-center text-2xl text-[#B91C1C]">🗺️</div>
                                    <div>
                                        <h3 class="font-bold text-gray-800 dark:text-gray-100 text-lg">Weekly Study Roadmap</h3>
                                        <p class="text-xs text-gray-400">Dibuat otomatis oleh Sinau AI pada {{ $user->study_plan_generated_at ? $user->study_plan_generated_at->translatedFormat('d F Y, H:i') : '-' }}</p>
                                    </div>
                                </div>
                                <span class="bg-red-50 dark:bg-red-950/20 text-[#B91C1C] text-xs font-extrabold px-3 py-1 rounded-full uppercase tracking-wider border border-red-100 dark:border-red-900/30">Personalized</span>
                            </div>

                            <div class="prose prose-sm max-w-none dark:prose-invert" id="studyPlanContent">
                                {{-- Renders formatted markdown generated by AI --}}
                            </div>
                        </div>
                    @else
                        {{-- Welcome State (Empty Plan) --}}
                        <div class="bg-gradient-to-br from-red-550 to-red-700 text-white rounded-3xl p-10 shadow-xl border border-red-700/20 flex flex-col items-center text-center space-y-6" style="background: linear-gradient(135deg, #B91C1C 0%, #EF4444 100%);">
                            <span class="text-6xl block animate-bounce">📚</span>
                            <div class="space-y-2">
                                <h3 class="font-black text-3xl">Rencana Belajar Cerdas Anda!</h3>
                                <p class="text-white/80 text-sm max-w-lg mx-auto">Mulai susun rencana belajar mingguan yang terarah, dirancang khusus oleh AI berdasarkan mata pelajaran {{ $user->kelas_label }}. Tugas belajar akan otomatis tersinkronisasi ke Kalender Pintarmu.</p>
                            </div>
                            <button onclick="document.getElementById('generatePlanForm').submit(); showLoadingState();" style="background-color: #ffffff; color: #B91C1C;" class="hover:bg-red-50 px-8 py-3.5 rounded-2xl font-black shadow-lg transition-all flex items-center gap-2 text-sm">
                                ✨ Mulai Rancang dengan AI Sekarang
                            </button>
                        </div>
                    @endif
                </div>

                {{-- Right: Weekly AI Study Tasks --}}
                <div class="space-y-6">
                    {{-- Subjects Card --}}
                    <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
                        <h4 class="font-bold text-gray-800 dark:text-gray-100 text-sm mb-4 flex items-center gap-2">
                            📖 Mata Pelajaran Anda
                        </h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach($subjects as $subj)
                                <span class="bg-gray-50 dark:bg-gray-700/50 text-gray-600 dark:text-gray-300 text-xs font-bold px-3 py-1.5 rounded-xl border border-gray-100 dark:border-gray-700">{{ $subj->name }}</span>
                            @endforeach
                        </div>
                    </div>

                    {{-- AI study tasks widget --}}
                    <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="font-bold text-gray-800 dark:text-gray-100 text-sm flex items-center gap-2">
                                🤖 Jadwal Belajar AI
                                <span class="text-xs bg-red-100 text-red-750 px-2 py-0.5 rounded-full font-bold">{{ $studyTasks->count() }}</span>
                            </h4>
                            <a href="{{ route('calendar.index') }}" class="text-xs text-accent hover:underline font-bold">Kalender →</a>
                        </div>

                        <div class="space-y-3">
                            @forelse($studyTasks as $task)
                                <div class="p-4 rounded-2xl bg-gray-50 dark:bg-gray-700/30 border border-gray-100 dark:border-gray-700 hover:shadow-md transition flex items-start gap-3">
                                    <span class="text-2xl mt-0.5">🤖</span>
                                    <div class="flex-1 space-y-1">
                                        <h5 class="font-bold text-gray-800 dark:text-gray-200 text-xs leading-snug {{ $task->status === 'done' ? 'line-through text-gray-400' : '' }}">{{ $task->title }}</h5>
                                        <p class="text-[10px] text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                            <span>{{ $task->subject }}</span> · 
                                            <span class="font-bold text-accent">{{ $task->due_date->translatedFormat('d M') }} ({{ \Carbon\Carbon::parse($task->due_time)->format('H:i') }})</span>
                                        </p>
                                        @if($task->description)
                                            <p class="text-[10px] text-gray-400 dark:text-gray-500 line-clamp-2 mt-1 leading-normal">{{ $task->description }}</p>
                                        @endif
                                    </div>
                                    @if($task->status !== 'done')
                                        <form method="POST" action="{{ route('tasks.update', $task) }}" class="shrink-0">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="status" value="done">
                                            <button type="submit" class="w-6 h-6 rounded-lg bg-green-50 hover:bg-green-100 text-green-600 flex items-center justify-center transition border border-green-200 shadow-sm" title="Tandai Selesai">
                                                ✓
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs text-green-500 shrink-0 font-bold">✓</span>
                                    @endif
                                </div>
                            @empty
                                <div class="text-center py-8 text-gray-400 text-xs">
                                    Belum ada jadwal belajar AI untuk minggu ini. Klik "Buat Rencana Belajar AI" untuk membuatnya otomatis!
                                </div>
                            @endforelse
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>

    @if($user->study_plan)
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const rawPlan = `{!! addslashes($user->study_plan) !!}`;
                document.getElementById('studyPlanContent').innerHTML = formatMarkdown(rawPlan);
            });

            // Lightweight structured Markdown text formatter
            function formatMarkdown(text) {
                if (!text) return '';
                
                // Escape HTML characters
                let escaped = text
                    .replace(/&/g, "&amp;")
                    .replace(/</g, "&lt;")
                    .replace(/>/g, "&gt;")
                    .replace(/"/g, "&quot;")
                    .replace(/'/g, "&#039;");

                // Convert Headings
                escaped = escaped.replace(/^### (.*$)/gim, '<h6 class="font-bold text-sm text-gray-800 dark:text-gray-200 mt-5 mb-2">$1</h6>');
                escaped = escaped.replace(/^## (.*$)/gim, '<h5 class="font-extrabold text-md text-[#B91C1C] dark:text-red-400 mt-6 mb-3 border-b pb-1 border-gray-150">$1</h5>');
                escaped = escaped.replace(/^# (.*$)/gim, '<h4 class="font-black text-xl text-gray-900 dark:text-white mt-8 mb-4">$1</h4>');

                // Convert Bold
                escaped = escaped.replace(/\*\*(.*?)\*\*/g, '<strong class="font-bold text-[#B91C1C] dark:text-red-400">$1</strong>');
                
                // Convert Bullet lists (supports both '-' and '*')
                escaped = escaped.replace(/^\s*[-*]\s+(.*$)/gim, '<li class="ml-5 list-disc my-2 text-sm text-gray-650 dark:text-gray-300">$1</li>');

                // Wrap stray list items
                escaped = escaped.replace(/(<li.*?>.*?<\/li>)/gs, '<ul class="my-3">$1</ul>');
                // Clean overlapping ul tags
                escaped = escaped.replace(/<\/ul>\s*<ul class="my-3">/g, '');

                return escaped;
            }
        </script>
    @endif

    <script>
        function showLoadingState() {
            document.getElementById('mainDashboard').classList.add('hidden');
            document.getElementById('loadingState').classList.remove('hidden');
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    </script>
</x-app-layout>
