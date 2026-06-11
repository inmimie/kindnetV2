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

                    <div class="overflow-x-auto">
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
