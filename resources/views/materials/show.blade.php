<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $material->subject->icon }} {{ $material->title }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Breadcrumb --}}
            <div class="flex items-center gap-2 text-sm text-gray-500 flex-wrap">
                <a href="{{ route('materials.index') }}" class="hover:text-[#B91C1C] transition font-medium">📚 Materi</a>
                <span>→</span>
                <a href="{{ route('materials.subject', $material->subject) }}" class="hover:text-[#B91C1C] transition font-medium">{{ $material->subject->name }}</a>
                <span>→</span>
                <span class="text-gray-800 font-bold">Bab {{ $material->chapter_order }}</span>
            </div>

            {{-- Material Content Card --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                {{-- Header --}}
                <div style="background: linear-gradient(135deg, #B91C1C 0%, #EF4444 100%);" class="p-6 text-white">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-xs px-2.5 py-1 rounded-full font-medium bg-white/20">Bab {{ $material->chapter_order }}</span>
                        <span class="text-xs px-2.5 py-1 rounded-full font-medium bg-white/20">{{ $material->difficulty_label }}</span>
                        <span class="text-xs px-2.5 py-1 rounded-full font-medium bg-white/20">⏱️ {{ $material->estimated_minutes }} menit</span>
                    </div>
                    <h1 class="text-2xl font-bold">{{ $material->title }}</h1>
                    <p class="text-white/80 mt-1">{{ $material->description }}</p>
                </div>

                {{-- Content --}}
                <div class="p-6 md:p-8 prose prose-sm max-w-none
                    prose-headings:text-gray-800 prose-headings:font-bold
                    prose-h2:text-xl prose-h2:mt-8 prose-h2:mb-4 prose-h2:pb-2 prose-h2:border-b prose-h2:border-gray-100
                    prose-h3:text-lg prose-h3:mt-6 prose-h3:mb-3
                    prose-p:text-gray-600 prose-p:leading-relaxed
                    prose-strong:text-gray-800
                    prose-li:text-gray-600
                    prose-table:text-sm
                    prose-th:bg-gray-50 prose-th:px-4 prose-th:py-2
                    prose-td:px-4 prose-td:py-2 prose-td:border-t
                    prose-code:text-[#B91C1C] prose-code:bg-red-50 prose-code:px-1.5 prose-code:py-0.5 prose-code:rounded prose-code:text-sm">
                    {!! \Illuminate\Support\Str::markdown($material->content) !!}
                </div>
            </div>

            {{-- Navigation --}}
            <div class="flex items-center justify-between">
                @if($prev)
                <a href="{{ route('materials.show', $prev) }}" class="flex items-center gap-2 px-5 py-3 bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md hover:border-[#B91C1C]/20 transition-all font-medium text-gray-700 hover:text-[#B91C1C]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    {{ \Illuminate\Support\Str::limit($prev->title, 30) }}
                </a>
                @else <div></div> @endif

                @if($next)
                <a href="{{ route('materials.show', $next) }}" class="flex items-center gap-2 px-5 py-3 bg-[#B91C1C] text-white rounded-xl shadow-md hover:opacity-90 transition-all font-bold">
                    {{ \Illuminate\Support\Str::limit($next->title, 30) }}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
                @else <div></div> @endif
            </div>

            {{-- CTA: Ask AI --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 text-center">
                <p class="text-gray-600 mb-3">Belum paham? Tanyakan ke AI! 🤖</p>
                <a href="{{ route('chat.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-[#B91C1C] text-white rounded-xl font-bold hover:opacity-90 transition shadow-md">
                    💬 Tanya AI tentang {{ $material->title }}
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
