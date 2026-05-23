<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">🎓 Pilih Kelas</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div style="background: linear-gradient(135deg, #B91C1C 0%, #EF4444 100%);" class="p-8 text-white text-center">
                    <div class="text-5xl mb-3">🎓</div>
                    <h1 class="text-2xl font-bold">Pilih Kelasmu</h1>
                    <p class="text-white/80 mt-2">Kami akan menyiapkan materi belajar yang sesuai</p>
                </div>

                <form method="POST" action="{{ route('materials.simpan-kelas') }}" class="p-8">
                    @csrf

                    <div class="space-y-3">
                        {{-- SMP --}}
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mt-2 mb-3">SMP</p>
                        @foreach(['7-smp' => 'Kelas 7 SMP', '8-smp' => 'Kelas 8 SMP', '9-smp' => 'Kelas 9 SMP'] as $code => $label)
                        <label class="flex items-center gap-4 p-4 rounded-xl border-2 cursor-pointer transition-all
                            {{ auth()->user()->kelas === $code ? 'border-[#B91C1C] bg-red-50' : 'border-gray-100 hover:border-[#B91C1C]/30 hover:bg-gray-50' }}">
                            <input type="radio" name="kelas" value="{{ $code }}" class="text-[#B91C1C] focus:ring-[#B91C1C]"
                                {{ auth()->user()->kelas === $code ? 'checked' : '' }}>
                            <div>
                                <p class="font-bold text-gray-800">{{ $label }}</p>
                                <p class="text-xs text-gray-500">Sekolah Menengah Pertama</p>
                            </div>
                        </label>
                        @endforeach

                        {{-- SMA --}}
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mt-6 mb-3">SMA</p>
                        @foreach(['10-sma' => 'Kelas 10 SMA', '11-sma' => 'Kelas 11 SMA', '12-sma' => 'Kelas 12 SMA'] as $code => $label)
                        <label class="flex items-center gap-4 p-4 rounded-xl border-2 cursor-pointer transition-all
                            {{ auth()->user()->kelas === $code ? 'border-[#B91C1C] bg-red-50' : 'border-gray-100 hover:border-[#B91C1C]/30 hover:bg-gray-50' }}">
                            <input type="radio" name="kelas" value="{{ $code }}" class="text-[#B91C1C] focus:ring-[#B91C1C]"
                                {{ auth()->user()->kelas === $code ? 'checked' : '' }}>
                            <div>
                                <p class="font-bold text-gray-800">{{ $label }}</p>
                                <p class="text-xs text-gray-500">Sekolah Menengah Atas</p>
                            </div>
                        </label>
                        @endforeach
                    </div>

                    @error('kelas')
                        <p class="text-red-500 text-sm mt-3">{{ $message }}</p>
                    @enderror

                    <button type="submit" class="w-full mt-8 px-6 py-3 bg-[#B91C1C] text-white rounded-xl font-bold hover:opacity-90 transition shadow-md border-b-4 border-[#991B1B]">
                        ✅ Simpan & Lihat Materi
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
