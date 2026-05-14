<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
            Pengaturan Profil <span class="text-3xl">⚙️</span>
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-8 items-start">
                
                {{-- Sidebar Profile Overview --}}
                <div class="w-full lg:w-1/3 sticky top-8">
                    <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 flex flex-col items-center text-center relative overflow-hidden">
                        {{-- Background Accent --}}
                        <div class="absolute top-0 left-0 w-full h-32 bg-primary/10"></div>
                        
                        <div class="w-32 h-32 bg-accent rounded-full flex items-center justify-center text-5xl text-white font-black shadow-xl shadow-accent/20 mb-6 relative z-10 border-4 border-white">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            <div class="absolute bottom-1 right-1 w-6 h-6 bg-green-500 border-4 border-white rounded-full"></div>
                        </div>
                        
                        <h3 class="text-2xl font-bold text-gray-800 relative z-10">{{ Auth::user()->name }}</h3>
                        <p class="text-gray-500 text-sm mb-8 relative z-10">{{ Auth::user()->email }}</p>
                        
                        <div class="w-full border-t border-gray-100 pt-6">
                            <div class="flex justify-between items-center text-sm mb-4 bg-gray-50 p-3 rounded-xl">
                                <span class="text-gray-500 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Tugas Selesai
                                </span>
                                <span class="font-bold text-gray-800 text-lg">{{ \App\Models\Task::where('user_id', Auth::id())->where('status', 'done')->count() }}</span>
                            </div>
                            <div class="flex justify-between items-center text-sm bg-accent/5 p-3 rounded-xl">
                                <span class="text-gray-500 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                    Status Akun
                                </span>
                                <span class="bg-accent text-white px-3 py-1 rounded-lg font-bold text-xs uppercase tracking-wider">Student</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Forms Column --}}
                <div class="w-full lg:w-2/3 space-y-8">
                    
                    {{-- Profile Info Card --}}
                    <div class="bg-white p-8 shadow-sm border border-gray-100 rounded-3xl relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-accent/5 rounded-full blur-3xl opacity-50 -mr-20 -mt-20 pointer-events-none"></div>
                        <div class="relative z-10 max-w-xl">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>

                    {{-- Password Card --}}
                    <div class="bg-white p-8 shadow-sm border border-gray-100 rounded-3xl relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-primary/5 rounded-full blur-3xl opacity-50 -mr-20 -mt-20 pointer-events-none"></div>
                        <div class="relative z-10 max-w-xl">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>

                    {{-- Delete Account Card --}}
                    <div class="bg-red-50 p-8 shadow-sm border border-red-100 rounded-3xl relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-red-100 rounded-full blur-3xl opacity-30 -mr-20 -mt-20 pointer-events-none"></div>
                        <div class="relative z-10 max-w-xl">
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>

                </div>
                
            </div>
        </div>
    </div>
</x-app-layout>
