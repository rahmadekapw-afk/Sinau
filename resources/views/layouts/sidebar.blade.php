<aside class="w-64 bg-white border-r border-gray-100 hidden md:flex flex-col flex-shrink-0 relative z-40">
    <div class="flex flex-col h-screen sticky top-0">
        <!-- Logo -->
        <div class="flex items-center h-20 border-b border-gray-100 px-6 shrink-0">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 font-bold text-2xl text-primary">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-12 h-12 object-contain" style="filter: invert(15%) sepia(95%) saturate(3000%) hue-rotate(350deg) brightness(85%) contrast(100%);">
                <span>Sinau</span>
            </a>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 px-4 py-6 text-sm font-medium space-y-1 overflow-y-auto">
            <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3 px-3">Menu Utama</div>
            
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('dashboard') ? 'bg-accent/10 text-accent' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} transition">
                <svg class="w-5 h-5 {{ request()->routeIs('dashboard') ? 'text-accent' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Dashboard
            </a>

            <a href="{{ route('tasks.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('tasks.*') ? 'bg-accent/10 text-accent' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} transition">
                <svg class="w-5 h-5 {{ request()->routeIs('tasks.*') ? 'text-accent' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                Tasks
            </a>

            <a href="{{ route('calendar.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('calendar.*') ? 'bg-accent/10 text-accent' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} transition">
                <svg class="w-5 h-5 {{ request()->routeIs('calendar.*') ? 'text-accent' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Calendar
            </a>

            @auth
            @if(request()->routeIs('calendar.*'))
                @php
                    $sidebarUpcoming = \App\Models\Task::forUser(auth()->id())
                        ->upcoming()
                        ->limit(3)
                        ->get();
                @endphp
                @if($sidebarUpcoming->count() > 0)
                    <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 px-3 mt-6">📌 Segera Datang</div>
                    @foreach($sidebarUpcoming as $task)
                        <div class="flex items-center gap-2.5 px-3 py-2 rounded-xl bg-gray-50 mb-1">
                            <div class="w-1.5 h-6 rounded-full {{ $task->bar_class }} shrink-0"></div>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-semibold text-gray-700 truncate">{{ $task->title }}</p>
                                <p class="text-[10px] text-gray-400 font-medium">
                                    @if($task->due_date->isToday()) <span class="text-red-500">Hari ini</span>
                                    @elseif($task->due_date->isTomorrow()) <span class="text-yellow-600">Besok</span>
                                    @else {{ $task->due_date->diffForHumans() }}
                                    @endif
                                </p>
                            </div>
                        </div>
                    @endforeach
                @endif
            @endif
            @endauth

            <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3 px-3 mt-8">Alat Produktivitas</div>

            <a href="{{ route('analytics.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('analytics.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} transition">
                <svg class="w-5 h-5 {{ request()->routeIs('analytics.*') ? 'text-indigo-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                Generate Analytics
            </a>

            <a href="{{ route('import.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('import.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} transition">
                <svg class="w-5 h-5 {{ request()->routeIs('import.*') ? 'text-indigo-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                Import Data Cerdas
            </a>

            <a href="{{ route('billing.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('billing.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} transition">
                <svg class="w-5 h-5 {{ request()->routeIs('billing.*') ? 'text-indigo-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                Billing
            </a>

            <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3 px-3 mt-8">Preferences</div>

            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('profile.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} transition">
                <svg class="w-5 h-5 {{ request()->routeIs('profile.*') ? 'text-indigo-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Profile
            </a>

            <a href="{{ route('settings.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('settings.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} transition">
                <svg class="w-5 h-5 {{ request()->routeIs('settings.*') ? 'text-indigo-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Settings
            </a>

            <a href="{{ route('support.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('support.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} transition">
                <svg class="w-5 h-5 {{ request()->routeIs('support.*') ? 'text-indigo-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                Support
            </a>

        </nav>

        <!-- Logout Bottom Menu -->
        <div class="p-4 border-t border-gray-100 shrink-0">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-red-600 hover:bg-red-50 hover:text-red-700 font-medium transition text-left">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Logout
                </button>
            </form>
        </div>
    </div>
</aside>
