<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Applications') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 px-4 py-3 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

             @if(session('error'))
                <div class="mb-4 px-4 py-3 bg-red-100 border border-red-400 text-red-700 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    @if($applications->isEmpty())
                        <p class="text-gray-500 py-4 text-center">You have not submitted any applications yet.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b dark:border-gray-700 text-gray-700 dark:text-gray-300">
                                        <th class="py-4 px-6 font-semibold">Type</th>
                                        <th class="py-4 px-6 font-semibold">Status</th>
                                        <th class="py-4 px-6 font-semibold">Date</th>
                                        <th class="py-4 px-6 font-semibold text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($applications as $app)
                                        <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-750 text-gray-800 dark:text-gray-200">
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
                                            <td class="py-4 px-6 text-right space-x-2">
                                                <a href="{{ route('applicant.applications.show', $app) }}" class="text-indigo-600 hover:text-indigo-400 font-medium transition">View</a>
                                                @if($app->status === 'pending')
                                                    <a href="{{ route('applicant.applications.edit', $app) }}" class="text-blue-600 hover:text-blue-400 font-medium transition">Edit</a>
                                                    <form action="{{ route('applicant.applications.destroy', $app) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to cancel this application?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-400 font-medium transition">Cancel</button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
