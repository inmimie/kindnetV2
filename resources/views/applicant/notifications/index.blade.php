<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Notifications') }}
            </h2>
            
            @if($notifications->contains(fn($n) => $n->unread()))
                <form action="{{ route('applicant.notifications.read-all') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-sm font-semibold text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors bg-indigo-50 dark:bg-indigo-950/40 px-4 py-2 rounded-lg">
                        {{ __('Mark all as read') }}
                    </button>
                </form>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/30 text-green-800 dark:text-green-300 rounded-xl border border-green-150 dark:border-green-800/30 flex items-center shadow-sm">
                    <svg class="w-5 h-5 mr-3 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    @if($notifications->isEmpty())
                        <div class="py-16 text-center">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-500 mb-4 shadow-inner">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">No notifications yet</h3>
                            <p class="text-gray-500 dark:text-gray-400 max-w-sm mx-auto">We'll let you know when there are updates on your charity applications.</p>
                        </div>
                    @else
                        <div class="divide-y divide-gray-150 dark:divide-gray-700">
                            @foreach($notifications as $notification)
                                <div class="py-5 flex items-start gap-4 transition-colors duration-200 {{ $notification->unread() ? 'bg-indigo-50/30 dark:bg-indigo-950/10 -mx-6 px-6 rounded-lg' : '' }}">
                                    <!-- Status Icon -->
                                    <div class="shrink-0">
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $notification->unread() ? 'bg-indigo-100 text-indigo-600 dark:bg-indigo-900/60 dark:text-indigo-400' : 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400' }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                    </div>

                                    <!-- Content -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between gap-2">
                                            <p class="text-sm font-bold text-gray-900 dark:text-white">
                                                {{ $notification->data['charity_type_name'] ?? 'Application Update' }}
                                            </p>
                                            <span class="text-xs text-gray-400 whitespace-nowrap">
                                                {{ $notification->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">
                                            {{ $notification->data['message'] }}
                                        </p>
                                        
                                        @if(isset($notification->data['application_id']))
                                            <div class="mt-2.5">
                                                <a href="{{ route('applicant.applications.show', $notification->data['application_id']) }}" class="inline-flex items-center text-xs font-semibold text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                    View Application
                                                    <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                                    </svg>
                                                </a>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Actions -->
                                    @if($notification->unread())
                                        <div class="shrink-0 self-center">
                                            <form action="{{ route('applicant.notifications.read', $notification->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" title="Mark as read" class="p-1 rounded-full text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 focus:outline-none transition-colors">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $notifications->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
