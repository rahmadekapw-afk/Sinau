<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
                Analytics & Laporan <span class="text-3xl">📊</span>
            </h2>
            <button style="background-color: #B91C1C;" class="hover:opacity-90 text-white px-5 py-2.5 rounded-xl font-semibold shadow-md transition-all flex items-center gap-2 text-sm border-b-4 border-[#991B1B]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Download Laporan (PDF)
            </button>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- AI Insight Card (Clean Flat) --}}
            <div style="background: linear-gradient(135deg, #B91C1C 0%, #EF4444 100%);" class="rounded-2xl p-6 text-white shadow-lg relative overflow-hidden border-l-8 border-[#991B1B]">
                <div class="relative z-10 flex items-start gap-4">
                    <div class="w-12 h-12 bg-white/10 rounded-xl backdrop-blur-sm flex items-center justify-center text-2xl shrink-0 shadow-inner">
                        🤖
                    </div>
                    <div>
                        <h3 class="font-bold text-lg mb-1 text-white uppercase tracking-tight">AI Insight</h3>
                        <p class="text-white/90 leading-relaxed font-medium italic">
                            "{{ $insight ?? 'Analisis belajarmu akan muncul di sini setelah kamu lebih sering berinteraksi dengan AI.' }}"
                        </p>
                    </div>
                </div>
                <div class="absolute -right-10 -top-10 w-48 h-48 bg-white/5 rounded-full blur-2xl"></div>
            </div>

            {{-- Top Stats Row --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <div class="text-gray-500 text-sm font-semibold mb-2">Total Tugas</div>
                    <div class="text-3xl font-bold text-gray-800">{{ $totalTasks }}</div>
                </div>
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <div class="text-gray-500 text-sm font-semibold mb-2">Tugas Selesai</div>
                    <div class="text-3xl font-bold text-green-600">{{ $completedTasks }}</div>
                </div>
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <div class="text-gray-500 text-sm font-semibold mb-2">Tugas Tertunda</div>
                    <div class="text-3xl font-bold text-yellow-600">{{ $pendingTasks }}</div>
                </div>
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <div class="text-gray-500 text-sm font-semibold mb-2">Tugas Bantuan AI</div>
                    <div class="text-3xl font-bold text-[#B91C1C]">{{ $aiGeneratedTasks }}</div>
                </div>
            </div>

            {{-- Charts Row 1 --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Completion Rate --}}
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <h3 class="font-bold text-gray-800 mb-6">Tingkat Penyelesaian (Completion Rate)</h3>
                    <div class="flex items-center justify-center h-48">
                        <div class="relative w-40 h-40">
                            <svg class="w-full h-full" viewBox="0 0 36 36">
                                <path class="text-gray-100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="currentColor" stroke-width="3" />
                                <path class="text-[#B91C1C]" stroke-dasharray="{{ $completionRate }}, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" />
                            </svg>
                            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-center">
                                <span class="text-3xl font-bold text-gray-800">{{ $completionRate }}%</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Task Distribution by Type --}}
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <h3 class="font-bold text-gray-800 mb-6">Distribusi Kategori Tugas</h3>
                    <div class="space-y-4">
                        @php
                            $totalTypeTasks = array_sum($typeStats) > 0 ? array_sum($typeStats) : 1;
                        @endphp
                        
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="font-semibold text-[#B91C1C]">🔴 Deadline Tugas</span>
                                <span class="text-gray-500">{{ $typeStats['deadline'] }}</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2.5">
                                <div class="bg-[#B91C1C] h-2.5 rounded-full" style="width: {{ ($typeStats['deadline'] / $totalTypeTasks) * 100 }}%"></div>
                            </div>
                        </div>
                        
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="font-semibold text-yellow-600">⚠️ Ujian / Kuis</span>
                                <span class="text-gray-500">{{ $typeStats['exam'] }}</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2.5">
                                <div class="bg-yellow-400 h-2.5 rounded-full" style="width: {{ ($typeStats['exam'] / $totalTypeTasks) * 100 }}%"></div>
                            </div>
                        </div>
                        
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="font-semibold text-[#B91C1C]">🤖 Jadwal Belajar AI</span>
                                <span class="text-gray-500">{{ $typeStats['study'] }}</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2.5">
                                <div class="bg-[#B91C1C] h-2.5 rounded-full" style="width: {{ ($typeStats['study'] / $totalTypeTasks) * 100 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Charts Row 2 --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Tasks by Subject --}}
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <h3 class="font-bold text-gray-800 mb-6">Top Mata Pelajaran</h3>
                    @if($tasksBySubject->count() > 0)
                        <div class="space-y-4">
                            @foreach($tasksBySubject as $index => $subject)
                                @php
                                    $colors = ['bg-[#B91C1C]', 'bg-[#DC2626]', 'bg-[#EF4444]', 'bg-[#F87171]', 'bg-[#FECACA]'];
                                    $color = $colors[$index % count($colors)];
                                    $maxCount = $tasksBySubject->first()->count;
                                @endphp
                                <div class="flex items-center gap-3">
                                    <div class="w-24 text-sm font-semibold text-gray-600 truncate" title="{{ $subject->subject }}">{{ $subject->subject }}</div>
                                    <div class="flex-1 bg-gray-100 rounded-full h-4">
                                        <div class="{{ $color }} h-4 rounded-full" style="width: {{ ($subject->count / $maxCount) * 100 }}%"></div>
                                    </div>
                                    <div class="w-8 text-right text-sm font-bold text-gray-700">{{ $subject->count }}</div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-400 text-sm">
                            Belum ada data mata pelajaran.
                        </div>
                    @endif
                </div>

                {{-- Activity Chart (Using simple bar charts with HTML/CSS) --}}
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <h3 class="font-bold text-gray-800 mb-6">Tugas Selesai (7 Hari Terakhir)</h3>
                    <div class="flex items-end justify-between h-48 pt-4 pb-2">
                        @php
                            $maxActivity = $activityData->max() > 0 ? $activityData->max() : 1;
                        @endphp
                        @foreach($last7Days as $index => $day)
                            <div class="flex flex-col items-center gap-2 group">
                                <div class="relative w-8 bg-gray-100 rounded-t-lg h-36 flex items-end">
                                    <div class="w-full bg-[#B91C1C] rounded-t-lg transition-all group-hover:bg-[#EF4444]" style="height: {{ ($activityData[$index] / $maxActivity) * 100 }}%"></div>
                                    {{-- Tooltip --}}
                                    <div class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-xs py-1 px-2 rounded opacity-0 group-hover:opacity-100 transition-opacity">
                                        {{ $activityData[$index] }}
                                    </div>
                                </div>
                                <span class="text-xs text-gray-500 font-medium">{{ explode(' ', $day)[0] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
