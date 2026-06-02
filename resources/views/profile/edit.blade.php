<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ 
        showInfoModal: {{ $errors->any() && !$errors->updatePassword->any() ? 'true' : 'false' }}, 
        showPasswordModal: {{ $errors->updatePassword->any() ? 'true' : 'false' }} 
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Success Alerts -->
            @if (session('status') === 'profile-updated' || session('status') === 'password-updated' || session('status') === 'profile-picture-updated' || session('status') === 'profile-picture-deleted')
                <div x-data="{ show: true }" x-show="show" x-transition class="p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg shadow-sm flex justify-between items-center">
                    <div>
                        @if (session('status') === 'profile-updated')
                            {{ __('Profile details updated successfully.') }}
                        @elseif (session('status') === 'password-updated')
                            {{ __('Password updated successfully.') }}
                        @elseif (session('status') === 'profile-picture-updated')
                            {{ __('Profile picture updated successfully.') }}
                        @elseif (session('status') === 'profile-picture-deleted')
                            {{ __('Profile picture removed successfully.') }}
                        @endif
                    </div>
                    <button @click="show = false" class="text-green-800 hover:text-green-950 font-bold">&times;</button>
                </div>
            @endif

            <!-- Main Profile Info Display Card -->
            <div class="p-6 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="flex flex-col md:flex-row gap-8 items-start">
                    
                    <!-- Left: Profile Picture -->
                    <div class="w-full md:w-1/3 flex flex-col items-center border-b md:border-b-0 md:border-r border-gray-200 dark:border-gray-700 pb-6 md:pb-0 md:pr-8">
                        @include('profile.partials.update-profile-picture-form')
                    </div>

                    <!-- Right: Detailed Profile Info -->
                    <div class="flex-1 w-full space-y-6">
                        <div class="flex justify-between items-center border-b dark:border-gray-700 pb-3">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Account Details</h3>
                            <div>
                                @if($user->role === 'admin')
                                    <span class="px-3 py-1 rounded bg-purple-100 dark:bg-purple-900/40 text-purple-800 dark:text-purple-300 text-xs font-semibold uppercase tracking-wider">ADMIN</span>
                                @else
                                    <span class="px-3 py-1 rounded bg-blue-100 dark:bg-blue-900/40 text-blue-800 dark:text-blue-300 text-xs font-semibold uppercase tracking-wider">APPLICANT</span>
                                @endif
                            </div>
                        </div>

                        <!-- Info Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-sm text-gray-600 dark:text-gray-400">
                            <div>
                                <span class="block font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider text-xs">Full Name</span>
                                <span class="text-base font-semibold text-gray-800 dark:text-gray-200">{{ $user->name }}</span>
                            </div>

                            <div>
                                <span class="block font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider text-xs">Age</span>
                                <span class="text-base font-semibold text-gray-800 dark:text-gray-200">
                                    {{ $user->date_of_birth ? $user->date_of_birth->age . ' years' : 'N/A' }}
                                </span>
                            </div>

                            <div>
                                <span class="block font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider text-xs">Email Address</span>
                                <span class="text-base font-semibold text-gray-800 dark:text-gray-200">{{ $user->email }}</span>
                            </div>

                            <div>
                                <span class="block font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider text-xs">IC Number</span>
                                <span class="text-base font-semibold text-gray-800 dark:text-gray-200">{{ $user->ic_number ?? 'N/A' }}</span>
                            </div>

                            <div>
                                <span class="block font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider text-xs">Gender</span>
                                <span class="text-base font-semibold text-gray-800 dark:text-gray-200">
                                    {{ $user->gender === 'Perempuan' ? 'Female' : ($user->gender === 'Lelaki' ? 'Male' : ($user->gender ?? 'N/A')) }}
                                </span>
                            </div>

                            <div>
                                <span class="block font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider text-xs">Mobile Phone</span>
                                <span class="text-base font-semibold text-gray-800 dark:text-gray-200">{{ $user->phone_number ?? 'N/A' }}</span>
                            </div>

                            <div>
                                <span class="block font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider text-xs">Home Phone</span>
                                <span class="text-base font-semibold text-gray-800 dark:text-gray-200">{{ $user->phone_home ?? 'N/A' }}</span>
                            </div>

                            <div>
                                <span class="block font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider text-xs">Role</span>
                                <span class="text-base font-semibold text-gray-800 dark:text-gray-200">{{ ucfirst($user->role) }}</span>
                            </div>

                            <div class="sm:col-span-2">
                                <span class="block font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider text-xs">Full Address</span>
                                <span class="text-base font-semibold text-gray-800 dark:text-gray-200">
                                    @php
                                        $addressParts = array_filter([
                                            $user->address_line1,
                                            $user->address_line2,
                                            $user->address_line3,
                                            $user->postcode,
                                            $user->city,
                                            $user->district,
                                            $user->state_nation
                                        ]);
                                    @endphp
                                    {{ !empty($addressParts) ? implode(', ', $addressParts) : 'N/A' }}
                                </span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-4 pt-6 border-t dark:border-gray-700 mt-6">
                            <button @click="showInfoModal = true" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-sm text-sm transition-colors">
                                Edit Profile Info
                            </button>
                            <button @click="showPasswordModal = true" class="px-5 py-2.5 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 font-semibold rounded-lg shadow-sm text-sm transition-colors">
                                Update Password
                            </button>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Delete Account Card -->
            <div class="p-6 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg border border-red-100 dark:border-red-900/20">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>

        <!-- MODAL 1: Edit Profile Info -->
        <div x-show="showInfoModal" 
             class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4 transition-opacity duration-300"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             x-cloak>
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl max-w-lg w-full overflow-hidden transform transition-all duration-300 border border-gray-200 dark:border-gray-700"
                 @click.away="showInfoModal = false"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                <div class="p-6">
                    <div class="flex justify-between items-center border-b dark:border-gray-700 pb-3 mb-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Update Profile Info</h3>
                        <button @click="showInfoModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>
        </div>

        <!-- MODAL 2: Update Password -->
        <div x-show="showPasswordModal" 
             class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4 transition-opacity duration-300"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             x-cloak>
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl max-w-lg w-full overflow-hidden transform transition-all duration-300 border border-gray-200 dark:border-gray-700"
                 @click.away="showPasswordModal = false"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                <div class="p-6">
                    <div class="flex justify-between items-center border-b dark:border-gray-700 pb-3 mb-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Update Password</h3>
                        <button @click="showPasswordModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>

    </div>

    <!-- Ensure modals aren't hidden by x-cloak before Alpine loads -->
    <style>
        [x-cloak] { display: none !important; }
    </style>
</x-app-layout>
