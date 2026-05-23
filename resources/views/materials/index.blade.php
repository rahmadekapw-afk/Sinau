<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">📚 Materi Belajar</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Kelas Badge --}}
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Mata Pelajaran</h1>
                    <p class="text-gray-500 text-sm mt-1">Pilih mata pelajaran untuk mulai belajar</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#B91C1C]/10 text-[#B91C1C] font-bold text-sm">
                        🎓 {{ \App\Models\Subject::kelasLabel($kelas) }}
                    </span>
                    <a href="{{ route('materials.pilih-kelas') }}" class="text-xs text-gray-400 hover:text-[#B91C1C] font-medium transition">Ubah Kelas</a>
                </div>
            </div>

            {{-- Subject Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($subjects as $subject)
                <a href="{{ route('materials.subject', $subject) }}"
                   class="group bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    <div class="flex items-start gap-4">
                        <div class="w-14 h-14 bg-[#B91C1C]/10 rounded-xl flex items-center justify-center text-3xl shrink-0">
                            {{ $subject->icon }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-bold text-gray-800 text-lg group-hover:text-[#B91C1C] transition">{{ $subject->name }}</h3>
                            <p class="text-sm text-gray-500 mt-1 line-clamp-2">{{ $subject->description }}</p>
                            <div class="flex items-center gap-3 mt-3">
                                <span class="text-xs bg-gray-100 text-gray-600 px-2.5 py-1 rounded-full font-medium">
                                    📖 {{ $subject->materials_count }} Bab
                                </span>
                                @if($subject->total_minutes)
                                <span class="text-xs bg-gray-100 text-gray-600 px-2.5 py-1 rounded-full font-medium">
                                    ⏱️ {{ $subject->total_minutes }} menit
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>

            @if($subjects->isEmpty())
            <div class="bg-white rounded-2xl p-12 text-center shadow-sm border border-gray-100">
                <div class="text-5xl mb-4">📭</div>
                <h3 class="font-bold text-gray-800 text-lg">Belum ada materi untuk kelas ini</h3>
                <p class="text-gray-500 mt-2">Materi sedang disiapkan. Coba pilih kelas lain.</p>
                <a href="{{ route('materials.pilih-kelas') }}" class="mt-4 inline-block px-6 py-2.5 bg-[#B91C1C] text-white rounded-xl font-bold hover:opacity-90 transition">
                    Pilih Kelas Lain
                </a>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>
