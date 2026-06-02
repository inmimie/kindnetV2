<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Add Charity Type') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if ($errors->any())
                        <div class="mb-4 p-4 rounded-md bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-300 border border-red-200 dark:border-red-800 text-sm">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.charity-types.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100" required>
                        </div>
                        
                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Description</label>
                            <textarea name="description" rows="3" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">{{ old('description') }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Picture</label>
                            <input type="file" name="image" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 dark:file:bg-gray-700 file:text-indigo-700 dark:file:text-gray-300 hover:file:bg-indigo-100 dark:hover:file:bg-gray-600 cursor-pointer">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                            <div>
                                <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Start Date</label>
                                <input type="date" name="start_date" value="{{ old('start_date') }}" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                            </div>
                            <div>
                                <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">End Date</label>
                                <input type="date" name="end_date" value="{{ old('end_date') }}" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                            </div>
                        </div>

                        <div class="flex items-center space-x-4">
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">Save</button>
                            <a href="{{ route('admin.charity-types.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded hover:bg-gray-300 dark:hover:bg-gray-600 transition">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
