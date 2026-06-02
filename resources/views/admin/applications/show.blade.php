<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight flex justify-between items-center">
            <span>Review Application #{{ $application->id }}</span>
            <span class="text-sm px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full font-bold">Aid: {{ $application->charityType->name }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Left Column: Details -->
            <div class="lg:col-span-2 space-y-6">
                @if(session('success'))
                    <div class="px-4 py-3 bg-green-100 border border-green-400 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="px-4 py-3 bg-red-100 border border-red-400 text-red-700 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 border-l-4 border-l-indigo-500">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">Applicant Information</h3>
                    </div>
                    <div class="p-6 grid grid-cols-2 sm:grid-cols-3 gap-y-6 gap-x-4">
                        <div>
                            <span class="block text-xs uppercase text-gray-500">Name</span>
                            <span class="font-medium text-sm">{{ $application->applicant_name }}</span>
                        </div>
                        <div>
                            <span class="block text-xs uppercase text-gray-500">IC Number</span>
                            <span class="font-medium text-sm">{{ $application->applicant_ic }}</span>
                        </div>
                        <div>
                            <span class="block text-xs uppercase text-gray-500">Date of Birth</span>
                            <span class="font-medium text-sm">{{ $application->applicant_dob }}</span>
                        </div>
                        <div>
                            <span class="block text-xs uppercase text-gray-500">Gender</span>
                            <span class="font-medium text-sm">{{ $application->applicant_gender === 'Perempuan' ? 'Female' : ($application->applicant_gender === 'Lelaki' ? 'Male' : $application->applicant_gender) }}</span>
                        </div>
                        <div>
                            <span class="block text-xs uppercase text-gray-500">Marital Status</span>
                            <span class="font-medium text-sm">{{ $application->applicant_marital_status }}</span>
                        </div>
                        <div>
                            <span class="block text-xs uppercase text-gray-500">Occupation</span>
                            <span class="font-medium text-sm">{{ $application->applicant_occupation }}</span>
                        </div>
                        <div class="col-span-2 sm:col-span-3">
                            <span class="block text-xs uppercase text-gray-500">Full Address</span>
                            <span class="font-medium text-sm">{{ $application->applicant_address }}</span>
                        </div>
                        <div>
                            <span class="block text-xs uppercase text-gray-500">Mobile Phone</span>
                            <span class="font-medium text-sm">{{ $application->applicant_phone }}</span>
                        </div>
                        <div class="col-span-2">
                            <span class="block text-xs uppercase text-gray-500">Main Account Email</span>
                            <span class="font-medium text-sm">{{ $application->user->email }}</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 border-l-4 border-l-orange-500 flex justify-between items-center">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">Family Finances</h3>
                        <span class="bg-orange-100 text-orange-800 text-xs font-bold px-3 py-1 rounded-full uppercase">Dependents: {{ $application->total_dependents }}</span>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <!-- Father -->
                            <div class="border rounded p-4 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                                <h4 class="font-bold mb-3 border-b pb-2 dark:border-gray-700">Father's Details</h4>
                                <table class="w-full text-sm">
                                    <tr><td class="text-gray-500 py-1">Name:</td><td class="font-medium">{{ $application->father_name }}</td></tr>
                                    <tr><td class="text-gray-500 py-1">Occupation:</td><td class="font-medium">{{ $application->father_occupation }}</td></tr>
                                    <tr><td class="text-gray-500 py-1">Income:</td><td class="font-bold text-green-600">RM {{ number_format($application->father_income, 2) }}</td></tr>
                                </table>
                            </div>
                            <!-- Mother -->
                            <div class="border rounded p-4 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                                <h4 class="font-bold mb-3 border-b pb-2 dark:border-gray-700">Mother's Details</h4>
                                <table class="w-full text-sm">
                                    <tr><td class="text-gray-500 py-1">Name:</td><td class="font-medium">{{ $application->mother_name }}</td></tr>
                                    <tr><td class="text-gray-500 py-1">Occupation:</td><td class="font-medium">{{ $application->mother_occupation }}</td></tr>
                                    <tr><td class="text-gray-500 py-1">Income:</td><td class="font-bold text-green-600">RM {{ number_format($application->mother_income, 2) }}</td></tr>
                                </table>
                            </div>
                        </div>
                        <div class="mt-4 p-4 bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded flex justify-between items-center">
                            <span class="font-bold text-orange-900 dark:text-orange-200">Total Household Income</span>
                            <span class="text-xl font-black text-orange-900 dark:text-orange-400">RM {{ number_format($application->total_income, 2) }}</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 border-l-4 border-l-blue-500">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">Education & Banking Information</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <!-- Education -->
                            <div class="border rounded p-4 dark:border-gray-700 bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800">
                                <h4 class="font-bold mb-3 border-b border-blue-200 dark:border-blue-800 pb-2 text-blue-900 dark:text-blue-200">Education Details</h4>
                                <table class="w-full text-sm">
                                    <tr><td class="text-gray-500 py-1 w-1/3">University:</td><td class="font-medium">{{ $application->university_name }}</td></tr>
                                    <tr><td class="text-gray-500 py-1">Course:</td><td class="font-medium">{{ $application->course_name }}</td></tr>
                                    <tr><td class="text-gray-500 py-1">Level:</td><td class="font-medium">{{ $application->study_level }}</td></tr>
                                    <tr><td class="text-gray-500 py-1">Duration:</td><td class="font-medium">{{ $application->start_year }} - {{ $application->end_year }}</td></tr>
                                </table>
                            </div>
                            <!-- Bank -->
                            <div class="border rounded p-4 dark:border-gray-700 bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800">
                                <h4 class="font-bold mb-3 border-b border-green-200 dark:border-green-800 pb-2 text-green-900 dark:text-green-200">Banking Details</h4>
                                <table class="w-full text-sm">
                                    <tr><td class="text-gray-500 py-1 w-1/3">Bank:</td><td class="font-medium">{{ $application->bank_name }}</td></tr>
                                    <tr><td class="text-gray-500 py-1">Account No:</td><td class="font-medium font-mono text-lg tracking-wider text-gray-900 dark:text-gray-100">{{ $application->account_number }}</td></tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 border-l-4 border-l-purple-500">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">Supporting Documents</h3>
                    </div>
                    <div class="p-6">
                        <ul class="space-y-4">
                            <li class="flex items-center justify-between border-b pb-2 dark:border-gray-700">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">1. Applicant IC (Salinan Kad Pengenalan Pelajar)</span>
                                @if($application->doc_student_ic)
                                    <a href="{{ Storage::url($application->doc_student_ic) }}" target="_blank" class="text-sm text-indigo-600 hover:text-indigo-800 font-bold underline">Download / View</a>
                                @else
                                    <span class="text-sm text-red-500 italic">No Document</span>
                                @endif
                            </li>
                            <li class="flex items-center justify-between border-b pb-2 dark:border-gray-700">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">2. Applicant Birth Cert (Salinan Sijil Lahir Pelajar)</span>
                                @if($application->doc_student_birth_cert)
                                    <a href="{{ Storage::url($application->doc_student_birth_cert) }}" target="_blank" class="text-sm text-indigo-600 hover:text-indigo-800 font-bold underline">Download / View</a>
                                @else
                                    <span class="text-sm text-red-500 italic">No Document</span>
                                @endif
                            </li>
                            <li class="flex items-center justify-between border-b pb-2 dark:border-gray-700">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">3. Mother's IC (Salinan Kad Pengenalan Ibu)</span>
                                @if($application->doc_mother_ic)
                                    <a href="{{ Storage::url($application->doc_mother_ic) }}" target="_blank" class="text-sm text-indigo-600 hover:text-indigo-800 font-bold underline">Download / View</a>
                                @else
                                    <span class="text-sm text-red-500 italic">No Document</span>
                                @endif
                            </li>
                            <li class="flex items-center justify-between border-b pb-2 dark:border-gray-700">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">4. Father's IC (Salinan Kad Pengenalan Bapa)</span>
                                @if($application->doc_father_ic)
                                    <a href="{{ Storage::url($application->doc_father_ic) }}" target="_blank" class="text-sm text-indigo-600 hover:text-indigo-800 font-bold underline">Download / View</a>
                                @else
                                    <span class="text-sm text-red-500 italic">No Document</span>
                                @endif
                            </li>
                            <li class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">5. IPT Offer Letter (Salinan Surat Tawaran IPT)</span>
                                @if($application->doc_offer_letter)
                                    <a href="{{ Storage::url($application->doc_offer_letter) }}" target="_blank" class="text-sm text-indigo-600 hover:text-indigo-800 font-bold underline">Download / View</a>
                                @else
                                    <span class="text-sm text-red-500 italic">No Document</span>
                                @endif
                            </li>
                        </ul>
                    </div>
                </div>

                @if($application->payment)
                <div class="bg-green-50 dark:bg-green-900/20 shadow sm:rounded-lg overflow-hidden border border-green-200 dark:border-green-800">
                    <div class="px-6 py-5 border-b border-green-200 dark:border-green-800 bg-green-100 dark:bg-green-900/40 border-l-4 border-l-green-600">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg leading-6 font-bold text-green-900 dark:text-green-100">Payment Processed</h3>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $application->payment->gateway_badge_class }}">
                                {{ $application->payment->gateway_display_name }}
                            </span>
                        </div>
                    </div>
                    <div class="p-6 grid grid-cols-2 gap-4">
                        <div>
                            <dt class="text-xs uppercase font-medium text-green-700 dark:text-green-400">Amount Paid</dt>
                            <dd class="mt-1 text-2xl font-bold text-green-600">RM {{ number_format($application->payment->amount, 2) }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs uppercase font-medium text-green-700 dark:text-green-400">Transaction ID</dt>
                            <dd class="mt-1 text-sm font-mono bg-white dark:bg-gray-800 p-2 rounded inline-block border border-green-200 dark:border-green-700">{{ $application->payment->transaction_id }}</dd>
                        </div>
                        @if($application->payment->payment_method)
                        <div>
                            <dt class="text-xs uppercase font-medium text-green-700 dark:text-green-400">Kaedah Pembayaran</dt>
                            <dd class="mt-1 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $application->payment->payment_method }}</dd>
                        </div>
                        @endif
                        @if($application->payment->gateway_reference)
                        <div>
                            <dt class="text-xs uppercase font-medium text-green-700 dark:text-green-400">Gateway Reference</dt>
                            <dd class="mt-1 text-sm font-mono bg-white dark:bg-gray-800 p-2 rounded inline-block border border-green-200 dark:border-green-700">{{ $application->payment->gateway_reference }}</dd>
                        </div>
                        @endif
                        @if($application->payment->notes)
                        <div class="col-span-2">
                            <dt class="text-xs uppercase font-medium text-green-700 dark:text-green-400">Nota</dt>
                            <dd class="mt-1 text-sm text-gray-700 dark:text-gray-300">{{ $application->payment->notes }}</dd>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <!-- Right Column: Actions -->
            <div class="space-y-6">
                <!-- Status Card -->
                <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6 border border-gray-200 dark:border-gray-700 sticky top-24">
                    <h3 class="text-lg font-bold mb-4">Review Action</h3>
                    
                    <div class="mb-6 flex justify-center">
                        <span class="px-4 py-2 rounded-full text-sm font-bold w-full text-center tracking-widest uppercase
                            {{ $application->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $application->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}
                            {{ $application->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                            {{ $application->status }}
                        </span>
                    </div>

                    @if($application->amount_requested)
                    <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-900 rounded border dark:border-gray-700 text-center">
                        <span class="block text-xs text-gray-500 uppercase">Requested Amount</span>
                        <span class="text-xl font-bold text-green-600">RM {{ number_format($application->amount_requested, 2) }}</span>
                    </div>
                    @endif

                    <form action="{{ route('admin.applications.update', $application) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Change Status</label>
                            <select name="status" class="block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
                                <option value="pending" {{ $application->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ $application->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ $application->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-2">Updating to Approved or Rejected will send an SMS to <strong>{{ $application->applicant_phone ?? 'the applicant' }}</strong>.</p>
                        </div>
                        <button type="submit" class="w-full justify-center inline-flex items-center px-4 py-3 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-bold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white transition ease-in-out duration-150 shadow">
                            Update Status
                        </button>
                    </form>

                    <!-- Payment Action Card -->
                    @if($application->status === 'approved' && !$application->payment)
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('admin.payments.create', ['application_id' => $application->id]) }}" class="w-full flex items-center justify-center px-4 py-3 bg-gradient-to-r from-green-500 to-emerald-600 border border-transparent rounded-md font-bold text-xs text-white uppercase tracking-widest hover:from-green-600 hover:to-emerald-700 transition ease-in-out duration-150 shadow-lg shadow-green-500/30">
                            Disburse Funds (Pay)
                        </a>
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
