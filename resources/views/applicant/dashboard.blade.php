<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Applicant Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-8 text-gray-900 dark:text-gray-100 flex justify-between items-center bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-700">
                    <div>
                        <h3 class="text-3xl font-bold text-indigo-700 dark:text-indigo-400">Welcome, {{ Auth::user()->name }}!</h3>
                        <p class="mt-2 text-gray-600 dark:text-gray-400">Choose an aid category below to start your application.</p>
                    </div>
                </div>
            </div>

            <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-6 px-2 border-l-4 border-indigo-500 pl-4">Available Financial Aid</h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
                @forelse($charityTypes as $type)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 relative group">
                        <div class="h-2 bg-gradient-to-r from-indigo-500 to-purple-500"></div>
                        <div class="p-6">
                            <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ $type->name }}</h4>
                            <p class="text-gray-600 dark:text-gray-400 text-sm mb-6 line-clamp-3">{{ $type->description ?? 'Financial aid application via KINDNET.' }}</p>
                            
                            <a href="{{ route('applicant.applications.create', ['charity_type_id' => $type->id]) }}" class="inline-flex w-full justify-center items-center px-4 py-2 bg-indigo-50 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 rounded-lg font-semibold hover:bg-indigo-600 hover:text-white dark:hover:bg-indigo-500 dark:hover:text-white transition-colors">
                                Apply Now
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-1 sm:col-span-2 lg:col-span-3 bg-white dark:bg-gray-800 p-8 rounded-xl text-center shadow-sm border border-gray-100 dark:border-gray-700">
                        <p class="text-gray-500 dark:text-gray-400">No active charity types available right now.</p>
                    </div>
                @endforelse
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <h3 class="text-lg font-bold mb-6 text-gray-900 dark:text-white border-b dark:border-gray-700 pb-3">Recent Applications</h3>
                    @if($applications->isEmpty())
                        <p class="text-gray-500 py-6 text-center italic">You have not submitted any applications yet.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-gray-50 dark:bg-gray-900 border-b dark:border-gray-700">
                                        <th class="py-3 px-6 font-semibold text-sm text-gray-600 dark:text-gray-400">Type of Aid</th>
                                        <th class="py-3 px-6 font-semibold text-sm text-gray-600 dark:text-gray-400">Date Submitted</th>
                                        <th class="py-3 px-6 font-semibold text-sm text-gray-600 dark:text-gray-400">Status</th>
                                        <th class="py-3 px-6 font-semibold text-sm text-right text-gray-600 dark:text-gray-400">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($applications as $app)
                                        <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">
                                            <td class="py-4 px-6 font-medium text-gray-900 dark:text-gray-200">{{ $app->charityType->name }}</td>
                                            <td class="py-4 px-6 text-sm text-gray-500">{{ $app->created_at->format('M d, Y') }}</td>
                                            <td class="py-4 px-6">
                                                @if($app->status === 'approved')
                                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800">Approved</span>
                                                @elseif($app->status === 'rejected')
                                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800">Rejected</span>
                                                @else
                                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800">Pending</span>
                                                @endif
                                            </td>
                                            <td class="py-4 px-6 text-right">
                                                <a href="{{ route('applicant.applications.show', $app) }}" class="text-indigo-600 hover:text-indigo-900 font-semibold text-sm bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded transition-colors">View Details</a>
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
