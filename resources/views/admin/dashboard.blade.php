<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Stat Cards -->
                <div class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-lg shadow-xl p-6 text-white transform hover:scale-105 transition-all duration-300">
                    <h3 class="text-3xl font-bold mb-2">{{ $stats['total_users'] }}</h3>
                    <p class="text-blue-100 uppercase tracking-wider text-sm font-semibold">Total Applicants</p>
                </div>
                
                <div class="bg-gradient-to-br from-green-500 to-green-700 rounded-lg shadow-xl p-6 text-white transform hover:scale-105 transition-all duration-300">
                    <h3 class="text-3xl font-bold mb-2">{{ $stats['total_applications'] }}</h3>
                    <p class="text-green-100 uppercase tracking-wider text-sm font-semibold">Total Applications</p>
                </div>
                
                <div class="bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-lg shadow-xl p-6 text-white transform hover:scale-105 transition-all duration-300">
                    <h3 class="text-3xl font-bold mb-2">{{ $stats['pending_applications'] }}</h3>
                    <p class="text-yellow-100 uppercase tracking-wider text-sm font-semibold">Pending Applications</p>
                </div>
                
                <div class="bg-gradient-to-br from-purple-500 to-purple-700 rounded-lg shadow-xl p-6 text-white transform hover:scale-105 transition-all duration-300">
                    <h3 class="text-3xl font-bold mb-2">${{ number_format($stats['total_payments'], 2) }}</h3>
                    <p class="text-purple-100 uppercase tracking-wider text-sm font-semibold">Total Paid Out</p>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="mt-8 bg-white dark:bg-gray-800 bg-opacity-50 backdrop-filter backdrop-blur-lg overflow-hidden shadow-sm sm:rounded-lg text-gray-900 dark:text-gray-100 border border-white/20">
                <div class="p-6">
                    <h3 class="text-xl font-bold mb-4">Quick Actions</h3>
                    <div class="flex space-x-4">
                        <a href="{{ route('admin.applications.index') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded shadow transition-colors duration-200">Review Applications</a>
                        <a href="{{ route('admin.charity-types.create') }}" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded shadow transition-colors duration-200">Add Charity Type</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
