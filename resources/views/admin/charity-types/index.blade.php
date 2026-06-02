<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manage Charity Types') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6 flex justify-end">
                <a href="{{ route('admin.charity-types.create') }}" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded shadow transition-colors">Add New Charity Type</a>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if(session('success'))
                        <div class="mb-4 px-4 py-3 bg-green-100 border border-green-400 text-green-700 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach($charityTypes as $type)
                            <div class="bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between">
                                @if ($type->image)
                                    <img src="{{ asset('storage/' . $type->image) }}" class="w-full h-40 object-cover border-b border-gray-200 dark:border-gray-600">
                                @else
                                    <div class="w-full h-40 bg-gradient-to-br from-green-400 to-indigo-500 flex items-center justify-center text-white font-bold text-lg border-b border-gray-200 dark:border-gray-600">
                                        {{ $type->name }}
                                    </div>
                                @endif
                                <div class="p-6 flex-1 flex flex-col justify-between">
                                    <div>
                                        <div class="flex justify-between items-center mb-2">
                                            <h3 class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-green-500 to-indigo-500">{{ $type->name }}</h3>
                                            @if ($type->status === 'open')
                                                <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300">Open</span>
                                            @else
                                                <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300">Closed</span>
                                            @endif
                                        </div>
                                        
                                        @if ($type->start_date || $type->end_date)
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-4 font-semibold flex items-center">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                @if ($type->start_date && $type->end_date)
                                                    {{ $type->start_date->format('M d, Y') }} - {{ $type->end_date->format('M d, Y') }}
                                                @elseif ($type->start_date)
                                                    Starts: {{ $type->start_date->format('M d, Y') }}
                                                @else
                                                    Ends: {{ $type->end_date->format('M d, Y') }}
                                                @endif
                                            </p>
                                        @endif
                                        
                                        <p class="text-gray-600 dark:text-gray-300 mb-6 text-sm line-clamp-3">{{ $type->description }}</p>
                                    </div>
                                    
                                    <div class="flex items-center space-x-4 border-t pt-4 dark:border-gray-600">
                                        <a href="{{ route('admin.charity-types.show', $type) }}" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 font-medium">View</a>
                                        <a href="{{ route('admin.charity-types.edit', $type) }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 font-medium">Edit</a>
                                        <form action="{{ route('admin.charity-types.destroy', $type) }}" method="POST" class="inline-flex items-center" onsubmit="return confirm('Are you sure you want to delete this Charity Type?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 font-medium">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
