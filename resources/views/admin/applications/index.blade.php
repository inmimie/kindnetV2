<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Incoming Applications') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if(session('success'))
                        <div class="mb-4 px-4 py-3 bg-green-100 border border-green-400 text-green-700 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Filters Panel -->
                    <form method="GET" action="{{ route('admin.applications.index') }}" class="mb-6 space-y-4 md:space-y-0 md:flex md:flex-wrap md:items-end gap-4 bg-gray-50 dark:bg-gray-900/30 p-5 rounded-xl border border-gray-150 dark:border-gray-700/50">
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
                                <a href="{{ route('admin.applications.index') }}" class="flex-grow md:flex-grow-0 px-5 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-semibold transition duration-150 ease-in-out text-center">
                                    Reset
                                </a>
                            @endif
                        </div>
                    </form>

                    <div class="overflow-x-auto">
                        @if(count($applications) > 0)
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b dark:border-gray-700 text-gray-700 dark:text-gray-300">
                                        <th class="py-4 px-6 font-semibold">Applicant</th>
                                        <th class="py-4 px-6 font-semibold">Type</th>
                                        <th class="py-4 px-6 font-semibold">Status</th>
                                        <th class="py-4 px-6 font-semibold">Submit Date</th>
                                        <th class="py-4 px-6 font-semibold">Approved Date</th>
                                        <th class="py-4 px-6 font-semibold text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($applications as $app)
                                        <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-750">
                                            <td class="py-4 px-6">
                                                <div class="font-medium text-gray-900 dark:text-gray-100">{{ $app->user->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $app->user->email }}</div>
                                            </td>
                                            <td class="py-4 px-6">{{ $app->charityType->name }}</td>
                                            <td class="py-4 px-6">
                                                <span class="whitespace-nowrap px-3 py-1 rounded-full text-xs font-bold 
                                                    {{ $app->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                                    {{ $app->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}
                                                    {{ $app->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                                    {{ $app->status === 'pending' ? 'In Progress' : ucfirst($app->status) }}
                                                </span>
                                            </td>
                                            <td class="py-4 px-6 text-gray-500">{{ $app->created_at->format('M d, Y') }}</td>
                                            <td class="py-4 px-6 text-gray-500">
                                                @if($app->status === 'approved' && $app->approved_at)
                                                    {{ $app->approved_at->format('M d, Y') }}
                                                @else
                                                    <span class="text-gray-400 italic text-sm">-</span>
                                                @endif
                                            </td>
                                            <td class="py-4 px-6 text-right">
                                                <a href="{{ route('admin.applications.show', $app) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring ring-blue-300 transition ease-in-out duration-150">
                                                    Review
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="text-center py-12">
                                <div class="w-16 h-16 bg-gray-100 dark:bg-gray-900 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                </div>
                                <h3 class="text-base font-bold text-gray-900 dark:text-gray-100">No applications found</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Try resetting the filters or check back later.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
