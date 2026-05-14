<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
                Task Management <span class="text-3xl">🎯</span>
            </h2>
            <button onclick="document.getElementById('addTaskModal').classList.remove('hidden')"
                    style="background-color: #B91C1C;" class="hover:opacity-95 text-white px-5 py-2.5 rounded-xl font-semibold shadow-md transition-all flex items-center gap-2 text-sm border-b-4 border-[#450A0A]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Tugas
            </button>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Success Message --}}
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 flex items-center gap-2 text-sm font-medium">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ session('success') }}
                </div>
            @endif

            {{-- AI Assistant Input --}}
            <div class="bg-primary/5 rounded-2xl p-6 border border-accent/10 mb-8 flex items-start gap-5 shadow-sm">
                <div class="w-14 h-14 bg-white rounded-2xl shadow-sm flex items-center justify-center text-3xl shrink-0 border border-white">🤖</div>
                <div class="flex-1">
                    <h3 class="font-bold text-primary mb-1 text-lg">AI Task Breakdown</h3>
                    <p class="text-sm text-primary/80 mb-4">Punya tugas besar yang membingungkan? Ketik di sini dan biarkan Sinau AI memecahnya menjadi langkah-langkah kecil yang mudah dikerjakan.</p>
                    <div class="flex gap-3">
                        <input type="text" placeholder="Contoh: Buat makalah sejarah kemerdekaan Indonesia" class="flex-1 rounded-xl border-accent/20 shadow-sm focus:border-accent focus:ring-accent text-sm px-4 py-3">
                        <button style="background-color: #B91C1C;" class="hover:opacity-95 text-white px-6 py-3 rounded-xl font-semibold shadow-sm transition-all whitespace-nowrap flex items-center gap-2 border-b-4 border-[#450A0A]">
                            ✨ Pecah dengan AI
                        </button>
                    </div>
                </div>
            </div>

            {{-- Kanban Board --}}
            <div class="flex gap-6 overflow-x-auto pb-4 items-start">

                {{-- Column: To-Do --}}
                <div class="bg-gray-100/80 rounded-2xl p-4 w-[350px] shrink-0 border border-gray-200/60">
                    <div class="flex items-center justify-between mb-4 px-1">
                        <h3 class="font-bold text-gray-700 flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-gray-400"></span>
                            Belum Mulai
                        </h3>
                        <span class="bg-white border border-gray-200 text-gray-600 text-xs font-bold px-2.5 py-1 rounded-lg shadow-sm">{{ $todoTasks->count() }}</span>
                    </div>

                    <div class="space-y-3">
                        @forelse($todoTasks as $task)
                            <div class="bg-white p-4 rounded-xl shadow-sm border {{ $task->is_ai ? 'border-purple-100' : 'border-gray-100' }} cursor-pointer hover:shadow-md transition-all group {{ $task->is_ai ? 'relative overflow-hidden' : '' }}">
                                @if($task->is_ai)
                                    <div class="absolute top-0 right-0 w-1.5 h-full bg-gradient-to-b from-indigo-500 to-purple-500"></div>
                                @endif
                                <div class="flex justify-between items-start mb-2">
                                    @if($task->is_ai)
                                        <span class="bg-purple-50 text-purple-600 text-xs font-bold px-2.5 py-1 rounded-md border border-purple-100 flex items-center gap-1">✨ AI Task</span>
                                    @elseif($task->priority === 'high')
                                        <span class="bg-red-50 text-red-600 text-xs font-bold px-2.5 py-1 rounded-md border border-red-100">Penting</span>
                                    @else
                                        <span class="bg-gray-50 text-gray-500 text-xs font-bold px-2.5 py-1 rounded-md border border-gray-100">{{ ucfirst($task->priority) }}</span>
                                    @endif
                                    <form method="POST" action="{{ route('tasks.update', $task) }}" class="opacity-0 group-hover:opacity-100 transition">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="status" value="progress">
                                        <button type="submit" title="Mulai Kerjakan" class="text-indigo-400 hover:text-indigo-600">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </button>
                                    </form>
                                </div>
                                <h4 class="font-bold text-gray-800 text-sm mb-1.5 leading-snug">{{ $task->title }}</h4>
                                @if($task->description)
                                    <p class="text-xs text-gray-500 mb-4 line-clamp-2">{{ $task->description }}</p>
                                @endif
                                <div class="flex items-center justify-between pt-3 border-t border-gray-50">
                                    <div class="flex items-center gap-1.5 text-xs font-semibold {{ $task->due_date && $task->due_date->isToday() ? 'text-red-600' : ($task->due_date && $task->due_date->isPast() ? 'text-red-600' : 'text-yellow-600') }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        @if($task->due_date)
                                            @if($task->due_date->isToday()) Hari ini
                                            @elseif($task->due_date->isTomorrow()) Besok
                                            @elseif($task->due_date->isPast()) Terlambat!
                                            @else {{ $task->due_date->format('d M') }}
                                            @endif
                                        @else
                                            Tanpa deadline
                                        @endif
                                    </div>
                                    @if($task->subject)
                                        <div class="text-xs px-2 py-1 bg-gray-50 text-gray-500 border border-gray-100 rounded-md font-medium">{{ $task->subject }}</div>
                                    @elseif($task->is_ai)
                                        <div class="w-6 h-6 rounded-full bg-purple-100 flex items-center justify-center text-[10px]">🤖</div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-400 text-sm">
                                <div class="text-3xl mb-2">🎉</div>
                                Tidak ada tugas pending!
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Column: In Progress --}}
                <div class="bg-gray-100/80 rounded-2xl p-4 w-[350px] shrink-0 border border-gray-200/60">
                    <div class="flex items-center justify-between mb-4 px-1">
                        <h3 class="font-bold text-gray-700 flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-accent shadow-sm shadow-accent/20"></span>
                            Sedang Dikerjakan
                        </h3>
                        <span class="bg-white border border-gray-200 text-gray-600 text-xs font-bold px-2.5 py-1 rounded-lg shadow-sm">{{ $progressTasks->count() }}</span>
                    </div>

                    <div class="space-y-3">
                        @forelse($progressTasks as $task)
                            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 cursor-pointer hover:shadow-md transition-all group border-l-4 border-l-accent">
                                <h4 class="font-bold text-gray-800 text-sm mb-1.5 leading-snug">{{ $task->title }}</h4>
                                @if($task->description)
                                    <p class="text-xs text-gray-500 mb-4">{{ $task->description }}</p>
                                @endif

                                {{-- Progress bar --}}
                                <div class="w-full bg-gray-100 rounded-full h-2 mb-4">
                                    <div class="bg-accent h-2 rounded-full transition-all" style="width: {{ $task->progress_percent }}%"></div>
                                </div>

                                <div class="flex items-center justify-between pt-3 border-t border-gray-50">
                                    <div class="flex items-center gap-1.5 text-xs font-medium text-gray-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        @if($task->due_date) {{ $task->due_date->format('d M') }} @else - @endif
                                    </div>
                                    <div class="flex items-center gap-2">
                                        @if($task->subject)
                                            <div class="text-xs px-2 py-1 bg-gray-50 text-gray-500 border border-gray-100 rounded-md font-medium">{{ $task->subject }}</div>
                                        @endif
                                        <form method="POST" action="{{ route('tasks.update', $task) }}" class="opacity-0 group-hover:opacity-100 transition">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="status" value="done">
                                            <button type="submit" title="Tandai Selesai" class="text-green-400 hover:text-green-600">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-400 text-sm">
                                <div class="text-3xl mb-2">💤</div>
                                Belum ada tugas dikerjakan
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Column: Done --}}
                <div class="bg-gray-100/80 rounded-2xl p-4 w-[350px] shrink-0 border border-gray-200/60 opacity-80 hover:opacity-100 transition-all">
                    <div class="flex items-center justify-between mb-4 px-1">
                        <h3 class="font-bold text-gray-700 flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-green-500 shadow-sm shadow-green-200"></span>
                            Selesai
                        </h3>
                        <span class="bg-white border border-gray-200 text-gray-600 text-xs font-bold px-2.5 py-1 rounded-lg shadow-sm">{{ $doneTasks->count() }}</span>
                    </div>

                    <div class="space-y-3">
                        @forelse($doneTasks as $task)
                            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 grayscale-[0.3]">
                                <div class="flex gap-2 items-center mb-2">
                                    <div class="w-6 h-6 rounded-full bg-green-100 flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                    <h4 class="font-bold text-gray-400 text-sm line-through">{{ $task->title }}</h4>
                                </div>
                                <div class="flex items-center justify-between pt-2">
                                    @if($task->subject)
                                        <div class="text-xs px-2 py-1 bg-gray-50 text-gray-400 border border-gray-100 rounded-md font-medium">{{ $task->subject }}</div>
                                    @else
                                        <div></div>
                                    @endif
                                    <span class="text-[10px] font-semibold text-gray-400 uppercase tracking-wide">
                                        @if($task->completed_at)
                                            Selesai {{ $task->completed_at->diffForHumans() }}
                                        @else
                                            Selesai
                                        @endif
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-400 text-sm">
                                <div class="text-3xl mb-2">📝</div>
                                Belum ada yang selesai
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Add Task Modal --}}
    <div id="addTaskModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="document.getElementById('addTaskModal').classList.add('hidden')"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6 z-10">
            <div class="flex items-center justify-between mb-6">
                <h3 class="font-bold text-xl text-gray-800">Tambah Tugas Baru</h3>
                <button onclick="document.getElementById('addTaskModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form method="POST" action="{{ route('tasks.store') }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Judul Tugas *</label>
                        <input type="text" name="title" required placeholder="Contoh: PR Matematika Bab 5" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-accent focus:ring-accent text-sm px-4 py-3">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi</label>
                        <textarea name="description" rows="2" placeholder="Detail tugas (opsional)" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-accent focus:ring-accent text-sm px-4 py-3"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Tipe</label>
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
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Deadline</label>
                            <input type="date" name="due_date" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-accent focus:ring-accent text-sm px-4 py-3">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Mata Pelajaran</label>
                            <input type="text" name="subject" placeholder="Contoh: Matematika" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-accent focus:ring-accent text-sm px-4 py-3">
                        </div>
                    </div>
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="document.getElementById('addTaskModal').classList.add('hidden')" class="flex-1 px-4 py-3 border border-gray-200 text-gray-600 rounded-xl font-semibold hover:bg-gray-50 transition text-sm">Batal</button>
                    <button type="submit" style="background-color: #B91C1C;" class="flex-1 px-4 py-3 text-white rounded-xl font-semibold hover:opacity-95 shadow-md border-b-4 border-[#450A0A] transition text-sm">Tambah Tugas</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
