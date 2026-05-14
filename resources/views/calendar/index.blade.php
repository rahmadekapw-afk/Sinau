<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
                Kalender Pintar <span class="text-3xl">📅</span>
            </h2>
            <div class="flex gap-3">
                <button class="bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 px-4 py-2.5 rounded-xl font-semibold shadow-sm transition-all text-sm flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Sync Google Calendar
                </button>
                <button onclick="document.getElementById('addScheduleModal').classList.remove('hidden')"
                        style="background-color: #B91C1C;" class="hover:opacity-95 text-white px-5 py-2.5 rounded-xl font-semibold shadow-md transition-all flex items-center gap-2 text-sm border-b-4 border-[#450A0A]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Jadwal
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Success Message --}}
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center gap-2 text-sm font-medium">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Smart Plan Notification --}}
            @if($upcomingTasks->count() > 0)
            <div class="bg-accent/5 rounded-2xl p-5 border border-accent/10 flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-white rounded-full shadow-sm flex items-center justify-center text-xl shrink-0">✨</div>
                    <div>
                        <h3 class="font-bold text-primary text-sm">{{ $upcomingTasks->count() }} Jadwal Mendatang</h3>
                        <p class="text-xs text-primary/70">Terdekat: {{ $upcomingTasks->first()->title }} — {{ $upcomingTasks->first()->due_date->translatedFormat('l, d F Y') }}</p>
                    </div>
                </div>
                <a href="{{ route('tasks.index') }}" style="background-color: #B91C1C;" class="hover:opacity-95 text-white px-4 py-2 rounded-lg text-sm font-semibold shadow-sm transition-all whitespace-nowrap border-b-2 border-[#450A0A]">
                    Lihat Tasks
                </a>
            </div>
            @endif

            {{-- Calendar Container --}}
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">

                {{-- Calendar Header --}}
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                    <div class="flex items-center gap-4">
                        <h2 class="text-xl font-bold text-gray-800">{{ $startOfMonth->translatedFormat('F Y') }}</h2>
                        <div class="flex items-center gap-1 bg-white border border-gray-200 rounded-lg p-1 shadow-sm">
                            @php
                                $prevMonth = $month == 1 ? 12 : $month - 1;
                                $prevYear = $month == 1 ? $year - 1 : $year;
                                $nextMonth = $month == 12 ? 1 : $month + 1;
                                $nextYear = $month == 12 ? $year + 1 : $year;
                            @endphp
                            <a href="{{ route('calendar.index', ['month' => $prevMonth, 'year' => $prevYear]) }}"
                               class="p-1 hover:bg-gray-100 rounded-md text-gray-500 block">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            </a>
                            <a href="{{ route('calendar.index', ['month' => $nextMonth, 'year' => $nextYear]) }}"
                               class="p-1 hover:bg-gray-100 rounded-md text-gray-500 block">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                        @if($month != now()->month || $year != now()->year)
                            <a href="{{ route('calendar.index') }}" class="text-xs font-semibold text-accent hover:text-primary bg-accent/5 px-3 py-1 rounded-md transition">
                                Hari Ini
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Legend --}}
                <div class="px-6 py-3 border-b border-gray-50 flex gap-4 text-xs font-medium">
                    <div class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-accent"></span> Jadwal Belajar AI</div>
                    <div class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-red-500"></span> Deadline Tugas</div>
                    <div class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-yellow-400"></span> Ujian / Kuis</div>
                </div>

                {{-- Calendar Grid: Day Headers --}}
                <div class="grid grid-cols-7 border-b border-gray-100 bg-gray-50/50">
                    @foreach(['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $day)
                        <div class="py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">{{ $day }}</div>
                    @endforeach
                </div>

                {{-- Calendar Grid: Days --}}
                <div class="grid grid-cols-7 bg-gray-100 gap-px">
                    {{-- Empty cells before first day --}}
                    @for($i = 0; $i < $firstDayOfWeek; $i++)
                        <div class="bg-gray-50 min-h-[120px] p-2 text-gray-400 text-sm font-medium"></div>
                    @endfor

                    {{-- Actual days --}}
                    @for($day = 1; $day <= $daysInMonth; $day++)
                        @php
                            $currentDate = \Carbon\Carbon::create($year, $month, $day)->format('Y-m-d');
                            $isToday = $currentDate === $today;
                            $dayTasks = $tasksGrouped[$currentDate] ?? collect();
                        @endphp
                        <div class="bg-white min-h-[120px] p-2 flex flex-col gap-1 transition hover:bg-gray-50 {{ $isToday ? 'ring-2 ring-accent/20 ring-inset' : '' }}">
                            <span class="text-sm font-semibold p-1 {{ $isToday ? 'bg-accent text-white rounded-full w-7 h-7 flex items-center justify-center' : 'text-gray-700' }}">
                                {{ $day }}
                            </span>

                            @foreach($dayTasks->take(3) as $task)
                                <div class="{{ $task->event_classes }} border text-[10px] font-bold px-2 py-1 rounded truncate {{ $task->status === 'done' ? 'opacity-50 line-through' : '' }}"
                                     title="{{ $task->title }}{{ $task->due_time ? ' (' . \Carbon\Carbon::parse($task->due_time)->format('H:i') . ')' : '' }}">
                                    {{ $task->emoji }} {{ $task->title }}
                                </div>
                            @endforeach

                            @if($dayTasks->count() > 3)
                                <span class="text-[10px] text-gray-400 font-medium px-2">+{{ $dayTasks->count() - 3 }} lainnya</span>
                            @endif
                        </div>
                    @endfor

                    {{-- Empty cells after last day --}}
                    @php
                        $lastDayOfWeek = \Carbon\Carbon::create($year, $month, $daysInMonth)->dayOfWeek;
                        $remainingCells = $lastDayOfWeek == 6 ? 0 : 6 - $lastDayOfWeek;
                    @endphp
                    @for($i = 0; $i < $remainingCells; $i++)
                        <div class="bg-gray-50 min-h-[120px] p-2 text-gray-400 text-sm font-medium"></div>
                    @endfor
                </div>
            </div>

            {{-- Upcoming Tasks List (below calendar) --}}
            @if($upcomingTasks->count() > 0)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                    📌 Jadwal Mendatang
                    <span class="text-xs bg-accent/10 text-accent px-2 py-0.5 rounded-full font-bold">{{ $upcomingTasks->count() }}</span>
                </h3>
                <div class="space-y-3">
                    @foreach($upcomingTasks as $task)
                        <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50 hover:bg-gray-100 transition">
                            <div class="flex items-center gap-3">
                                <div class="w-2.5 h-8 rounded-full {{ $task->bar_class }}"></div>
                                <div>
                                    <p class="font-semibold text-gray-800 text-sm">{{ $task->title }}</p>
                                    <p class="text-xs text-gray-500">{{ $task->type_label }} @if($task->subject) · {{ $task->subject }} @endif</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-semibold text-gray-700">{{ $task->due_date->translatedFormat('d M') }}</p>
                                @if($task->due_time)
                                    <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($task->due_time)->format('H:i') }}</p>
                                @endif
                                @if($task->due_date->isToday())
                                    <span class="text-[10px] font-bold text-red-600 uppercase">Hari ini</span>
                                @elseif($task->due_date->isTomorrow())
                                    <span class="text-[10px] font-bold text-yellow-600 uppercase">Besok</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>
    </div>

    {{-- Add Schedule Modal --}}
    <div id="addScheduleModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="document.getElementById('addScheduleModal').classList.add('hidden')"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6 z-10">
            <div class="flex items-center justify-between mb-6">
                <h3 class="font-bold text-xl text-gray-800">Tambah Jadwal Baru</h3>
                <button onclick="document.getElementById('addScheduleModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form method="POST" action="{{ route('tasks.store') }}">
                @csrf
                <input type="hidden" name="from_calendar" value="1">
                <input type="hidden" name="month" value="{{ $month }}">
                <input type="hidden" name="year" value="{{ $year }}">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Judul Jadwal *</label>
                        <input type="text" name="title" required placeholder="Contoh: Ujian Tengah Semester" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-accent focus:ring-accent text-sm px-4 py-3">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi</label>
                        <textarea name="description" rows="2" placeholder="Detail jadwal (opsional)" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-accent focus:ring-accent text-sm px-4 py-3"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Kategori</label>
                            <select name="type" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-accent focus:ring-accent text-sm px-4 py-3">
                                <option value="deadline">🔴 Deadline Tugas</option>
                                <option value="exam">⚠️ Ujian / Kuis</option>
                                <option value="study">🤖 Jadwal Belajar</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Prioritas</label>
                            <select name="priority" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-accent focus:ring-accent text-sm px-4 py-3">
                                <option value="high">🔥 Penting</option>
                                <option value="medium" selected>📌 Sedang</option>
                                <option value="low">💤 Rendah</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Tanggal *</label>
                            <input type="date" name="due_date" required class="w-full rounded-xl border-gray-200 shadow-sm focus:border-accent focus:ring-accent text-sm px-4 py-3">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Waktu</label>
                            <input type="time" name="due_time" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-accent focus:ring-accent text-sm px-4 py-3">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Mapel</label>
                            <input type="text" name="subject" placeholder="Opsional" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-accent focus:ring-accent text-sm px-4 py-3">
                        </div>
                    </div>
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="document.getElementById('addScheduleModal').classList.add('hidden')" class="flex-1 px-4 py-3 border border-gray-200 text-gray-600 rounded-xl font-semibold hover:bg-gray-50 transition text-sm">Batal</button>
                    <button type="submit" style="background-color: #B91C1C;" class="flex-1 px-4 py-3 text-white rounded-xl font-semibold hover:opacity-95 shadow-md border-b-4 border-[#450A0A] transition text-sm">Tambah Jadwal</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
