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
            .step-inactive { opacity: 0.5; filter: grayscale(1); }
        </style>
    </head>
    <body class="antialiased bg-slate-50 dark:bg-gray-900 text-slate-800 dark:text-gray-200 relative min-h-screen flex items-center justify-center selection:bg-indigo-500 selection:text-white py-12">
        <div class="fixed top-6 right-6 z-50">
            <x-theme-toggle />
        </div>

        <!-- Background Effect -->
        <div class="fixed inset-0 z-[-1] overflow-hidden pointer-events-none">
            <div class="absolute top-1/4 left-1/4 w-[30rem] h-[30rem] bg-purple-300 dark:bg-purple-900/40 rounded-full mix-blend-multiply dark:mix-blend-lighten filter blur-3xl opacity-60 animate-blob"></div>
            <div class="absolute top-1/3 right-1/4 w-[30rem] h-[30rem] bg-indigo-300 dark:bg-indigo-900/40 rounded-full mix-blend-multiply dark:mix-blend-lighten filter blur-3xl opacity-60 animate-blob animation-delay-2000"></div>
            <div class="absolute -bottom-8 left-1/3 w-[30rem] h-[30rem] bg-pink-300 dark:bg-pink-900/40 rounded-full mix-blend-multiply dark:mix-blend-lighten filter blur-3xl opacity-60 animate-blob animation-delay-4000"></div>
        </div>

        <div class="w-full max-w-2xl px-6 relative z-10">
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

                <!-- Progress Header -->
                <div class="mb-8 border-b border-slate-200 dark:border-gray-700/50 pb-6">
                    <div class="flex justify-between items-center text-xs font-bold uppercase tracking-wider text-slate-400">
                        <span id="step-badge-1" class="text-indigo-600 dark:text-indigo-400">1. Personal</span>
                        <span id="step-badge-2">2. Contact</span>
                        <span id="step-badge-3">3. Credentials</span>
                        <span id="step-badge-4">4. Review</span>
                    </div>
                    <div class="w-full bg-slate-200 dark:bg-gray-700 h-1.5 rounded-full mt-3 overflow-hidden">
                        <div id="progress-bar" class="bg-gradient-to-r from-indigo-600 to-purple-600 h-full rounded-full transition-all duration-300" style="width: 25%"></div>
                    </div>
                </div>

                @if($errors->any())
                    <div class="mb-6 p-4 rounded-xl bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-300 border border-red-200 dark:border-red-800 text-sm font-medium">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" id="regForm">
                    @csrf

                    <!-- STEP 1: Personal Details -->
                    <div id="step-1" class="block space-y-5 animate-fade-in">
                        <h3 class="text-lg font-bold text-indigo-900 dark:text-indigo-300 mb-2">Step 1: Personal Details</h3>
                        
                        <!-- Full Name -->
                        <div>
                            <label for="name" class="block text-sm font-bold text-slate-700 dark:text-gray-300 mb-2">Full Name (as per IC) *</label>
                            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-gray-700 bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors shadow-sm dark:text-white" placeholder="Your full name as per identity card">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <!-- IC Type -->
                            <div>
                                <label for="ic_type" class="block text-sm font-bold text-slate-700 dark:text-gray-300 mb-2">IC Type *</label>
                                <select id="ic_type" name="ic_type" required class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-gray-700 bg-white dark:bg-gray-800 focus:border-indigo-500 focus:ring-indigo-500 transition-colors shadow-sm dark:text-white">
                                    <option value="">Select IC Type</option>
                                    <option value="Baru" {{ old('ic_type') == 'Baru' ? 'selected' : '' }}>Baru</option>
                                    <option value="Lama" {{ old('ic_type') == 'Lama' ? 'selected' : '' }}>Lama</option>
                                </select>
                            </div>

                            <!-- No. IC -->
                            <div>
                                <label for="ic_number" class="block text-sm font-bold text-slate-700 dark:text-gray-300 mb-2">No. IC *</label>
                                <input id="ic_number" type="text" name="ic_number" value="{{ old('ic_number') }}" required placeholder="e.g. 981212-01-5555" maxlength="14" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-gray-700 bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors shadow-sm dark:text-white">
                                <p class="text-xs text-slate-500 mt-1">Must have dash and 12 numbers</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <!-- Date of Birth -->
                            <div>
                                <label for="dob_display" class="block text-sm font-bold text-slate-700 dark:text-gray-300 mb-2">Date of Birth *</label>
                                <input id="dob_display" type="text" readonly class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-gray-700 bg-slate-100 dark:bg-gray-800/80 shadow-sm dark:text-slate-400 cursor-not-allowed font-medium">
                                <input id="date_of_birth" type="hidden" name="date_of_birth" value="{{ old('date_of_birth') }}">
                            </div>

                            <!-- Country / State of Birth -->
                            <div>
                                <label for="place_of_birth" class="block text-sm font-bold text-slate-700 dark:text-gray-300 mb-2">State of Birth *</label>
                                <input id="place_of_birth" type="text" name="place_of_birth" value="{{ old('place_of_birth') }}" readonly class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-gray-700 bg-slate-100 dark:bg-gray-800/80 shadow-sm dark:text-slate-400 cursor-not-allowed font-medium">
                            </div>

                            <!-- Gender -->
                            <div>
                                <label for="gender" class="block text-sm font-bold text-slate-700 dark:text-gray-300 mb-2">Gender *</label>
                                <input id="gender" type="text" name="gender" value="{{ old('gender') }}" readonly class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-gray-700 bg-slate-100 dark:bg-gray-800/80 shadow-sm dark:text-slate-400 cursor-not-allowed font-medium">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <!-- Marital Status -->
                            <div>
                                <label for="marital_status" class="block text-sm font-bold text-slate-700 dark:text-gray-300 mb-2">Marital Status *</label>
                                <select id="marital_status" name="marital_status" required class="w-full px-3 py-3 rounded-xl border border-slate-200 dark:border-gray-700 bg-white dark:bg-gray-800 focus:border-indigo-500 focus:ring-indigo-500 transition-colors shadow-sm dark:text-white text-sm">
                                    <option value="">Select</option>
                                    <option value="Single" {{ old('marital_status') == 'Single' ? 'selected' : '' }}>Single</option>
                                    <option value="Married" {{ old('marital_status') == 'Married' ? 'selected' : '' }}>Married</option>
                                    <option value="Divorced" {{ old('marital_status') == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                                    <option value="Widowed" {{ old('marital_status') == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                </select>
                            </div>

                            <!-- Race -->
                            <div>
                                <label for="race" class="block text-sm font-bold text-slate-700 dark:text-gray-300 mb-2">Race *</label>
                                <select id="race" name="race" required class="w-full px-3 py-3 rounded-xl border border-slate-200 dark:border-gray-700 bg-white dark:bg-gray-800 focus:border-indigo-500 focus:ring-indigo-500 transition-colors shadow-sm dark:text-white text-sm">
                                    <option value="">Select</option>
                                    <option value="Melayu" {{ old('race') == 'Melayu' ? 'selected' : '' }}>Melayu</option>
                                    <option value="Cina" {{ old('race') == 'Cina' ? 'selected' : '' }}>Cina</option>
                                    <option value="India" {{ old('race') == 'India' ? 'selected' : '' }}>India</option>
                                    <option value="Bumiputera Sabah/Sarawak" {{ old('race') == 'Bumiputera Sabah/Sarawak' ? 'selected' : '' }}>Bumiputera</option>
                                    <option value="Lain-lain" {{ old('race') == 'Lain-lain' ? 'selected' : '' }}>Lain-lain</option>
                                </select>
                            </div>

                            <!-- Religion -->
                            <div>
                                <label for="religion" class="block text-sm font-bold text-slate-700 dark:text-gray-300 mb-2">Religion *</label>
                                <select id="religion" name="religion" required class="w-full px-3 py-3 rounded-xl border border-slate-200 dark:border-gray-700 bg-white dark:bg-gray-800 focus:border-indigo-500 focus:ring-indigo-500 transition-colors shadow-sm dark:text-white text-sm">
                                    <option value="">Select</option>
                                    <option value="Islam" {{ old('religion') == 'Islam' ? 'selected' : '' }}>Islam</option>
                                    <option value="Buddha" {{ old('religion') == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                                    <option value="Hindu" {{ old('religion') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                    <option value="Kristian" {{ old('religion') == 'Kristian' ? 'selected' : '' }}>Kristian</option>
                                    <option value="Lain-lain" {{ old('religion') == 'Lain-lain' ? 'selected' : '' }}>Lain-lain</option>
                                </select>
                            </div>
                        </div>

                        <!-- Citizen -->
                        <div>
                            <label for="citizen" class="block text-sm font-bold text-slate-700 dark:text-gray-300 mb-2">Citizen *</label>
                            <input id="citizen" type="text" name="citizen" value="{{ old('citizen', 'Warganegara') }}" required class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-gray-700 bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors shadow-sm dark:text-white">
                        </div>

                        <!-- Save & Next Button Step 1 -->
                        <div class="pt-4">
                            <button type="button" onclick="goToStep(2)" class="w-full flex justify-center py-3.5 px-4 rounded-xl text-base font-bold text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none shadow-lg shadow-indigo-500/20 transform hover:-translate-y-0.5 transition-all duration-200">
                                Save & Next &rarr;
                            </button>
                        </div>
                    </div>

                    <!-- STEP 2: Contact Details -->
                    <div id="step-2" class="hidden space-y-5 animate-fade-in">
                        <h3 class="text-lg font-bold text-indigo-900 dark:text-indigo-300 mb-2">Step 2: Contact Details</h3>

                        <!-- Address Fields -->
                        <div class="space-y-3">
                            <label class="block text-sm font-bold text-slate-700 dark:text-gray-300">Full Address *</label>
                            <input id="address_line1" type="text" name="address_line1" value="{{ old('address_line1') }}" placeholder="Address Line 1 *" required class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-gray-700 bg-white/50 dark:bg-gray-800/50 focus:border-indigo-500 focus:ring-indigo-500 transition-colors shadow-sm dark:text-white">
                            <input id="address_line2" type="text" name="address_line2" value="{{ old('address_line2') }}" placeholder="Address Line 2 (Optional)" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-gray-700 bg-white/50 dark:bg-gray-800/50 focus:border-indigo-500 focus:ring-indigo-500 transition-colors shadow-sm dark:text-white">
                            <input id="address_line3" type="text" name="address_line3" value="{{ old('address_line3') }}" placeholder="Address Line 3 (Optional)" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-gray-700 bg-white/50 dark:bg-gray-800/50 focus:border-indigo-500 focus:ring-indigo-500 transition-colors shadow-sm dark:text-white">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <!-- City -->
                            <div>
                                <label for="city" class="block text-sm font-bold text-slate-700 dark:text-gray-300 mb-2">City *</label>
                                <input id="city" type="text" name="city" value="{{ old('city') }}" placeholder="Your City" required class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-gray-700 bg-white/50 dark:bg-gray-800/50 focus:border-indigo-500 focus:ring-indigo-500 transition-colors shadow-sm dark:text-white">
                            </div>

                            <!-- Postcode -->
                            <div>
                                <label for="postcode" class="block text-sm font-bold text-slate-700 dark:text-gray-300 mb-2">Postcode *</label>
                                <input id="postcode" type="text" name="postcode" value="{{ old('postcode') }}" placeholder="e.g. 43000" maxlength="5" required class="w-28 px-4 py-3 rounded-xl border border-slate-200 dark:border-gray-700 bg-white/50 dark:bg-gray-800/50 focus:border-indigo-500 focus:ring-indigo-500 text-center font-semibold transition-colors shadow-sm dark:text-white">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <!-- State/Nation -->
                            <div>
                                <label for="state_nation" class="block text-sm font-bold text-slate-700 dark:text-gray-300 mb-2">State / Nation *</label>
                                <select id="state_nation" name="state_nation" required onchange="updateDistricts(this.value)" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-gray-700 bg-white dark:bg-gray-800 focus:border-indigo-500 focus:ring-indigo-500 transition-colors shadow-sm dark:text-white">
                                    <option value="">Select State</option>
                                    <option value="Selangor" {{ old('state_nation') == 'Selangor' ? 'selected' : '' }}>Selangor</option>
                                    <option value="Kuala Lumpur" {{ old('state_nation') == 'Kuala Lumpur' ? 'selected' : '' }}>Kuala Lumpur</option>
                                    <option value="Johor" {{ old('state_nation') == 'Johor' ? 'selected' : '' }}>Johor</option>
                                    <option value="Kedah" {{ old('state_nation') == 'Kedah' ? 'selected' : '' }}>Kedah</option>
                                    <option value="Kelantan" {{ old('state_nation') == 'Kelantan' ? 'selected' : '' }}>Kelantan</option>
                                    <option value="Melaka" {{ old('state_nation') == 'Melaka' ? 'selected' : '' }}>Melaka</option>
                                    <option value="Negeri Sembilan" {{ old('state_nation') == 'Negeri Sembilan' ? 'selected' : '' }}>Negeri Sembilan</option>
                                    <option value="Pahang" {{ old('state_nation') == 'Pahang' ? 'selected' : '' }}>Pahang</option>
                                    <option value="Pulau Pinang" {{ old('state_nation') == 'Pulau Pinang' ? 'selected' : '' }}>Pulau Pinang</option>
                                    <option value="Perak" {{ old('state_nation') == 'Perak' ? 'selected' : '' }}>Perak</option>
                                    <option value="Perlis" {{ old('state_nation') == 'Perlis' ? 'selected' : '' }}>Perlis</option>
                                    <option value="Sabah" {{ old('state_nation') == 'Sabah' ? 'selected' : '' }}>Sabah</option>
                                    <option value="Sarawak" {{ old('state_nation') == 'Sarawak' ? 'selected' : '' }}>Sarawak</option>
                                    <option value="Terengganu" {{ old('state_nation') == 'Terengganu' ? 'selected' : '' }}>Terengganu</option>
                                    <option value="Labuan" {{ old('state_nation') == 'Labuan' ? 'selected' : '' }}>Labuan</option>
                                    <option value="Putrajaya" {{ old('state_nation') == 'Putrajaya' ? 'selected' : '' }}>Putrajaya</option>
                                </select>
                            </div>

                            <!-- District -->
                            <div>
                                <label for="district" class="block text-sm font-bold text-slate-700 dark:text-gray-300 mb-2">District *</label>
                                <select id="district" name="district" required class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-gray-700 bg-white dark:bg-gray-800 focus:border-indigo-500 focus:ring-indigo-500 transition-colors shadow-sm dark:text-white">
                                    <option value="">Select District</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <!-- Mobile Phone Number -->
                            <div>
                                <label for="phone_number" class="block text-sm font-bold text-slate-700 dark:text-gray-300 mb-2">Mobile Phone Number *</label>
                                <input id="phone_number" type="text" name="phone_number" value="{{ old('phone_number') }}" required placeholder="+60123456789" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-gray-700 bg-white/50 dark:bg-gray-800/50 focus:border-indigo-500 focus:ring-indigo-500 transition-colors shadow-sm dark:text-white">
                                <p class="text-xs text-slate-500 mt-1">Primary mobile contact number</p>
                            </div>

                            <!-- Home Phone Number -->
                            <div>
                                <label for="phone_home" class="block text-sm font-bold text-slate-700 dark:text-gray-300 mb-2">Home Phone Number (Optional)</label>
                                <input id="phone_home" type="text" name="phone_home" value="{{ old('phone_home') }}" placeholder="+6031234567" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-gray-700 bg-white/50 dark:bg-gray-800/50 focus:border-indigo-500 focus:ring-indigo-500 transition-colors shadow-sm dark:text-white">
                                <p class="text-xs text-slate-500 mt-1">Optional home phone contact</p>
                            </div>
                        </div>

                        <!-- Buttons Step 2 -->
                        <div class="flex gap-4 pt-4">
                            <button type="button" onclick="goToStep(1)" class="w-1/3 py-3.5 px-4 rounded-xl text-base font-bold text-slate-700 dark:text-slate-300 bg-slate-100 hover:bg-slate-200 dark:bg-gray-800 dark:hover:bg-gray-700 transition duration-200">
                                &larr; Back
                            </button>
                            <button type="button" onclick="goToStep(3)" class="w-2/3 py-3.5 px-4 rounded-xl text-base font-bold text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 shadow-lg shadow-indigo-500/20 transform hover:-translate-y-0.5 transition-all duration-200">
                                Save & Next &rarr;
                            </button>
                        </div>
                    </div>

                    <!-- STEP 3: Account Credentials -->
                    <div id="step-3" class="hidden space-y-5 animate-fade-in">
                        <h3 class="text-lg font-bold text-indigo-900 dark:text-indigo-300 mb-2">Step 3: Account Credentials</h3>

                        <!-- Email Address -->
                        <div>
                            <label for="email" class="block text-sm font-bold text-slate-700 dark:text-gray-300 mb-2">Email Address *</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-gray-700 bg-white/50 dark:bg-gray-800/50 focus:border-indigo-500 focus:ring-indigo-500 transition-colors shadow-sm dark:text-white" placeholder="you@example.com">
                            <p class="text-xs text-slate-500 mt-1">Example: ishamimie03@gmail.com</p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <!-- Password -->
                            <div>
                                <label for="password" class="block text-sm font-bold text-slate-700 dark:text-gray-300 mb-2">Password *</label>
                                <div class="relative">
                                    <input id="password" type="password" name="password" required autocomplete="new-password" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-gray-700 bg-white/50 dark:bg-gray-800/50 focus:border-indigo-500 focus:ring-indigo-500 transition-colors shadow-sm dark:text-white pr-12" placeholder="••••••••">
                                    <button type="button" onclick="togglePassword('password', this)" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-indigo-600 transition-colors focus:outline-none">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    </button>
                                </div>
                                
                                <!-- Password Status Checklist -->
                                <div class="mt-2.5 space-y-1 text-xs font-semibold">
                                    <div id="check-length" class="text-red-500 flex items-center gap-1.5 transition-colors">
                                        <span id="bullet-length">❌</span> Min. 8 characters
                                    </div>
                                    <div id="check-letters" class="text-red-500 flex items-center gap-1.5 transition-colors">
                                        <span id="bullet-letters">❌</span> Contains letters
                                    </div>
                                    <div id="check-numbers" class="text-red-500 flex items-center gap-1.5 transition-colors">
                                        <span id="bullet-numbers">❌</span> Contains numbers
                                    </div>
                                </div>
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-bold text-slate-700 dark:text-gray-300 mb-2">Confirm Password *</label>
                                <div class="relative">
                                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-gray-700 bg-white/50 dark:bg-gray-800/50 focus:border-indigo-500 focus:ring-indigo-500 transition-colors shadow-sm dark:text-white pr-12" placeholder="••••••••">
                                    <button type="button" onclick="togglePassword('password_confirmation', this)" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-indigo-600 transition-colors focus:outline-none">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    </button>
                                </div>
                                <div id="check-match" class="mt-2 text-xs font-semibold text-red-500 flex items-center gap-1.5 transition-colors">
                                    <span id="bullet-match">❌</span> Passwords must match
                                </div>
                            </div>
                        </div>

                        <!-- Buttons Step 3 -->
                        <div class="flex gap-4 pt-4">
                            <button type="button" onclick="goToStep(2)" class="w-1/3 py-3.5 px-4 rounded-xl text-base font-bold text-slate-700 dark:text-slate-300 bg-slate-100 hover:bg-slate-200 dark:bg-gray-800 dark:hover:bg-gray-700 transition duration-200">
                                &larr; Back
                            </button>
                            <button type="button" onclick="goToStep(4)" class="w-2/3 py-3.5 px-4 rounded-xl text-base font-bold text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 shadow-lg shadow-indigo-500/20 transform hover:-translate-y-0.5 transition-all duration-200">
                                Save & Next &rarr;
                            </button>
                        </div>
                    </div>

                    <!-- STEP 4: Review & Submit -->
                    <div id="step-4" class="hidden space-y-6 animate-fade-in">
                        <div>
                            <h3 class="text-lg font-bold text-indigo-900 dark:text-indigo-300">Step 4: Review & Confirm</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Please review your profile details before registration.</p>
                        </div>

                        <!-- Summary Container -->
                        <div class="bg-slate-50 dark:bg-gray-800/40 border border-slate-150 dark:border-gray-700 rounded-2xl p-5 text-sm space-y-4">
                            <!-- Section 1 -->
                            <div>
                                <h4 class="font-bold text-xs uppercase tracking-wider text-indigo-600 dark:text-indigo-400 mb-1.5">Personal Info</h4>
                                <div class="grid grid-cols-2 gap-y-1 gap-x-4 text-slate-600 dark:text-slate-300">
                                    <div><span class="font-semibold text-slate-400">Name:</span> <span id="rev-name">-</span></div>
                                    <div><span class="font-semibold text-slate-400">IC Type:</span> <span id="rev-ic-type">-</span></div>
                                    <div><span class="font-semibold text-slate-400">IC No:</span> <span id="rev-ic-number">-</span></div>
                                    <div><span class="font-semibold text-slate-400">DOB:</span> <span id="rev-dob">-</span></div>
                                    <div><span class="font-semibold text-slate-400">Gender:</span> <span id="rev-gender">-</span></div>
                                    <div><span class="font-semibold text-slate-400">Place of Birth:</span> <span id="rev-pob">-</span></div>
                                    <div><span class="font-semibold text-slate-400">Race/Religion:</span> <span id="rev-race-rel">-</span></div>
                                </div>
                            </div>
                            <hr class="border-slate-200 dark:border-gray-700/50" />
                            <!-- Section 2 -->
                            <div>
                                <h4 class="font-bold text-xs uppercase tracking-wider text-indigo-600 dark:text-indigo-400 mb-1.5">Contact Details</h4>
                                <div class="text-slate-600 dark:text-slate-300 space-y-1">
                                    <div><span class="font-semibold text-slate-400">Address:</span> <span id="rev-address">-</span></div>
                                    <div class="grid grid-cols-2 gap-x-4">
                                        <div><span class="font-semibold text-slate-400">District:</span> <span id="rev-district">-</span></div>
                                        <div><span class="font-semibold text-slate-400">Mobile Phone:</span> <span id="rev-phone-mob">-</span></div>
                                    </div>
                                </div>
                            </div>
                            <hr class="border-slate-200 dark:border-gray-700/50" />
                            <!-- Section 3 -->
                            <div>
                                <h4 class="font-bold text-xs uppercase tracking-wider text-indigo-600 dark:text-indigo-400 mb-1.5">Account Details</h4>
                                <div class="text-slate-600 dark:text-slate-300">
                                    <div><span class="font-semibold text-slate-400">Email:</span> <span id="rev-email">-</span></div>
                                </div>
                            </div>
                        </div>

                        <!-- Terms -->
                        <div class="block">
                            <label class="inline-flex items-start cursor-pointer">
                                <input type="checkbox" required class="mt-1 w-5 h-5 rounded border border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-700">
                                <span class="ml-3 text-sm font-medium text-slate-600 dark:text-gray-400">
                                    I agree to the <a href="#" class="text-indigo-605 dark:text-indigo-400 hover:underline">Terms & Conditions</a> and <a href="#" class="text-indigo-600 dark:text-indigo-400 hover:underline">Privacy Policy</a>.
                                </span>
                            </label>
                        </div>

                        <!-- Buttons Step 4 -->
                        <div class="flex gap-4 pt-4">
                            <button type="button" onclick="goToStep(3)" class="w-1/3 py-3.5 px-4 rounded-xl text-base font-bold text-slate-700 dark:text-slate-300 bg-slate-100 hover:bg-slate-200 dark:bg-gray-800 dark:hover:bg-gray-700 transition duration-200">
                                &larr; Back
                            </button>
                            <button type="submit" class="w-2/3 py-3.5 px-4 rounded-xl text-base font-bold text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 shadow-lg shadow-indigo-500/30 transform hover:-translate-y-0.5 transition-all duration-200">
                                Register
                            </button>
                        </div>
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
            let currentStep = 1;

            // Form inputs
            const inputName = document.getElementById('name');
            const selectIcType = document.getElementById('ic_type');
            const inputIcNumber = document.getElementById('ic_number');
            const inputDobDisplay = document.getElementById('dob_display');
            const inputDateOfBirth = document.getElementById('date_of_birth');
            const inputPob = document.getElementById('place_of_birth');
            const inputGender = document.getElementById('gender');
            const selectMarital = document.getElementById('marital_status');
            const selectRace = document.getElementById('race');
            const selectReligion = document.getElementById('religion');
            const inputCitizen = document.getElementById('citizen');

            const inputAddress1 = document.getElementById('address_line1');
            const inputAddress2 = document.getElementById('address_line2');
            const inputAddress3 = document.getElementById('address_line3');
            const inputCity = document.getElementById('city');
            const inputPostcode = document.getElementById('postcode');
            const selectStateNation = document.getElementById('state_nation');
            const selectDistrict = document.getElementById('district');
            const inputPhoneMob = document.getElementById('phone_number');
            const inputPhoneHome = document.getElementById('phone_home');

            const inputEmail = document.getElementById('email');
            const inputPassword = document.getElementById('password');
            const inputConfirm = document.getElementById('password_confirmation');

            // Dynamic Districts Dictionary
            const districtMap = {
                'Selangor': ['Petaling', 'Hulu Langat', 'Klang', 'Gombak', 'Kuala Langat', 'Sepang', 'Kuala Selangor', 'Hulu Selangor', 'Sabak Bernam'],
                'Kuala Lumpur': ['Kuala Lumpur', 'Cheras', 'Kepong', 'Sentul', 'Bangsar'],
                'Johor': ['Johor Bahru', 'Batu Pahat', 'Muar', 'Kluang', 'Kota Tinggi', 'Segamat', 'Pontian', 'Tangkak', 'Mersing', 'Kulai'],
                'Kedah': ['Alor Setar', 'Sungai Petani', 'Kulim', 'Langkawi', 'Kubang Pasu', 'Baling', 'Pendang', 'Yan', 'Sik', 'Padang Terap', 'Pokok Sena'],
                'Kelantan': ['Kota Bharu', 'Pasir Mas', 'Tumpat', 'Bachok', 'Tanah Merah', 'Pasir Puteh', 'Kuala Krai', 'Machang', 'Gua Musang', 'Jeli'],
                'Melaka': ['Melaka Tengah', 'Alor Gajah', 'Jasin'],
                'Negeri Sembilan': ['Seremban', 'Port Dickson', 'Jempol', 'Tampin', 'Kuala Pilah', 'Rembau', 'Jelebu'],
                'Pahang': ['Kuantan', 'Temerloh', 'Bentong', 'Maran', 'Rompin', 'Pekan', 'Bera', 'Raub', 'Jerantut', 'Lipis', 'Cameron Highlands'],
                'Pulau Pinang': ['George Town', 'Butterworth', 'Bukit Mertajam', 'Nibong Tebal', 'Kepala Batas'],
                'Perak': ['Ipoh', 'Taiping', 'Teluk Intan', 'Manjung', 'Kuala Kangsar', 'Kampar', 'Batang Padang', 'Kerian', 'Hulu Perak', 'Muallim', 'Bagan Datuk'],
                'Perlis': ['Kangar'],
                'Sabah': ['Kota Kinabalu', 'Sandakan', 'Tawau', 'Lahad Datu', 'Keningau', 'Penampang', 'Papar', 'Kudat', 'Ranau'],
                'Sarawak': ['Kuching', 'Miri', 'Sibu', 'Bintulu', 'Samarahan', 'Sri Aman', 'Sarikei', 'Kapit', 'Limbang', 'Mukah', 'Betong'],
                'Terengganu': ['Kuala Terengganu', 'Kemaman', 'Dungun', 'Besut', 'Marang', 'Hulu Terengganu', 'Setiu', 'Kuala Nerus'],
                'Labuan': ['Labuan'],
                'Putrajaya': ['Putrajaya']
            };

            function updateDistricts(stateVal) {
                selectDistrict.innerHTML = '<option value="">Select District *</option>';
                if (districtMap[stateVal]) {
                    districtMap[stateVal].forEach(d => {
                        const opt = document.createElement('option');
                        opt.value = d;
                        opt.textContent = d;
                        if (d === "{{ old('district') }}") {
                            opt.selected = true;
                        }
                        selectDistrict.appendChild(opt);
                    });
                }
            }

            // Trigger district population if old value exists
            if (selectStateNation.value) {
                updateDistricts(selectStateNation.value);
            }

            // Enforce Postcode to 5 digits only
            inputPostcode.addEventListener('input', (e) => {
                e.target.value = e.target.value.replace(/[^0-9]/g, '').substring(0, 5);
            });

            // Enforce Auto Prefix +60 for mobile and home phone inputs
            function setupPhoneAutoPrefix(input, required = true) {
                input.addEventListener('focus', () => {
                    if (!input.value) {
                        input.value = '+60';
                    }
                });
                input.addEventListener('blur', () => {
                    if (input.value === '+60' && !required) {
                        input.value = '';
                    }
                });
                input.addEventListener('input', () => {
                    let val = input.value;
                    if (!val.startsWith('+60')) {
                        if (val.length < 3) {
                            input.value = '+60';
                        } else {
                            input.value = '+60' + val.replace(/[^0-9]/g, '');
                        }
                    } else {
                        let digits = val.substring(3).replace(/[^0-9]/g, '');
                        input.value = '+60' + digits;
                    }
                });
            }

            setupPhoneAutoPrefix(inputPhoneMob, true);
            setupPhoneAutoPrefix(inputPhoneHome, false);

            // Toggle password visual
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

            // Birth Place Code dictionary based on Malaysian IC middle numbers
            const birthPlaceCodes = {
                '01': 'Johor', '21': 'Johor', '22': 'Johor', '23': 'Johor', '24': 'Johor',
                '02': 'Kedah', '25': 'Kedah', '26': 'Kedah', '27': 'Kedah',
                '03': 'Kelantan', '28': 'Kelantan', '29': 'Kelantan',
                '04': 'Melaka', '30': 'Melaka',
                '05': 'Negeri Sembilan', '31': 'Negeri Sembilan', '59': 'Negeri Sembilan',
                '06': 'Pahang', '32': 'Pahang', '33': 'Pahang',
                '07': 'Pulau Pinang', '34': 'Pulau Pinang', '35': 'Pulau Pinang',
                '08': 'Perak', '36': 'Perak', '37': 'Perak', '38': 'Perak', '39': 'Perak',
                '09': 'Perlis', '40': 'Perlis',
                '10': 'Selangor', '41': 'Selangor', '42': 'Selangor', '43': 'Selangor', '44': 'Selangor',
                '11': 'Terengganu', '45': 'Terengganu', '46': 'Terengganu',
                '12': 'Sabah', '47': 'Sabah', '48': 'Sabah', '49': 'Sabah',
                '13': 'Sarawak', '50': 'Sarawak', '51': 'Sarawak', '52': 'Sarawak', '53': 'Sarawak',
                '14': 'Kuala Lumpur', '54': 'Kuala Lumpur', '55': 'Kuala Lumpur', '56': 'Kuala Lumpur', '57': 'Kuala Lumpur',
                '15': 'Labuan', '58': 'Labuan',
                '16': 'Putrajaya'
            };

            // Auto DOB and Gender generation from Malaysian IC
            inputIcNumber.addEventListener('input', function(e) {
                let ic = e.target.value.replace(/[^0-9]/g, '');
                
                // Format IC: YYMMDD-PB-###G
                let formatted = ic;
                if (ic.length > 6 && ic.length <= 8) {
                    formatted = ic.substr(0, 6) + '-' + ic.substr(6);
                } else if (ic.length > 8) {
                    formatted = ic.substr(0, 6) + '-' + ic.substr(6, 2) + '-' + ic.substr(8, 4);
                }
                e.target.value = formatted;

                if (ic.length >= 6) {
                    let yy = parseInt(ic.substring(0, 2));
                    let mm = ic.substring(2, 4);
                    let dd = ic.substring(4, 6);

                    // Auto century calculation
                    let currentYear2Digit = parseInt(new Date().getFullYear().toString().slice(-2));
                    let fullYear = yy > currentYear2Digit ? 1900 + yy : 2000 + yy;

                    let dobStr = `${fullYear}-${mm}-${dd}`;
                    let isDateValid = !isNaN(new Date(dobStr).getTime());

                    if (isDateValid) {
                        inputDobDisplay.value = `${dd}-${mm}-${fullYear}`;
                        inputDateOfBirth.value = dobStr; // YYYY-MM-DD for DB compatibility
                    } else {
                        inputDobDisplay.value = '';
                        inputDateOfBirth.value = '';
                    }
                } else {
                    inputDobDisplay.value = '';
                    inputDateOfBirth.value = '';
                }

                // Auto Place of Birth generation from Malaysian IC middle number
                if (ic.length >= 8) {
                    let pb = ic.substring(6, 8);
                    if (birthPlaceCodes[pb]) {
                        inputPob.value = birthPlaceCodes[pb];
                    } else {
                        inputPob.value = '';
                    }
                } else {
                    inputPob.value = '';
                }

                // Auto Gender extraction from Malaysian IC last number
                if (ic.length === 12) {
                    let lastDigit = parseInt(ic.substring(11));
                    if (lastDigit % 2 === 0) {
                        inputGender.value = 'Perempuan'; // Female
                    } else {
                        inputGender.value = 'Lelaki'; // Male
                    }
                } else {
                    inputGender.value = '';
                }
            });

            // Password real-time requirements tracker
            const bulletLength = document.getElementById('bullet-length');
            const bulletLetters = document.getElementById('bullet-letters');
            const bulletNumbers = document.getElementById('bullet-numbers');
            const bulletMatch = document.getElementById('bullet-match');

            const checkLength = document.getElementById('check-length');
            const checkLetters = document.getElementById('check-letters');
            const checkNumbers = document.getElementById('check-numbers');
            const checkMatch = document.getElementById('check-match');

            function validatePasswordTracker() {
                const pass = inputPassword.value;
                const confirm = inputConfirm.value;

                // Length
                if (pass.length >= 8) {
                    bulletLength.textContent = '✅';
                    checkLength.className = 'text-emerald-500 flex items-center gap-1.5 transition-colors';
                } else {
                    bulletLength.textContent = '❌';
                    checkLength.className = 'text-red-500 flex items-center gap-1.5 transition-colors';
                }

                // Letters
                if (/[a-zA-Z]/.test(pass)) {
                    bulletLetters.textContent = '✅';
                    checkLetters.className = 'text-emerald-500 flex items-center gap-1.5 transition-colors';
                } else {
                    bulletLetters.textContent = '❌';
                    checkLetters.className = 'text-red-500 flex items-center gap-1.5 transition-colors';
                }

                // Numbers
                if (/[0-9]/.test(pass)) {
                    bulletNumbers.textContent = '✅';
                    checkNumbers.className = 'text-emerald-500 flex items-center gap-1.5 transition-colors';
                } else {
                    bulletNumbers.textContent = '❌';
                    checkNumbers.className = 'text-red-500 flex items-center gap-1.5 transition-colors';
                }

                // Match
                if (pass === confirm && pass !== '') {
                    bulletMatch.textContent = '✅';
                    checkMatch.className = 'mt-2 text-xs font-semibold text-emerald-500 flex items-center gap-1.5 transition-colors';
                } else {
                    bulletMatch.textContent = '❌';
                    checkMatch.className = 'mt-2 text-xs font-semibold text-red-500 flex items-center gap-1.5 transition-colors';
                }
            }

            inputPassword.addEventListener('input', validatePasswordTracker);
            inputConfirm.addEventListener('input', validatePasswordTracker);

            // Step Navigation Wizard logic
            function goToStep(step) {
                // Perform validations if moving forward
                if (step > currentStep) {
                    if (currentStep === 1) {
                        if (!inputName.value.trim()) { inputName.focus(); return; }
                        if (!selectIcType.value) { selectIcType.focus(); return; }
                        if (inputIcNumber.value.replace(/[^0-9]/g, '').length !== 12) {
                            alert("IC Number must contain exactly 12 digits.");
                            inputIcNumber.focus();
                            return;
                        }
                        if (!inputDateOfBirth.value) { alert("Invalid IC Number Date."); inputIcNumber.focus(); return; }
                        if (!inputPob.value) { alert("Invalid IC Number Place of Birth."); inputIcNumber.focus(); return; }
                        if (!inputGender.value) { alert("Invalid IC Number Gender."); inputIcNumber.focus(); return; }
                        if (!selectMarital.value) { selectMarital.focus(); return; }
                        if (!selectRace.value) { selectRace.focus(); return; }
                        if (!selectReligion.value) { selectReligion.focus(); return; }
                        if (!inputCitizen.value.trim()) { inputCitizen.focus(); return; }
                    }

                    if (currentStep === 2) {
                        if (!inputAddress1.value.trim()) { inputAddress1.focus(); return; }
                        if (!inputCity.value.trim()) { inputCity.focus(); return; }
                        if (inputPostcode.value.length !== 5) { alert("Postcode must be exactly 5 digits."); inputPostcode.focus(); return; }
                        if (!selectStateNation.value) { selectStateNation.focus(); return; }
                        if (!selectDistrict.value) { selectDistrict.focus(); return; }
                        if (inputPhoneMob.value.replace(/[^0-9]/g, '').length < 9) {
                            alert("Please enter a valid mobile phone number.");
                            inputPhoneMob.focus();
                            return;
                        }
                    }

                    if (currentStep === 3) {
                        // Validate email format
                        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        if (!emailRegex.test(inputEmail.value)) {
                            alert("Please enter a valid email address.");
                            inputEmail.focus();
                            return;
                        }
                        // Validate Password Criteria
                        if (inputPassword.value.length < 8 || !/[a-zA-Z]/.test(inputPassword.value) || !/[0-9]/.test(inputPassword.value)) {
                            alert("Password does not meet requirements.");
                            inputPassword.focus();
                            return;
                        }
                        if (inputPassword.value !== inputConfirm.value) {
                            alert("Passwords do not match.");
                            inputConfirm.focus();
                            return;
                        }

                        // Generate summary values for step 4
                        document.getElementById('rev-name').textContent = inputName.value;
                        document.getElementById('rev-ic-type').textContent = selectIcType.value;
                        document.getElementById('rev-ic-number').textContent = inputIcNumber.value;
                        document.getElementById('rev-dob').textContent = inputDobDisplay.value;
                        document.getElementById('rev-gender').textContent = inputGender.value === 'Perempuan' ? 'Female' : (inputGender.value === 'Lelaki' ? 'Male' : inputGender.value);
                        document.getElementById('rev-pob').textContent = inputPob.value;
                        document.getElementById('rev-race-rel').textContent = `${selectRace.value} / ${selectReligion.value}`;
                        
                        let fullAddress = inputAddress1.value;
                        if(inputAddress2.value.trim()) fullAddress += `, ${inputAddress2.value}`;
                        if(inputAddress3.value.trim()) fullAddress += `, ${inputAddress3.value}`;
                        fullAddress += `, ${inputPostcode.value} ${inputCity.value}, ${selectStateNation.value}`;
                        
                        document.getElementById('rev-address').textContent = fullAddress;
                        document.getElementById('rev-district').textContent = selectDistrict.value;
                        document.getElementById('rev-phone-mob').textContent = inputPhoneMob.value;
                        document.getElementById('rev-email').textContent = inputEmail.value;
                    }
                }

                // Transition Step Views
                document.getElementById(`step-${currentStep}`).classList.add('hidden');
                document.getElementById(`step-badge-${currentStep}`).className = 'text-slate-400';

                currentStep = step;

                document.getElementById(`step-${currentStep}`).classList.remove('hidden');
                document.getElementById(`step-badge-${currentStep}`).className = 'text-indigo-600 dark:text-indigo-400 font-bold';

                // Progress Bar Width
                const progressWidths = { 1: '25%', 2: '50%', 3: '75%', 4: '100%' };
                document.getElementById('progress-bar').style.width = progressWidths[currentStep];

                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        </script>
    </body>
</html>