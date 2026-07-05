<div 
    x-data="{ 
        show: false, 
        title: '', 
        message: '', 
        url: '',
        triggerToast(title, message, url = '') {
            this.title = title;
            this.message = message;
            this.url = url;
            this.show = true;
            setTimeout(() => { this.show = false; }, 6000);
        }
    }"
    x-on:show-toast.window="triggerToast($event.detail.title, $event.detail.message, $event.detail.url)"
    x-cloak
    x-show="show"
    x-transition:enter="transform ease-out duration-300 transition"
    x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
    x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
    x-transition:leave="transition ease-in duration-100"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed bottom-5 right-5 z-50 max-w-sm w-full bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-150 dark:border-gray-700 pointer-events-auto overflow-hidden"
>
    <div class="p-4">
        <div class="flex items-start">
            <!-- Icon -->
            <div class="flex-shrink-0">
                <div class="p-1 bg-indigo-100 text-indigo-600 dark:bg-indigo-900/40 dark:text-indigo-400 rounded-full">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </div>
            </div>

            <!-- Content -->
            <div class="ml-3 w-0 flex-1 pt-0.5">
                <p x-text="title" class="text-sm font-bold text-gray-900 dark:text-white"></p>
                <p x-text="message" class="mt-1 text-sm text-gray-500 dark:text-gray-400"></p>
                
                <div class="mt-3 flex space-x-7" x-show="url">
                    <a :href="url" class="bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-950/40 dark:hover:bg-indigo-900/60 text-indigo-700 dark:text-indigo-300 rounded-md px-2.5 py-1.5 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        View Details
                    </a>
                    <button type="button" @click="show = false" class="text-gray-400 hover:text-gray-500 dark:text-gray-500 dark:hover:text-gray-400 text-xs font-medium focus:outline-none">
                        Dismiss
                    </button>
                </div>
            </div>

            <!-- Close Button -->
            <div class="ml-4 flex-shrink-0 flex">
                <button @click="show = false" class="bg-white dark:bg-gray-800 rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <span class="sr-only">Close</span>
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>
