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
                            <div class="task-card bg-white p-4 rounded-xl shadow-sm border {{ $task->is_ai ? 'border-purple-100' : 'border-gray-100' }} cursor-pointer hover:shadow-md transition-all group {{ $task->is_ai ? 'relative overflow-hidden' : '' }}"
                                 data-id="{{ $task->id }}"
                                 data-title="{{ $task->title }}"
                                 data-description="{{ $task->description }}"
                                 data-subject="{{ $task->subject }}"
                                 data-priority="{{ $task->priority }}"
                                 data-due="{{ $task->due_date ? $task->due_date->format('Y-m-d') : '' }}"
                                 data-status="{{ $task->status }}"
                                 data-is-ai="{{ $task->is_ai ? 1 : 0 }}">
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
                            {{-- Card utama: klik buka modal detail --}}
                            <div class="task-card bg-white p-4 rounded-xl shadow-sm border border-gray-100 cursor-pointer hover:shadow-md transition-all group border-l-4 border-l-accent"
                                 data-id="{{ $task->id }}"
                                 data-title="{{ $task->title }}"
                                 data-description="{{ $task->description }}"
                                 data-subject="{{ $task->subject }}"
                                 data-priority="{{ $task->priority }}"
                                 data-due="{{ $task->due_date ? $task->due_date->format('Y-m-d') : '' }}"
                                 data-status="{{ $task->status }}"
                                 data-is-ai="{{ $task->is_ai ? 1 : 0 }}"
                                 data-submission="{{ $task->submission ?? '' }}">
                                <h4 class="font-bold text-gray-800 text-sm mb-1.5 leading-snug">{{ $task->title }}</h4>
                                @if($task->description)
                                    <p class="text-xs text-gray-500 mb-3">{{ $task->description }}</p>
                                @endif

                                {{-- Progress bar --}}
                                <div class="w-full bg-gray-100 rounded-full h-2 mb-3">
                                    <div class="bg-accent h-2 rounded-full transition-all" style="width: {{ $task->progress_percent }}%"></div>
                                </div>

                                {{-- Submit Area — langsung di kartu --}}
                                <div class="submit-area mt-3 pt-3 border-t border-dashed border-red-100 bg-red-50/40 rounded-xl p-3" onclick="event.stopPropagation()">
                                    <p class="text-[11px] font-bold text-red-700 mb-2 flex items-center gap-1">📤 Kumpulkan Jawaban Tugas</p>
                                    <form method="POST" action="{{ route('tasks.submit', $task) }}">
                                        @csrf
                                        <textarea name="submission"
                                                  rows="3"
                                                  required
                                                  placeholder="Tulis jawaban atau ringkasan hasil pengerjaanmu di sini..."
                                                  class="w-full text-xs rounded-lg border border-red-200 focus:border-red-400 focus:ring-red-300 px-3 py-2 resize-none bg-white shadow-sm"></textarea>
                                        <button type="submit"
                                                style="background-color:#B91C1C;"
                                                class="mt-2 w-full text-white text-xs font-bold py-2 rounded-lg hover:opacity-90 transition border-b-2 border-[#450A0A] flex items-center justify-center gap-1.5">
                                            ✅ Submit &amp; Tandai Selesai
                                        </button>
                                    </form>
                                </div>

                                <div class="flex items-center justify-between pt-2 mt-2">
                                    <div class="flex items-center gap-1.5 text-xs font-medium text-gray-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        @if($task->due_date) {{ $task->due_date->format('d M') }} @else - @endif
                                    </div>
                                    @if($task->subject)
                                        <div class="text-xs px-2 py-1 bg-gray-50 text-gray-500 border border-gray-100 rounded-md font-medium">{{ $task->subject }}</div>
                                    @endif
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
                            <div class="task-card bg-white p-4 rounded-xl shadow-sm border border-gray-100 grayscale-[0.3] cursor-pointer hover:shadow-md transition-all"
                                 data-id="{{ $task->id }}"
                                 data-title="{{ $task->title }}"
                                 data-description="{{ $task->description }}"
                                 data-subject="{{ $task->subject }}"
                                 data-priority="{{ $task->priority }}"
                                 data-due="{{ $task->due_date ? $task->due_date->format('Y-m-d') : '' }}"
                                 data-status="{{ $task->status }}"
                                 data-is-ai="{{ $task->is_ai ? 1 : 0 }}">
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

    {{-- Task Detail & AI Material Modal --}}
    <div id="taskDetailModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4" style="background-color: rgba(0, 0, 0, 0.5); backdrop-filter: blur(4px);">
        <div class="absolute inset-0" onclick="closeTaskDetailModal()"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden z-10 transform scale-95 transition-all duration-300" id="taskDetailModalCard" style="display: flex; flex-direction: column; max-height: 90vh;">
            
            <!-- Header bar with theme color -->
            <div class="bg-gradient-to-r from-red-600 to-[#991B1B] p-6 text-white flex justify-between items-start shrink-0">
                <div class="space-y-1">
                    <span id="detail-badge-is-ai" class="hidden bg-white/20 text-white text-[10px] font-extrabold px-2.5 py-1 rounded-full uppercase tracking-wider border border-white/25 items-center gap-1 w-max">✨ Ditugaskan oleh AI</span>
                    <h3 id="detail-title" class="font-extrabold text-2xl leading-tight">Detail Tugas</h3>
                    <p id="detail-meta" class="text-white/70 text-xs font-medium">Mapel: - | Deadline: -</p>
                </div>
                <button onclick="closeTaskDetailModal()" class="text-white/80 hover:text-white transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Content Area (Scrollable) -->
            <div class="p-8 space-y-6" style="flex: 1; overflow-y: auto; max-height: 60vh;">
                <!-- Description -->
                <div>
                    <h4 class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2">Deskripsi Tugas</h4>
                    <p id="detail-description" class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed font-medium bg-gray-50 dark:bg-gray-700/30 p-4 rounded-xl border border-gray-100 dark:border-gray-700/50">Tidak ada deskripsi.</p>
                </div>

                <!-- AI Material Generator Section -->
                <div class="pt-4 border-t border-gray-100 dark:border-gray-700 space-y-4">
                    <div class="flex items-center justify-between">
                        <h4 class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest">Bahan Belajar AI Sinau</h4>
                        <span class="text-xs bg-purple-50 dark:bg-purple-950/30 text-purple-700 dark:text-purple-400 font-bold px-2 py-0.5 rounded">Asisten Guru</span>
                    </div>

                    <!-- State 1: Generate Button -->
                    <div id="ai-generator-init" class="p-5 rounded-2xl border text-center space-y-3" style="background-color: #FAF5FF; border-color: #E9D5FF;">
                        <span class="text-3xl block">📖</span>
                        <h5 class="font-bold text-sm text-purple-900 dark:text-purple-300">Siapkan Lembar Kerja & Latihan Soal</h5>
                        <p class="text-xs text-purple-700 dark:text-purple-400 max-w-md mx-auto">AI akan menganalisis judul tugas dan mata pelajaran ini untuk merancang rangkuman konsep, petunjuk belajar, serta 3 latihan soal khusus untuk Anda.</p>
                        <button onclick="triggerAiGeneration()" style="background-color: #B91C1C;" class="hover:opacity-95 text-white px-5 py-2.5 rounded-xl font-bold shadow-md transition-all text-xs border-b-4 border-[#450A0A] inline-flex items-center gap-1.5 mt-2">
                            ✨ Siapkan Tugas Sekarang (AI)
                        </button>
                    </div>

                    <!-- State 2: Loading Animation -->
                    <div id="ai-generator-loading" class="hidden p-8 text-center space-y-4">
                        <div class="w-12 h-12 rounded-full border-4 border-purple-100 animate-spin mx-auto" style="border-top-color: #7C3AED;"></div>
                        <p class="text-sm font-bold text-gray-700 dark:text-gray-300">Sinau AI sedang merancang bahan belajar...</p>
                        <p class="text-xs text-gray-400 max-w-sm mx-auto">Kami sedang menulis rangkuman konsep kunci dan merancang 3 soal latihan khusus untuk tugas ini.</p>
                    </div>

                    <!-- State 3: Content Display (Rendered Markdown) -->
                    <div id="ai-generator-result" class="hidden space-y-4">
                        <div class="bg-gray-50 dark:bg-gray-700/20 p-5 rounded-2xl border border-gray-200/50 dark:border-gray-700 prose prose-sm max-w-none dark:prose-invert">
                            <div id="ai-content-body" class="text-xs text-gray-750 dark:text-gray-300 space-y-4 leading-relaxed font-medium" style="white-space: pre-wrap;">
                                Bahan belajar akan tampil di sini.
                            </div>
                        </div>
                        <div class="text-right">
                            <button onclick="triggerAiGeneration(true)" class="inline-flex items-center gap-1 text-[11px] font-bold text-purple-750 hover:text-purple-900 transition bg-purple-50 px-3 py-1.5 rounded-lg border border-purple-100 dark:bg-purple-950/20 dark:text-purple-400 dark:border-purple-900/30">
                                🔄 Regenerasi Lembar Kerja (AI)
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer with update controls -->
            <div class="px-8 py-4 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center shrink-0" style="background-color: #F9FAFB;">
                <form id="delete-task-form" method="POST" action="">
                    @csrf @method('DELETE')
                    <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus tugas ini?')" class="text-xs font-bold text-red-600 hover:text-red-800 transition">
                        🗑️ Hapus Tugas
                    </button>
                </form>

                <div class="flex gap-2">
                    <button onclick="closeTaskDetailModal()" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-600 rounded-xl font-bold transition text-xs">
                        Tutup
                    </button>
                    {{-- Submit answer form — hanya tampil jika status = progress --}}
                    <div id="modal-submit-area" class="hidden">
                        <form id="modal-submit-form" method="POST" action="">
                            @csrf
                            <textarea id="modal-submission-text" name="submission" rows="2"
                                      required
                                      placeholder="Tulis jawabanmu di sini..."
                                      class="text-xs rounded-xl border border-red-200 focus:border-red-400 focus:ring-red-300 px-3 py-2 w-56 resize-none"></textarea>
                            <button type="submit" style="background-color:#B91C1C;" class="ml-2 px-4 py-2 text-white rounded-xl font-bold transition text-xs border-b-2 border-[#450A0A] hover:opacity-90 inline-flex items-center gap-1">
                                📤 Submit Jawaban
                            </button>
                        </form>
                    </div>
                    {{-- Tampil jika sudah selesai / done --}}
                    <form id="mark-done-form" method="POST" action="" class="hidden">
                        @csrf @method('PUT')
                        <input type="hidden" name="status" value="done">
                        <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-xl font-bold transition text-xs flex items-center gap-1">
                            ✓ Tandai Selesai
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <script>
        let activeTaskId = null;

        // Attach click listeners to all Task Cards on the Kanban board
        document.querySelectorAll('.task-card').forEach(card => {
            card.addEventListener('click', function(e) {
                // Prevent modal opening when clicking action forms/buttons inside the submit area
                if (e.target.closest('.submit-area') || e.target.closest('button') || e.target.closest('a')) {
                    return;
                }

                const id         = this.getAttribute('data-id');
                const title      = this.getAttribute('data-title');
                const desc       = this.getAttribute('data-description');
                const subject    = this.getAttribute('data-subject');
                const priority   = this.getAttribute('data-priority');
                const due        = this.getAttribute('data-due');
                const status     = this.getAttribute('data-status');
                const isAi       = this.getAttribute('data-is-ai') === '1';
                const submission = this.getAttribute('data-submission') || '';

                activeTaskId = id;

                // Populate Fields
                document.getElementById('detail-title').innerText = title;
                document.getElementById('detail-description').innerText = desc || 'Tidak ada deskripsi tambahan.';

                let priorityLabel = priority === 'high' ? 'Penting' : (priority === 'medium' ? 'Sedang' : 'Rendah');
                document.getElementById('detail-meta').innerText = `Mapel: ${subject || 'Umum'} | Prioritas: ${priorityLabel} | Batas Waktu: ${due || 'Tanpa Deadline'}`;

                // Toggle AI badge
                const badge = document.getElementById('detail-badge-is-ai');
                if (isAi) {
                    badge.classList.remove('hidden');
                    badge.classList.add('inline-flex');
                } else {
                    badge.classList.add('hidden');
                    badge.classList.remove('inline-flex');
                }

                // Set Action Form URLs
                document.getElementById('delete-task-form').action = `/tasks/${id}`;

                // Modal footer: tampilkan form submit jika status = progress
                const submitArea   = document.getElementById('modal-submit-area');
                const submitForm   = document.getElementById('modal-submit-form');
                const markDoneForm = document.getElementById('mark-done-form');

                if (status === 'progress') {
                    submitArea.classList.remove('hidden');
                    markDoneForm.classList.add('hidden');
                    submitForm.action = `/tasks/${id}/submit`;
                    document.getElementById('modal-submission-text').value = '';
                } else if (status === 'todo') {
                    submitArea.classList.add('hidden');
                    markDoneForm.classList.remove('hidden');
                    markDoneForm.action = `/tasks/${id}`;
                } else {
                    // done
                    submitArea.classList.add('hidden');
                    markDoneForm.classList.add('hidden');
                }

                // Reset AI interface
                resetAiSection();

                // Open Modal
                const modal  = document.getElementById('taskDetailModal');
                const cardEl = document.getElementById('taskDetailModalCard');
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                setTimeout(() => {
                    cardEl.classList.remove('scale-95');
                    cardEl.classList.add('scale-100');
                }, 10);

                // Check for existing AI content
                checkExistingAiContent(id);
            });
        });

        function closeTaskDetailModal() {
            const modal = document.getElementById('taskDetailModal');
            const cardEl = document.getElementById('taskDetailModalCard');
            cardEl.classList.remove('scale-100');
            cardEl.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.replace('flex', 'hidden');
            }, 150);
        }

        function resetAiSection() {
            document.getElementById('ai-generator-init').classList.remove('hidden');
            document.getElementById('ai-generator-loading').classList.add('hidden');
            document.getElementById('ai-generator-result').classList.add('hidden');
            document.getElementById('ai-content-body').innerHTML = '';
        }

        async function checkExistingAiContent(taskId) {
            try {
                const response = await fetch(`/tasks/${taskId}/generate-materials`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });
                const data = await response.json();
                if (data.success && data.ai_content) {
                    showAiContent(data.ai_content);
                }
            } catch (err) {
                console.error("Error loading task AI content:", err);
            }
        }

        function showAiContent(content) {
            document.getElementById('ai-generator-init').classList.add('hidden');
            document.getElementById('ai-generator-loading').classList.add('hidden');
            document.getElementById('ai-generator-result').classList.remove('hidden');
            document.getElementById('ai-content-body').innerHTML = formatMarkdown(content);
        }

        async function triggerAiGeneration(isRegenerate = false) {
            if (!activeTaskId) return;

            document.getElementById('ai-generator-init').classList.add('hidden');
            document.getElementById('ai-generator-result').classList.add('hidden');
            document.getElementById('ai-generator-loading').classList.remove('hidden');

            try {
                const response = await fetch(`/tasks/${activeTaskId}/generate-materials`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ regenerate: isRegenerate })
                });
                const data = await response.json();

                if (!response.ok || !data.success) {
                    throw new Error(data.error || 'Gagal merancang tugas oleh AI.');
                }

                showAiContent(data.ai_content);

            } catch (err) {
                alert(err.message);
                resetAiSection();
            }
        }

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

            // Convert Spoiler Blocks (collapsible answer key)
            escaped = escaped.replace(/:::spoiler\r?\n([\s\S]*?)\r?\n:::/g, 
                '<details class="my-4 text-xs bg-purple-50 dark:bg-purple-950/20 p-4 rounded-xl border border-purple-100 dark:border-purple-900/40 cursor-pointer"><summary class="font-bold text-purple-700 dark:text-purple-400 select-none outline-none flex items-center gap-1.5">🔑 Lihat Kunci Jawaban & Pembahasan</summary><div class="mt-3 pl-2 text-gray-700 dark:text-gray-300 pointer-events-auto cursor-default">$1</div></details>');

            // Convert Headings
            escaped = escaped.replace(/^### (.*$)/gim, '<h6 class="font-extrabold text-sm text-purple-900 dark:text-purple-400 mt-5 mb-2">$1</h6>');
            escaped = escaped.replace(/^## (.*$)/gim, '<h5 class="font-bold text-md text-purple-950 dark:text-purple-300 mt-5 mb-2">$1</h5>');
            escaped = escaped.replace(/^# (.*$)/gim, '<h4 class="font-black text-lg text-red-700 dark:text-red-500 mt-5 mb-2">$1</h4>');

            // Convert Bold
            escaped = escaped.replace(/\*\*(.*?)\*\*/g, '<strong class="font-bold text-gray-900 dark:text-white">$1</strong>');
            
            // Convert Bullet lists (supports both '-' and '*')
            escaped = escaped.replace(/^\s*[-*]\s+(.*$)/gim, '<li class="ml-5 list-disc my-1">$1</li>');

            return escaped;
        }
    </script>
</x-app-layout>
