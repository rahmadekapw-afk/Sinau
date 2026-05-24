<div x-show="mobileMenuOpen" 
     class="fixed inset-0 bg-gray-900/50 z-40 md:hidden" 
     @click="mobileMenuOpen = false"
     x-transition:enter="transition-opacity ease-linear duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-linear duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"></div>

<aside class="bg-white border-r border-gray-200 flex flex-col fixed inset-y-0 left-0 z-50 transition-all duration-300 transform md:translate-x-0"
       :class="[
           mobileMenuOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0',
           sidebarCollapsed ? 'w-20' : 'w-64'
       ]">
    
    <!-- Header Section -->
    <div class="flex items-center h-20 border-b border-gray-100 shrink-0 bg-white transition-all duration-300"
         :class="sidebarCollapsed ? 'px-4 justify-center' : 'px-6 justify-between'">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 font-bold text-3xl text-[#B91C1C] overflow-hidden shrink-0">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-12 h-12 object-contain shrink-0" style="filter: invert(15%) sepia(95%) saturate(3000%) hue-rotate(350deg) brightness(85%) contrast(100%);">
            <span x-show="!sidebarCollapsed" x-transition.opacity.duration.300 class="font-bold text-3xl text-[#B91C1C]">Sinau</span>
        </a>
        <button @click="mobileMenuOpen = false" class="md:hidden p-2 rounded-lg text-gray-400 hover:bg-gray-50 shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <!-- Toggle Collapse Button (Desktop Only) -->
        <button @click="sidebarCollapsed = !sidebarCollapsed; localStorage.setItem('sidebar_collapsed', sidebarCollapsed)" 
                class="hidden md:flex p-2 rounded-lg text-gray-400 hover:bg-red-50 hover:text-[#B91C1C] transition-colors shrink-0">
            <svg x-show="!sidebarCollapsed" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
            </svg>
            <svg x-show="sidebarCollapsed" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/>
            </svg>
        </button>
    </div>

    <!-- Navigation Links -->
    <div class="flex-1 overflow-y-auto bg-white py-6">
        <nav class="px-4 space-y-1 transition-all duration-300" :class="sidebarCollapsed ? 'px-2' : 'px-4'">
            <div x-show="!sidebarCollapsed" x-transition.opacity.duration.300 class="text-xs font-bold text-gray-400 uppercase tracking-[0.2em] mb-4 px-3 whitespace-nowrap">Menu Utama</div>
            
            @php
                $navItems = [
                    ['route' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                    ['route' => 'materials.index', 'label' => 'Materi', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                    ['route' => 'tasks.index', 'label' => 'Tasks', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4'],
                    ['route' => 'calendar.index', 'label' => 'Calendar', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                    ['route' => 'analytics.index', 'label' => 'Analytics', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                    ['route' => 'import.index', 'label' => 'Import Data', 'icon' => 'M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12'],
                    ['route' => 'billing.index', 'label' => 'Billing', 'icon' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z'],
                    ['route' => 'profile.edit', 'label' => 'Profile', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                    ['route' => 'support.index', 'label' => 'Support', 'icon' => 'M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z'],
                ];
            @endphp

            @foreach($navItems as $item)
            <a href="{{ route($item['route']) }}" 
               class="flex items-center gap-3 py-2.5 rounded-xl transition-all duration-200 font-bold text-sm shrink-0 overflow-hidden {{ request()->routeIs($item['route']) ? 'bg-[#B91C1C] text-white shadow-md shadow-[#B91C1C]/20' : 'text-gray-500 hover:bg-red-50 hover:text-[#B91C1C]' }}"
               :class="sidebarCollapsed ? 'justify-center px-2' : 'px-4'"
               title="{{ $item['label'] }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/></svg>
                <span x-show="!sidebarCollapsed" x-transition.opacity.duration.300 class="whitespace-nowrap">{{ $item['label'] }}</span>
            </a>
            @endforeach
        </nav>
    </div>

    <!-- Logout -->
    <div class="border-t border-gray-100 bg-white transition-all duration-300"
         :class="sidebarCollapsed ? 'p-2' : 'p-4'">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" 
                    class="w-full flex items-center gap-3 py-2.5 rounded-xl text-[#B91C1C] hover:bg-red-50 font-bold transition shrink-0 overflow-hidden"
                    :class="sidebarCollapsed ? 'justify-center px-2' : 'px-4'">
                <svg class="w-5 h-5 text-[#B91C1C] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                <span x-show="!sidebarCollapsed" x-transition.opacity.duration.300 class="whitespace-nowrap">Logout</span>
            </button>
        </form>
    </div>
</aside>

<!-- Desktop Spacer -->
<div class="hidden md:block shrink-0 transition-all duration-300"
     :class="sidebarCollapsed ? 'w-20' : 'w-64'"></div>
