<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Process Payment
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            @if($errors->any())
                <div class="mb-6 px-4 py-3 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                    <ul class="list-disc list-inside text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Application Summary Card --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-xl border border-gray-200 dark:border-gray-700 mb-6">
                <div class="p-6 bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-indigo-200 text-xs uppercase tracking-wider font-semibold">Application</p>
                            <h3 class="text-2xl font-bold mt-1">#{{ $application->id }} — {{ $application->user->name }}</h3>
                            <p class="text-indigo-200 text-sm mt-1">{{ $application->charityType->name ?? 'N/A' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-indigo-200 text-xs uppercase tracking-wider font-semibold">Jumlah Dimohon</p>
                            <p class="text-3xl font-black mt-1">RM {{ number_format($application->amount_requested ?? 0, 2) }}</p>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center gap-4 text-sm text-indigo-200">
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            Bank: {{ $application->bank_name ?? 'N/A' }}
                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Akaun: {{ $application->account_number ?? 'N/A' }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Payment Form --}}
            <form action="{{ route('admin.payments.store') }}" method="POST" id="paymentForm">
                @csrf
                <input type="hidden" name="application_id" value="{{ $application->id }}">

                {{-- Step 1: Choose Gateway --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-xl border border-gray-200 dark:border-gray-700 mb-6">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                        <div class="flex items-center gap-3">
                            <span class="flex items-center justify-center w-8 h-8 rounded-full bg-indigo-600 text-white text-sm font-bold">1</span>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Pilih Payment Gateway</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Pilih kaedah pembayaran yang ingin digunakan</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4" id="gatewayOptions">
                            @foreach($gateways as $key => $gateway)
                            <label class="gateway-card relative cursor-pointer group" data-gateway="{{ $key }}">
                                <input type="radio" name="payment_gateway" value="{{ $key }}" class="sr-only peer" {{ old('payment_gateway') === $key ? 'checked' : '' }} required>
                                <div class="border-2 rounded-xl p-5 transition-all duration-300 
                                    border-gray-200 dark:border-gray-700 
                                    peer-checked:border-indigo-500 peer-checked:bg-indigo-50 dark:peer-checked:bg-indigo-900/20 
                                    peer-checked:shadow-lg peer-checked:shadow-indigo-500/20
                                    hover:border-gray-300 dark:hover:border-gray-600
                                    hover:shadow-md">
                                    
                                    {{-- Gateway Icon --}}
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="w-12 h-12 rounded-lg flex items-center justify-center
                                            @if($gateway['color'] === 'blue') bg-blue-100 dark:bg-blue-900/30 text-blue-600
                                            @elseif($gateway['color'] === 'pink') bg-pink-100 dark:bg-pink-900/30 text-pink-600
                                            @elseif($gateway['color'] === 'purple') bg-purple-100 dark:bg-purple-900/30 text-purple-600
                                            @elseif($gateway['color'] === 'orange') bg-orange-100 dark:bg-orange-900/30 text-orange-600
                                            @else bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400
                                            @endif">
                                            @if($key === 'fpx')
                                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                            @elseif($key === 'duitnow')
                                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                            @elseif($key === 'toyyibpay')
                                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                            @elseif($key === 'billplz')
                                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21l-7-5-7 5V5a2 2 0 012-2h10a2 2 0 012 2v16z"/></svg>
                                            @else
                                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                            @endif
                                        </div>
                                        <div class="w-5 h-5 rounded-full border-2 border-gray-300 dark:border-gray-600 peer-checked:border-indigo-500 peer-checked:bg-indigo-500 flex items-center justify-center transition-all duration-200 gateway-check">
                                            <svg class="w-3 h-3 text-white hidden check-icon" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        </div>
                                    </div>

                                    <h4 class="font-bold text-gray-900 dark:text-gray-100 text-sm">{{ $gateway['name'] }}</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 leading-relaxed">{{ $gateway['description'] }}</p>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Step 2: Payment Method --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-xl border border-gray-200 dark:border-gray-700 mb-6 transition-all duration-300" id="methodSection" style="display: none;">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                        <div class="flex items-center gap-3">
                            <span class="flex items-center justify-center w-8 h-8 rounded-full bg-indigo-600 text-white text-sm font-bold">2</span>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Kaedah Pembayaran</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Pilih kaedah spesifik untuk gateway yang dipilih</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <select name="payment_method" id="paymentMethod" class="block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm text-sm">
                            <option value="">— Pilih Kaedah —</option>
                        </select>
                    </div>
                </div>

                {{-- Step 3: Amount & Notes --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-xl border border-gray-200 dark:border-gray-700 mb-6">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                        <div class="flex items-center gap-3">
                            <span class="flex items-center justify-center w-8 h-8 rounded-full bg-indigo-600 text-white text-sm font-bold" id="amountStepNum">2</span>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Jumlah & Maklumat Pembayaran</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Masukkan jumlah bayaran dan nota tambahan</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 space-y-6">
                        {{-- Amount --}}
                        <div>
                            <label for="amount" class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">Jumlah Bayaran (RM)</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <span class="text-gray-400 text-lg font-bold">RM</span>
                                </div>
                                <input type="number" step="0.01" name="amount" id="amount"
                                    value="{{ old('amount', $application->amount_requested ?? '') }}"
                                    class="pl-14 text-2xl font-bold block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 h-16"
                                    required placeholder="0.00">
                            </div>
                            @if($application->amount_requested)
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Pemohon memohon: RM {{ number_format($application->amount_requested, 2) }}
                                </p>
                            @endif
                        </div>

                        {{-- Notes --}}
                        <div>
                            <label for="notes" class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">Nota Pembayaran <span class="text-gray-400">(Pilihan)</span></label>
                            <textarea name="notes" id="notes" rows="3"
                                class="block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                placeholder="Contoh: Bayaran semester 1, bantuan kecemasan, dll...">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Payment Summary & Submit --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-xl border-2 border-green-200 dark:border-green-800 mb-6" id="summaryCard" style="display: none;">
                    <div class="px-6 py-4 bg-green-50 dark:bg-green-900/20 border-b border-green-200 dark:border-green-800">
                        <h3 class="text-lg font-bold text-green-800 dark:text-green-200 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Ringkasan Pembayaran
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-2 gap-4 text-sm mb-6">
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Penerima</span>
                                <p class="font-bold text-gray-900 dark:text-gray-100">{{ $application->user->name }}</p>
                            </div>
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Gateway</span>
                                <p class="font-bold text-gray-900 dark:text-gray-100" id="summaryGateway">—</p>
                            </div>
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Kaedah</span>
                                <p class="font-bold text-gray-900 dark:text-gray-100" id="summaryMethod">—</p>
                            </div>
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Jumlah</span>
                                <p class="font-bold text-2xl text-green-600" id="summaryAmount">RM 0.00</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center justify-between">
                    <a href="{{ route('admin.applications.show', $application) }}" class="inline-flex items-center px-5 py-3 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 font-medium transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        Batal
                    </a>
                    <button type="submit" id="submitBtn" disabled
                        class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 disabled:from-gray-400 disabled:to-gray-500 disabled:cursor-not-allowed text-white font-bold rounded-xl shadow-lg shadow-green-500/30 disabled:shadow-none transition-all duration-300 transform hover:-translate-y-0.5 disabled:transform-none text-sm uppercase tracking-wider">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        Proses Pembayaran Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- JavaScript for interactive gateway selection --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const gateways = @json($gateways);
            const gatewayRadios = document.querySelectorAll('input[name="payment_gateway"]');
            const methodSection = document.getElementById('methodSection');
            const methodSelect = document.getElementById('paymentMethod');
            const amountInput = document.getElementById('amount');
            const submitBtn = document.getElementById('submitBtn');
            const summaryCard = document.getElementById('summaryCard');
            const summaryGateway = document.getElementById('summaryGateway');
            const summaryMethod = document.getElementById('summaryMethod');
            const summaryAmount = document.getElementById('summaryAmount');
            const amountStepNum = document.getElementById('amountStepNum');

            function updateCheckIcons() {
                document.querySelectorAll('.gateway-card').forEach(card => {
                    const radio = card.querySelector('input[type="radio"]');
                    const checkCircle = card.querySelector('.gateway-check');
                    const checkIcon = card.querySelector('.check-icon');
                    if (radio.checked) {
                        checkCircle.classList.add('bg-indigo-500', 'border-indigo-500');
                        checkCircle.classList.remove('border-gray-300', 'dark:border-gray-600');
                        checkIcon.classList.remove('hidden');
                    } else {
                        checkCircle.classList.remove('bg-indigo-500', 'border-indigo-500');
                        checkCircle.classList.add('border-gray-300', 'dark:border-gray-600');
                        checkIcon.classList.add('hidden');
                    }
                });
            }

            function updateMethodDropdown(gatewayKey) {
                const gateway = gateways[gatewayKey];
                methodSelect.innerHTML = '<option value="">— Pilih Kaedah —</option>';
                
                if (gateway && gateway.methods) {
                    gateway.methods.forEach(method => {
                        const opt = document.createElement('option');
                        opt.value = method;
                        opt.textContent = method;
                        methodSelect.appendChild(opt);
                    });
                    methodSection.style.display = 'block';
                    amountStepNum.textContent = '3';
                } else {
                    methodSection.style.display = 'none';
                    amountStepNum.textContent = '2';
                }
            }

            function updateSummary() {
                const selectedGateway = document.querySelector('input[name="payment_gateway"]:checked');
                const amount = parseFloat(amountInput.value) || 0;
                const method = methodSelect.value;

                if (selectedGateway && amount > 0) {
                    summaryCard.style.display = 'block';
                    summaryGateway.textContent = gateways[selectedGateway.value]?.name || '—';
                    summaryMethod.textContent = method || 'Tiada dipilih';
                    summaryAmount.textContent = 'RM ' + amount.toLocaleString('en-MY', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    submitBtn.disabled = false;
                } else {
                    summaryCard.style.display = 'none';
                    submitBtn.disabled = true;
                }
            }

            gatewayRadios.forEach(radio => {
                radio.addEventListener('change', function () {
                    updateCheckIcons();
                    updateMethodDropdown(this.value);
                    updateSummary();
                });
            });

            methodSelect.addEventListener('change', updateSummary);
            amountInput.addEventListener('input', updateSummary);

            // Initialize if there's a pre-selected value
            const preSelected = document.querySelector('input[name="payment_gateway"]:checked');
            if (preSelected) {
                updateCheckIcons();
                updateMethodDropdown(preSelected.value);
                updateSummary();
            }

            // Confirmation on submit
            document.getElementById('paymentForm').addEventListener('submit', function (e) {
                const amount = parseFloat(amountInput.value) || 0;
                if (!confirm('Anda pasti ingin memproses pembayaran RM ' + amount.toFixed(2) + '? Tindakan ini tidak boleh dibatalkan.')) {
                    e.preventDefault();
                }
            });
        });
    </script>
</x-app-layout>
