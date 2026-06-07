<button
    {{ $attributes->merge([
        'type' => 'button',
        'class' => 'relative inline-flex items-center justify-center p-2.5 rounded-xl border border-slate-200/50 dark:border-gray-700/50 bg-white/60 dark:bg-gray-800/60 backdrop-blur-md text-slate-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 hover:scale-105 active:scale-95 transition-all duration-300 shadow-sm hover:shadow-md cursor-pointer focus:outline-none focus:ring-2 focus:ring-indigo-500/20'
    ]) }}
    @click="$store.theme.toggle()"
    aria-label="Toggle theme"
>
    <!-- Sun icon for light mode (visible when theme is dark) -->
    <svg x-show="$store.theme.value === 'dark'" class="w-5.5 h-5.5 transition-transform duration-500 rotate-0 hover:rotate-90 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display: none;">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m12.728 12.728l.707.707M12 8a4 4 0 100 8 4 4 0 000-8z" />
    </svg>
    <!-- Moon icon for dark mode (visible when theme is light) -->
    <svg x-show="$store.theme.value === 'light'" class="w-5.5 h-5.5 transition-transform duration-500 rotate-0 hover:-rotate-12 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display: none;">
        <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
    </svg>
</button>
