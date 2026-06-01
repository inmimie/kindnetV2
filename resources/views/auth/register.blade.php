<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Register - KINDNET</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|outfit:400,600,700,800,900&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body { font-family: 'Inter', sans-serif; }
            h1, h2, h3, h4, h5, .font-heading { font-family: 'Outfit', sans-serif; }
            
            .card-glass {
                background: rgba(255, 255, 255, 0.85);
                backdrop-filter: blur(16px);
                -webkit-backdrop-filter: blur(16px);
                border: 1px solid rgba(255, 255, 255, 0.5);
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
            }
            .dark .card-glass {
                background: rgba(31, 41, 55, 0.75);
                border: 1px solid rgba(255, 255, 255, 0.1);
            }

            .animate-blob { animation: blob 8s infinite alternate; }
            .animation-delay-2000 { animation-delay: 2s; }
            .animation-delay-4000 { animation-delay: 4s; }
            
            @keyframes blob {
                0% { transform: translate(0px, 0px) scale(1); }
                33% { transform: translate(40px, -50px) scale(1.1); }
                66% { transform: translate(-30px, 30px) scale(0.9); }
                100% { transform: translate(0px, 0px) scale(1); }
            }
        </style>
    </head>
    <body class="antialiased bg-slate-50 dark:bg-gray-900 text-slate-800 dark:text-gray-200 relative min-h-screen flex items-center justify-center selection:bg-indigo-500 selection:text-white py-12">

        <!-- Background Effect -->
        <div class="fixed inset-0 z-[-1] overflow-hidden pointer-events-none">
            <div class="absolute top-1/4 left-1/4 w-[30rem] h-[30rem] bg-purple-300 dark:bg-purple-900/40 rounded-full mix-blend-multiply dark:mix-blend-lighten filter blur-3xl opacity-60 animate-blob"></div>
            <div class="absolute top-1/3 right-1/4 w-[30rem] h-[30rem] bg-indigo-300 dark:bg-indigo-900/40 rounded-full mix-blend-multiply dark:mix-blend-lighten filter blur-3xl opacity-60 animate-blob animation-delay-2000"></div>
            <div class="absolute -bottom-8 left-1/3 w-[30rem] h-[30rem] bg-pink-300 dark:bg-pink-900/40 rounded-full mix-blend-multiply dark:mix-blend-lighten filter blur-3xl opacity-60 animate-blob animation-delay-4000"></div>
        </div>

        <div class="w-full max-w-lg px-6 relative z-10">
            <!-- Logo -->
            <div class="flex flex-col items-center justify-center mb-8">
                <a href="/" class="group flex flex-col items-center gap-3">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center text-white font-heading font-black text-3xl shadow-lg shadow-indigo-500/30 group-hover:scale-105 transition-transform duration-300">
                        KN
                    </div>
                    <span class="font-heading font-bold text-3xl tracking-tight text-slate-900 dark:text-white mt-1">KIND<span class="text-indigo-600 dark:text-indigo-400">NET</span></span>
                </a>
                <p class="mt-2 text-sm text-slate-600 dark:text-gray-400 font-medium text-center">Create an account to start your financial aid application.</p>
            </div>

            <!-- Register Card -->
            <div class="card-glass rounded-3xl p-8 sm:p-10 relative overflow-hidden">

                @if($errors->any())
                    <div class="mb-6 p-4 rounded-xl bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-300 border border-red-200 dark:border-red-800 text-sm font-medium">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Name -->
                    <div class="mb-5">
                        <label for="name" class="block text-sm font-bold text-slate-700 dark:text-gray-300 mb-2">Full Name (as per IC) *</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-gray-700 bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors shadow-sm dark:text-white" placeholder="Your full name">
                    </div>

                    <!-- Email Address -->
                    <div class="mb-5">
                        <label for="email" class="block text-sm font-bold text-slate-700 dark:text-gray-300 mb-2">Email Address *</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-gray-700 bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors shadow-sm dark:text-white" placeholder="you@example.com">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-6">
                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-bold text-slate-700 dark:text-gray-300 mb-2">Password *</label>
                            <div class="relative">
                                <input id="password" type="password" name="password" required autocomplete="new-password" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-gray-700 bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors shadow-sm dark:text-white pr-12" placeholder="••••••••">
                                <button type="button" onclick="togglePassword('password', this)" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-indigo-600 transition-colors focus:outline-none">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                </button>
                            </div>
                            <p class="text-xs text-slate-500 mt-1">Min. 8 characters</p>
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-bold text-slate-700 dark:text-gray-300 mb-2">Confirm Password *</label>
                            <div class="relative">
                                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-gray-700 bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors shadow-sm dark:text-white pr-12" placeholder="••••••••">
                                <button type="button" onclick="togglePassword('password_confirmation', this)" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-indigo-600 transition-colors focus:outline-none">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Terms -->
                    <div class="block mb-8">
                        <label class="inline-flex flex-start cursor-pointer items-start">
                            <input type="checkbox" required class="mt-1 w-5 h-5 rounded border border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-700">
                            <span class="ml-3 text-sm font-medium text-slate-600 dark:text-gray-400">
                                I agree to the <a href="#" class="text-indigo-600 dark:text-indigo-400 hover:underline">Terms & Conditions</a> and <a href="#" class="text-indigo-600 dark:text-indigo-400 hover:underline">Privacy Policy</a>.
                            </span>
                        </label>
                    </div>

                    <div>
                        <button type="submit" class="w-full flex justify-center py-3.5 px-4 rounded-xl text-base font-bold text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-lg shadow-indigo-500/30 transform hover:-translate-y-0.5 transition-all duration-200">
                            Create Account
                        </button>
                    </div>
                </form>

                <div class="mt-8 text-center border-t border-slate-200 dark:border-gray-700/50 pt-6">
                    <p class="text-sm text-slate-600 dark:text-gray-400 font-medium">
                        Already have an account?
                        <a href="{{ route('login') }}" class="font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 transition-colors">Log in here</a>
                    </p>
                </div>
            </div>
            
            <!-- Footer -->
            <p class="mt-8 text-center text-xs text-slate-500 dark:text-gray-500 font-medium">
                © {{ date('Y') }} KINDNET Charity Management.
            </p>
        </div>
        <script>
            function togglePassword(inputId, button) {
                const input = document.getElementById(inputId);
                if (input.type === 'password') {
                    input.type = 'text';
                    button.innerHTML = '<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>';
                } else {
                    input.type = 'password';
                    button.innerHTML = '<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>';
                }
            }
        </script>
    </body>
</html>