<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center w-full px-6 py-4 bg-accent border border-transparent rounded-xl font-bold text-base text-white tracking-wide hover:opacity-90 hover:shadow-xl focus:opacity-90 active:opacity-100 focus:outline-none focus:ring-2 focus:ring-accent focus:ring-offset-2 transition-all ease-in-out duration-200 transform hover:-translate-y-0.5 shadow-lg shadow-accent/20']) }}>
    {{ $slot }}
</button>
