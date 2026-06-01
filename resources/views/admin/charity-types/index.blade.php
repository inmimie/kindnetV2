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
                            <div class="bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow">
                                <div class="flex justify-between items-start mb-4">
                                    <h3 class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-green-500 to-indigo-500">{{ $type->name }}</h3>
                                </div>
                                <p class="text-gray-600 dark:text-gray-300 mb-6 text-sm">{{ $type->description }}</p>
                                
                                <div class="flex space-x-4 border-t pt-4 dark:border-gray-600">
                                    <a href="{{ route('admin.charity-types.show', $type) }}" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 font-medium">View</a>
                                    <a href="{{ route('admin.charity-types.edit', $type) }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 font-medium">Edit</a>
                                    <form action="{{ route('admin.charity-types.destroy', $type) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this Charity Type?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 font-medium">Delete</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
