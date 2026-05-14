<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                📜 Riwayat Chat
            </h2>
            <a href="{{ route('chat.new') }}"
               class="inline-flex items-center px-4 py-2 bg-accent text-white text-sm rounded-lg hover:bg-primary transition shadow-md shadow-accent/20">
                ✨ Chat Baru
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl overflow-hidden">

                @if($sessionData->isEmpty())
                    <div class="flex flex-col items-center justify-center py-20 text-center">
                        <div class="text-5xl mb-4">💬</div>
                        <h3 class="text-lg font-semibold text-gray-600 dark:text-gray-300">Belum ada riwayat chat</h3>
                        <p class="text-sm text-gray-400 mt-1">Mulai chat baru untuk memulai belajar!</p>
                        <a href="{{ route('chat.index') }}" class="mt-4 px-6 py-2 bg-accent text-white rounded-lg hover:bg-primary transition text-sm shadow-md shadow-accent/20">
                            Mulai Belajar
                        </a>
                    </div>
                @else
                    <ul class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($sessionData as $session)
                        <li>
                            <a href="{{ route('chat.load', $session['session_id']) }}"
                               class="flex items-center gap-4 p-5 hover:bg-accent/5 dark:hover:bg-gray-700 transition group">
                                <div class="flex-shrink-0 w-10 h-10 bg-accent rounded-full flex items-center justify-center text-white">
                                    💬
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-800 dark:text-gray-100 truncate group-hover:text-accent">
                                        {{ $session['preview'] }}
                                    </p>
                                    <p class="text-xs text-gray-400 mt-0.5">
                                        {{ \Carbon\Carbon::parse($session['created_at'])->diffForHumans() }}
                                    </p>
                                </div>
                                <svg class="w-4 h-4 text-gray-400 group-hover:text-accent transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
