<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('View Charity Type') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row gap-8 mb-6">
                        @if ($charityType->image)
                            <div class="w-full md:w-1/3">
                                <img src="{{ asset('storage/' . $charityType->image) }}" class="w-full h-auto rounded-lg shadow border border-gray-200 dark:border-gray-700 object-cover">
                            </div>
                        @endif
                        <div class="flex-1">
                            <h3 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-green-500 to-indigo-500 mb-4">{{ $charityType->name }}</h3>
                            
                            @if ($charityType->start_date || $charityType->end_date)
                                <div class="mb-4 inline-flex items-center px-3 py-1.5 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 text-sm font-semibold">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    @if ($charityType->start_date && $charityType->end_date)
                                        {{ $charityType->start_date->format('M d, Y') }} - {{ $charityType->end_date->format('M d, Y') }}
                                    @elseif ($charityType->start_date)
                                        Starts: {{ $charityType->start_date->format('M d, Y') }}
                                    @else
                                        Ends: {{ $charityType->end_date->format('M d, Y') }}
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 dark:bg-gray-700/50 p-6 rounded-lg border border-gray-200 dark:border-gray-600 mb-8">
                        <h4 class="font-bold text-gray-700 dark:text-gray-300 mb-3 uppercase tracking-wide text-sm border-b dark:border-gray-600 pb-2">Description</h4>
                        <p class="text-gray-600 dark:text-gray-400 whitespace-pre-line">{{ $charityType->description ?: 'No description provided for this charity type.' }}</p>
                    </div>

                    <div class="flex items-center justify-between border-t border-gray-200 dark:border-gray-700 pt-6">
                        <a href="{{ route('admin.charity-types.index') }}" class="px-6 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 font-semibold rounded hover:bg-gray-300 dark:hover:bg-gray-600 transition shadow-sm">&larr; Back to List</a>
                        <div class="flex space-x-3">
                            <a href="{{ route('admin.charity-types.edit', $charityType) }}" class="px-6 py-2 bg-indigo-600 text-white font-semibold rounded hover:bg-indigo-700 transition shadow-sm">Edit</a>
                            <form action="{{ route('admin.charity-types.destroy', $charityType) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this Charity Type?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-6 py-2 bg-red-600 text-white font-semibold rounded hover:bg-red-700 transition shadow-sm">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
