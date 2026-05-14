@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-slate-200 focus:border-accent focus:ring-accent rounded-xl shadow-sm px-4 py-3.5 text-sm transition duration-200 ease-in-out hover:border-slate-300 bg-slate-50 focus:bg-white w-full']) }}>
