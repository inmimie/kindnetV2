<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Payments History') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    @if(session('success'))
                        <div class="mb-4 px-4 py-3 bg-green-100 border border-green-400 text-green-700 rounded-lg flex items-center gap-2">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($payments->isEmpty())
                        <div class="text-center py-12">
                            <svg class="mx-auto h-16 w-16 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            <h3 class="mt-4 text-lg font-medium text-gray-500 dark:text-gray-400">Tiada rekod pembayaran</h3>
                            <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Pembayaran akan muncul di sini selepas diproses.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b dark:border-gray-700 text-gray-700 dark:text-gray-300">
                                        <th class="py-4 px-4 font-semibold text-xs uppercase tracking-wider">Transaction ID</th>
                                        <th class="py-4 px-4 font-semibold text-xs uppercase tracking-wider">Application</th>
                                        <th class="py-4 px-4 font-semibold text-xs uppercase tracking-wider">Penerima</th>
                                        <th class="py-4 px-4 font-semibold text-xs uppercase tracking-wider">Payment Gateway</th>
                                        <th class="py-4 px-4 font-semibold text-xs uppercase tracking-wider">Kaedah</th>
                                        <th class="py-4 px-4 font-semibold text-xs uppercase tracking-wider">Jumlah</th>
                                        <th class="py-4 px-4 font-semibold text-xs uppercase tracking-wider">Status</th>
                                        <th class="py-4 px-4 font-semibold text-xs uppercase tracking-wider">Tarikh</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($payments as $payment)
                                        <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors duration-150">
                                            <td class="py-4 px-4">
                                                <span class="font-mono text-xs bg-gray-100 dark:bg-gray-900 px-2 py-1 rounded">{{ $payment->transaction_id }}</span>
                                            </td>
                                            <td class="py-4 px-4">
                                                <a href="{{ route('admin.applications.show', $payment->application) }}" class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 font-medium text-sm">
                                                    App #{{ $payment->application->id }}
                                                </a>
                                            </td>
                                            <td class="py-4 px-4 text-sm text-gray-900 dark:text-gray-100">{{ $payment->application->user->name }}</td>
                                            <td class="py-4 px-4">
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold {{ $payment->gateway_badge_class }}">
                                                    {{ $payment->gateway_display_name }}
                                                </span>
                                            </td>
                                            <td class="py-4 px-4 text-sm text-gray-600 dark:text-gray-400">{{ $payment->payment_method ?? '—' }}</td>
                                            <td class="py-4 px-4">
                                                <span class="font-bold text-green-600 dark:text-green-400">RM {{ number_format($payment->amount, 2) }}</span>
                                            </td>
                                            <td class="py-4 px-4">
                                                @if($payment->status === 'completed')
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 text-xs font-bold rounded-full uppercase">
                                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                                        {{ $payment->status }}
                                                    </span>
                                                @elseif($payment->status === 'failed')
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 text-xs font-bold rounded-full uppercase">
                                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                                                        {{ $payment->status }}
                                                    </span>
                                                @else
                                                    <span class="px-2.5 py-1 bg-yellow-100 text-yellow-800 text-xs font-bold rounded-full uppercase">{{ $payment->status }}</span>
                                                @endif
                                            </td>
                                            <td class="py-4 px-4 text-sm text-gray-500 dark:text-gray-400">{{ $payment->created_at->format('d M Y, h:i A') }}</td>
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
