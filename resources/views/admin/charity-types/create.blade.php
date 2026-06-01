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
                    <form action="{{ route('admin.charity-types.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Name</label>
                            <input type="text" name="name" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100" required>
                        </div>
                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Description</label>
                            <textarea name="description" rows="3" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"></textarea>
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
