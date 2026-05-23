<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $subject->icon }} {{ $subject->name }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Breadcrumb --}}
            <div class="flex items-center gap-2 text-sm text-gray-500">
                <a href="{{ route('materials.index') }}" class="hover:text-[#B91C1C] transition font-medium">📚 Materi</a>
                <span>→</span>
                <span class="text-gray-800 font-bold">{{ $subject->name }}</span>
            </div>

            {{-- Subject Header --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-[#B91C1C]/10 rounded-2xl flex items-center justify-center text-4xl">{{ $subject->icon }}</div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">{{ $subject->name }}</h1>
                        <p class="text-gray-500 mt-1">{{ $subject->description }}</p>
                        <span class="text-xs bg-[#B91C1C]/10 text-[#B91C1C] px-3 py-1 rounded-full font-bold mt-2 inline-block">
                            🎓 {{ \App\Models\Subject::kelasLabel($subject->kelas) }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Chapters List --}}
            <div class="space-y-3">
                @foreach($materials as $idx => $material)
                <a href="{{ route('materials.show', $material) }}"
                   class="group flex items-center gap-4 bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-lg hover:border-[#B91C1C]/20 transition-all duration-300">
                    <div class="w-10 h-10 rounded-xl bg-[#B91C1C] text-white flex items-center justify-center font-bold text-sm shrink-0">
                        {{ $idx + 1 }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-bold text-gray-800 group-hover:text-[#B91C1C] transition">{{ $material->title }}</h3>
                        <p class="text-sm text-gray-500 mt-0.5 line-clamp-1">{{ $material->description }}</p>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <span class="text-xs px-2.5 py-1 rounded-full font-medium {{ $material->difficulty_class }}">
                            {{ $material->difficulty_label }}
                        </span>
                        <span class="text-xs text-gray-400">⏱️ {{ $material->estimated_minutes }}m</span>
                        <svg class="w-5 h-5 text-gray-300 group-hover:text-[#B91C1C] transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </a>
                @endforeach
            </div>

        </div>
    </div>
</x-app-layout>
