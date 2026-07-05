<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>KINDNET - Charity Management System</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|outfit:400,600,700,800,900&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script>
            if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }

            // Force reload when navigated to via browser back button (BF Cache)
            window.addEventListener('pageshow', function (event) {
                if (event.persisted) {
                    window.location.reload();
                }
            });
        </script>

        <style>
            body { font-family: 'Inter', sans-serif; }
            h1, h2, h3, h4, h5, .font-heading { font-family: 'Outfit', sans-serif; }
            
            .bg-glass {
                background: rgba(255, 255, 255, 0.65);
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
                border-bottom: 1px solid rgba(255, 255, 255, 0.4);
            }
            .dark .bg-glass {
                background: rgba(17, 24, 39, 0.7);
                border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            }

            .card-glass {
                background: rgba(255, 255, 255, 0.8);
                backdrop-filter: blur(12px);
                border: 1px solid rgba(255, 255, 255, 0.5);
            }
            .dark .card-glass {
                background: rgba(31, 41, 55, 0.7);
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
    <body class="antialiased bg-slate-50 dark:bg-gray-900 text-slate-800 dark:text-gray-200 relative overflow-x-hidden selection:bg-indigo-500 selection:text-white">

        <!-- Background Effect -->
        <div class="fixed inset-0 z-[-1] overflow-hidden pointer-events-none">
            <div class="absolute top-0 left-1/4 w-96 h-96 bg-purple-300 dark:bg-purple-900/40 rounded-full mix-blend-multiply dark:mix-blend-lighten filter blur-3xl opacity-50 animate-blob"></div>
            <div class="absolute top-0 right-1/4 w-96 h-96 bg-indigo-300 dark:bg-indigo-900/40 rounded-full mix-blend-multiply dark:mix-blend-lighten filter blur-3xl opacity-50 animate-blob animation-delay-2000"></div>
            <div class="absolute -bottom-8 left-1/3 w-96 h-96 bg-pink-300 dark:bg-pink-900/40 rounded-full mix-blend-multiply dark:mix-blend-lighten filter blur-3xl opacity-50 animate-blob animation-delay-4000"></div>
        </div>

        <!-- Navigation -->
        <nav class="fixed w-full z-50 bg-glass transition-all duration-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-20">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center text-white font-heading font-black text-xl shadow-lg shadow-indigo-500/30">
                            KN
                        </div>
                        <span class="font-heading font-bold text-2xl tracking-tight text-slate-900 dark:text-white">KIND<span class="text-indigo-600 dark:text-indigo-400">NET</span></span>
                    </div>

                    <div class="hidden md:flex space-x-8">
                        <a href="#about" class="text-sm font-semibold text-slate-600 hover:text-indigo-600 dark:text-gray-300 dark:hover:text-indigo-400 transition">About Us</a>
                        <a href="#how-it-works" class="text-sm font-semibold text-slate-600 hover:text-indigo-600 dark:text-gray-300 dark:hover:text-indigo-400 transition">How it Works</a>
                        <a href="#impact" class="text-sm font-semibold text-slate-600 hover:text-indigo-600 dark:text-gray-300 dark:hover:text-indigo-400 transition">Our Impact</a>
                    </div>

                    <div class="flex items-center gap-4">
                        <x-theme-toggle />
                        @auth
                            <a href="{{ url('/dashboard') }}" class="font-semibold text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 transition">Go to Dashboard &rarr;</a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-700 dark:text-gray-200 hover:text-indigo-600 dark:hover:text-indigo-400 transition">Log in</a>

                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <main class="relative pt-32 pb-16 sm:pt-40 sm:pb-24 lg:pb-32 px-4 mx-auto max-w-7xl sm:px-6 lg:px-8 flex flex-col lg:flex-row items-center gap-12">
            <div class="text-center lg:text-left lg:w-1/2 z-10">
                <div class="inline-flex items-center px-3 py-1.5 rounded-full bg-indigo-50 dark:bg-indigo-900/30 border border-indigo-100 dark:border-indigo-800 text-indigo-600 dark:text-indigo-300 text-sm font-semibold mb-6">
                    <span class="flex w-2 h-2 rounded-full bg-indigo-600 dark:bg-indigo-400 mr-2 animate-pulse"></span>
                    Empowering Families in Need
                </div>
                <h1 class="font-heading text-5xl sm:text-6xl lg:text-7xl font-extrabold tracking-tight text-slate-900 dark:text-white mb-6 leading-[1.1]">
                    Hope Starts <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600 dark:from-indigo-400 dark:to-purple-400">With Us.</span>
                </h1>
                <p class="mt-4 text-lg sm:text-xl text-slate-600 dark:text-gray-300 max-w-2xl mx-auto lg:mx-0 font-medium leading-relaxed">
                    KINDNET is a seamless charity management platform that connects individuals in need with essential resources. Apply for financial, medical, or educational support in minutes.
                </p>
                <div class="mt-10 flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-4 text-base font-bold text-white transition-all duration-200 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl hover:from-indigo-700 hover:to-purple-700 hover:shadow-xl hover:shadow-indigo-500/40 transform hover:-translate-y-1">
                        Start Application
                    </a>
                    <a href="#how-it-works" class="inline-flex items-center justify-center px-8 py-4 text-base font-bold text-slate-700 dark:text-gray-200 transition-all duration-200 bg-white dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-xl hover:bg-slate-50 dark:hover:bg-gray-700 hover:shadow-md">
                        Learn More
                    </a>
                </div>
                
                <div class="mt-12 flex items-center justify-center lg:justify-start gap-8">
                    <div>
                        <p class="font-heading text-3xl font-bold text-slate-900 dark:text-white">2.4K+</p>
                        <p class="text-sm font-semibold text-slate-500 dark:text-gray-400">Families Helped</p>
                    </div>
                    <div class="w-px h-10 bg-slate-200 dark:bg-gray-700"></div>
                    <div>
                        <p class="font-heading text-3xl font-bold text-slate-900 dark:text-white">$1.2M</p>
                        <p class="text-sm font-semibold text-slate-500 dark:text-gray-400">Funds Disbursed</p>
                    </div>
                </div>
            </div>
            
            <div class="lg:w-1/2 relative z-10 w-full mt-10 lg:mt-0 perspective-1000">
                <!-- Decorative Abstract Dashboard UI -->
                <div class="relative w-full max-w-lg mx-auto transform rotate-y-[-10deg] rotate-x-[5deg] hover:rotate-0 transition-transform duration-700 ease-out">
                    <div class="card-glass rounded-2xl shadow-2xl overflow-hidden p-2">
                        <div class="bg-slate-900 rounded-xl overflow-hidden border border-slate-700 shadow-inner">
                            <div class="px-4 py-3 border-b border-slate-800 flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full bg-red-500"></div>
                                <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                                <div class="w-3 h-3 rounded-full bg-green-500"></div>
                                <div class="ml-4 text-xs font-mono text-slate-400">kindnet-admin-panel</div>
                            </div>
                            <div class="p-5">
                                <div class="flex justify-between items-center mb-6">
                                    <div class="w-1/3 h-4 bg-slate-800 rounded-full"></div>
                                    <div class="w-10 h-10 rounded-full bg-indigo-500/20 flex items-center justify-center"><div class="w-5 h-5 bg-indigo-500 rounded-full"></div></div>
                                </div>
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between p-3 rounded-lg bg-slate-800/50 border border-slate-700">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-lg bg-green-500/20 text-green-400 flex items-center justify-center font-bold">✓</div>
                                            <div>
                                                <div class="w-24 h-3 bg-slate-600 rounded-full mb-2"></div>
                                                <div class="w-16 h-2 bg-slate-700 rounded-full"></div>
                                            </div>
                                        </div>
                                        <div class="w-16 h-6 rounded-full bg-green-500/20 border border-green-500/30"></div>
                                    </div>
                                    <div class="flex items-center justify-between p-3 rounded-lg bg-slate-800/50 border border-slate-700">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-lg bg-yellow-500/20 text-yellow-400 flex items-center justify-center font-bold">!</div>
                                            <div>
                                                <div class="w-32 h-3 bg-slate-600 rounded-full mb-2"></div>
                                                <div class="w-20 h-2 bg-slate-700 rounded-full"></div>
                                            </div>
                                        </div>
                                        <div class="w-16 h-6 rounded-full bg-yellow-500/20 border border-yellow-500/30"></div>
                                    </div>
                                     <div class="flex items-center justify-between p-3 rounded-lg bg-slate-800/50 border border-slate-700">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-lg bg-indigo-500/20 text-indigo-400 flex items-center justify-center font-bold">$</div>
                                            <div>
                                                <div class="w-28 h-3 bg-slate-600 rounded-full mb-2"></div>
                                                <div class="w-12 h-2 bg-slate-700 rounded-full"></div>
                                            </div>
                                        </div>
                                        <div class="w-20 h-6 rounded-full bg-indigo-500/20 border border-indigo-500/30"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Floating visual elements -->
                    <div class="absolute -right-6 -bottom-6 bg-white dark:bg-slate-800 p-4 rounded-xl shadow-xl border border-slate-100 dark:border-slate-700 flex items-center gap-4 animate-bounce" style="animation-duration: 3s;">
                        <div class="w-12 h-12 rounded-full bg-green-100 dark:bg-green-900/50 flex items-center justify-center text-green-600 dark:text-green-400">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider">Status</p>
                            <p class="font-heading font-bold text-slate-900 dark:text-white">Approved</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Features Section -->
        <section id="about" class="py-20 bg-white dark:bg-slate-900 relative">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <h2 class="font-heading text-3xl md:text-4xl font-bold text-slate-900 dark:text-white mb-4">Support that makes a difference</h2>
                    <p class="text-lg text-slate-600 dark:text-gray-400">Our platform is designed to eliminate paperwork and accelerate the support process for those who need it most.</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div class="p-8 rounded-2xl bg-indigo-50 dark:bg-indigo-900/10 border border-indigo-100 dark:border-indigo-900/30 hover:-translate-y-2 transition-transform duration-300">
                        <div class="w-14 h-14 rounded-xl bg-indigo-600 text-white flex items-center justify-center mb-6 shadow-lg shadow-indigo-500/30">
                            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold font-heading text-slate-900 dark:text-white mb-3">Fast Processing</h3>
                        <p class="text-slate-600 dark:text-gray-400">Our digital-first approach ensures your applications are reviewed rapidly, getting you the support you need without the wait.</p>
                    </div>
                    <!-- Feature 2 -->
                    <div class="p-8 rounded-2xl bg-purple-50 dark:bg-purple-900/10 border border-purple-100 dark:border-purple-900/30 hover:-translate-y-2 transition-transform duration-300">
                        <div class="w-14 h-14 rounded-xl bg-purple-600 text-white flex items-center justify-center mb-6 shadow-lg shadow-purple-500/30">
                            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold font-heading text-slate-900 dark:text-white mb-3">Secure & Private</h3>
                        <p class="text-slate-600 dark:text-gray-400">Your data privacy is our priority. All applicant information is encrypted and securely stored strictly for review purposes.</p>
                    </div>
                    <!-- Feature 3 -->
                    <div class="p-8 rounded-2xl bg-pink-50 dark:bg-pink-900/10 border border-pink-100 dark:border-pink-900/30 hover:-translate-y-2 transition-transform duration-300">
                        <div class="w-14 h-14 rounded-xl bg-pink-600 text-white flex items-center justify-center mb-6 shadow-lg shadow-pink-500/30">
                            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold font-heading text-slate-900 dark:text-white mb-3">Direct Financial Aid</h3>
                        <p class="text-slate-600 dark:text-gray-400">We integrate directly with secure payment gateways ensuring funds are delivered directly to approved applicants safely.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center text-white font-heading font-black text-sm">KN</div>
                        <span class="font-heading font-bold text-xl text-slate-900 dark:text-white">KIND<span class="text-indigo-600">NET</span></span>
                    </div>
                    <p class="text-slate-500 dark:text-gray-400 text-sm">© {{ date('Y') }} KINDNET Charity Management. Built for the community.</p>
                </div>
            </div>
        </footer>

    </body>
</html>