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
                    <p class="text-yellow-100 uppercase tracking-wider text-sm font-semibold">In Progress Applications</p>
                </div>
                
                <div class="bg-gradient-to-br from-purple-500 to-purple-700 rounded-lg shadow-xl p-6 text-white transform hover:scale-105 transition-all duration-300">
                    <h3 class="text-3xl font-bold mb-2">RM {{ number_format($stats['total_payments'], 2) }}</h3>
                    <p class="text-purple-100 uppercase tracking-wider text-sm font-semibold">Total Paid Out</p>
                </div>
            </div>
            
            <!-- Application Listing and Filtering -->
            <div class="mt-8 bg-white dark:bg-gray-800 bg-opacity-50 backdrop-filter backdrop-blur-lg overflow-hidden shadow-xl sm:rounded-2xl border border-white/20 dark:border-gray-700/30">
                <div class="p-6 sm:p-8">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between border-b border-gray-200 dark:border-gray-700 pb-5 mb-6">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">All Applications</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Search, filter, and view incoming applications.</p>
                        </div>
                    </div>

                    <!-- Filters Panel -->
                    <form method="GET" action="{{ route('admin.dashboard') }}" class="mb-6 space-y-4 md:space-y-0 md:flex md:flex-wrap md:items-end gap-4 bg-gray-50 dark:bg-gray-900/30 p-5 rounded-xl border border-gray-150 dark:border-gray-700/50">
                        <!-- Search input -->
                        <div class="flex-1 min-w-[200px]">
                            <label for="search" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Search</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                                </span>
                                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search name, email..." class="w-full pl-9 pr-4 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500 transition-colors">
                            </div>
                        </div>

                        <!-- Status Filter -->
                        <div class="w-full md:w-40">
                            <label for="status" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Status</label>
                            <select name="status" id="status" class="w-full py-2 px-3 text-sm rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500 transition-colors">
                                <option value="">All Statuses</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>In Progress</option>
                                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>

                        <!-- Charity Type Filter -->
                        <div class="w-full md:w-48">
                            <label for="charity_type_id" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Charity Type</label>
                            <select name="charity_type_id" id="charity_type_id" class="w-full py-2 px-3 text-sm rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500 transition-colors">
                                <option value="">All Charity Types</option>
                                @foreach($charityTypes as $type)
                                    <option value="{{ $type->id }}" {{ request('charity_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Sort -->
                        <div class="w-full md:w-44">
                            <label for="sort" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Sort By</label>
                            <select name="sort" id="sort" class="w-full py-2 px-3 text-sm rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500 transition-colors">
                                <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Newest First</option>
                                <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest First</option>
                            </select>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center gap-2 w-full md:w-auto">
                            <button type="submit" class="flex-grow md:flex-grow-0 px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-semibold shadow transition duration-150 ease-in-out cursor-pointer text-center">
                                Filter
                            </button>
                            @if(request()->anyFilled(['search', 'status', 'charity_type_id', 'sort']))
                                <a href="{{ route('admin.dashboard') }}" class="flex-grow md:flex-grow-0 px-5 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-semibold transition duration-150 ease-in-out text-center">
                                    Reset
                                </a>
                            @endif
                        </div>
                    </form>

                    <!-- Applications List Table -->
                    <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700 bg-white/10 dark:bg-gray-900/10">
                        @if($applications->count() > 0)
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-gray-50/55 dark:bg-gray-900/40 border-b border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300">
                                        <th class="py-3.5 px-5 text-sm font-bold">Applicant</th>
                                        <th class="py-3.5 px-5 text-sm font-bold">Charity Type</th>
                                        <th class="py-3.5 px-5 text-sm font-bold">Requested Amount</th>
                                        <th class="py-3.5 px-5 text-sm font-bold">Status</th>
                                        <th class="py-3.5 px-5 text-sm font-bold">Date</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($applications as $app)
                                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-900/20 transition duration-150">
                                            <!-- Applicant Info -->
                                            <td class="py-4 px-5">
                                                <div class="font-semibold text-gray-900 dark:text-gray-100">
                                                    <a href="{{ route('admin.applications.show', $app) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 hover:underline">
                                                        {{ $app->applicant_name }}
                                                    </a>
                                                </div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $app->user->email }}</div>
                                            </td>
                                            
                                            <!-- Charity Type -->
                                            <td class="py-4 px-5 text-sm text-gray-800 dark:text-gray-200 font-medium">
                                                {{ $app->charityType->name }}
                                            </td>

                                            <!-- Amount Requested -->
                                            <td class="py-4 px-5 text-sm text-gray-800 dark:text-gray-200 font-medium">
                                                @if($app->amount_requested)
                                                    RM {{ number_format($app->amount_requested, 2) }}
                                                @else
                                                    <span class="text-gray-400 dark:text-gray-500 italic">Not Specified</span>
                                                @endif
                                            </td>

                                            <!-- Status badge -->
                                            <td class="py-4 px-5">
                                                <span class="inline-flex items-center whitespace-nowrap px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider
                                                    {{ $app->status === 'approved' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : '' }}
                                                    {{ $app->status === 'rejected' ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' : '' }}
                                                    {{ $app->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400' : '' }}">
                                                    {{ $app->status === 'pending' ? 'In Progress' : $app->status }}
                                                </span>
                                            </td>

                                            <!-- Submission Date -->
                                            <td class="py-4 px-5 text-sm text-gray-500 dark:text-gray-400 font-medium">
                                                {{ $app->created_at->format('M d, Y') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="text-center py-12 bg-white/40 dark:bg-gray-800/10">
                                <div class="w-16 h-16 bg-gray-100 dark:bg-gray-900/50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400 dark:text-gray-500">
                                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                </div>
                                <h3 class="text-base font-bold text-gray-900 dark:text-gray-100">No applications found</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Try resetting the filters or check back later.</p>
                            </div>
                        @endif
                    </div>

                    <!-- Pagination -->
                    @if($applications->hasPages())
                        <div class="mt-6">
                            {{ $applications->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
