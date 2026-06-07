<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight flex justify-between items-center">
            <span>Application Details #{{ $application->id }}</span>
            <span class="text-sm px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full font-bold">Aid: {{ $application->charityType->name }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                
                <div class="flex justify-between items-start mb-6 border-b pb-4 dark:border-gray-700">
                    <div>
                        <h3 class="text-2xl font-bold">{{ $application->user->name }}'s Application</h3>
                        <p class="text-gray-500 text-sm mt-1">Submitted on {{ $application->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                    <div>
                        <span class="whitespace-nowrap px-4 py-2 rounded-full text-sm font-bold uppercase tracking-wider
                            {{ $application->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $application->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}
                            {{ $application->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                            {{ $application->status === 'pending' ? 'In Progress' : $application->status }}
                        </span>
                    </div>
                </div>

                @if($application->amount_requested)
                <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg mb-8 inline-block">
                    <h4 class="font-semibold text-gray-400 text-xs uppercase mb-1">Amount Requested</h4>
                    <p class="font-bold text-green-600">RM {{ number_format($application->amount_requested, 2) }}</p>
                </div>
                @endif

                <!-- Applicant Details -->
                <h3 class="text-lg font-bold text-indigo-700 dark:text-indigo-400 mb-4 border-b border-gray-100 dark:border-gray-700 pb-2">Applicant Information</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-y-4 gap-x-6 mb-8">
                    <div>
                        <span class="block text-xs uppercase text-gray-500">Applicant Name</span>
                        <span class="font-medium">{{ $application->applicant_name }}</span>
                    </div>
                    <div>
                        <span class="block text-xs uppercase text-gray-500">IC Number</span>
                        <span class="font-medium">{{ $application->applicant_ic }}</span>
                    </div>
                    <div>
                        <span class="block text-xs uppercase text-gray-500">Date of Birth</span>
                        <span class="font-medium">{{ $application->applicant_dob }}</span>
                    </div>
                    <div>
                        <span class="block text-xs uppercase text-gray-500">Gender</span>
                        <span class="font-medium">{{ $application->applicant_gender === 'Perempuan' ? 'Female' : ($application->applicant_gender === 'Lelaki' ? 'Male' : $application->applicant_gender) }}</span>
                    </div>
                    <div>
                        <span class="block text-xs uppercase text-gray-500">Marital Status</span>
                        <span class="font-medium">{{ $application->applicant_marital_status }}</span>
                    </div>
                    <div>
                        <span class="block text-xs uppercase text-gray-500">Occupation</span>
                        <span class="font-medium">{{ $application->applicant_occupation }}</span>
                    </div>
                    <div class="col-span-1 md:col-span-2">
                        <span class="block text-xs uppercase text-gray-500">Full Address</span>
                        <span class="font-medium">{{ $application->applicant_address }}</span>
                    </div>
                    <div>
                        <span class="block text-xs uppercase text-gray-500">Mobile / Email</span>
                        <span class="font-medium block">{{ $application->applicant_phone }}</span>
                        <span class="font-medium text-sm text-gray-500">{{ $application->applicant_email }}</span>
                    </div>
                </div>

                <!-- Family Details -->
                <h3 class="text-lg font-bold text-indigo-700 dark:text-indigo-400 mb-4 border-b border-gray-100 dark:border-gray-700 pb-2">Parent / Guardian Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="border border-gray-200 dark:border-gray-600 rounded p-4 bg-gray-50 dark:bg-gray-800/50">
                        <h4 class="font-bold mb-3">Father's Details</h4>
                        <div class="space-y-2 text-sm">
                            <p><span class="text-gray-500 block text-xs">Name</span> <span class="font-medium">{{ $application->father_name }}</span></p>
                            <p><span class="text-gray-500 block text-xs">Occupation</span> <span class="font-medium">{{ $application->father_occupation }}</span></p>
                            <p><span class="text-gray-500 block text-xs">Income</span> <span class="font-medium">RM {{ number_format($application->father_income, 2) }}</span></p>
                        </div>
                    </div>
                    <div class="border border-gray-200 dark:border-gray-600 rounded p-4 bg-gray-50 dark:bg-gray-800/50">
                        <h4 class="font-bold mb-3">Mother's Details</h4>
                        <div class="space-y-2 text-sm">
                            <p><span class="text-gray-500 block text-xs">Name</span> <span class="font-medium">{{ $application->mother_name }}</span></p>
                            <p><span class="text-gray-500 block text-xs">Occupation</span> <span class="font-medium">{{ $application->mother_occupation }}</span></p>
                            <p><span class="text-gray-500 block text-xs">Income</span> <span class="font-medium">RM {{ number_format($application->mother_income, 2) }}</span></p>
                        </div>
                    </div>
                </div>

                <!-- Education & Banking Details -->
                <h3 class="text-lg font-bold text-indigo-700 dark:text-indigo-400 mb-4 border-b border-gray-100 dark:border-gray-700 pb-2">Education & Banking Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="border border-blue-200 dark:border-blue-800 rounded p-4 bg-blue-50 dark:bg-blue-900/20">
                        <h4 class="font-bold mb-3 text-blue-900 dark:text-blue-200">Education Details</h4>
                        <div class="space-y-2 text-sm">
                            <p><span class="text-gray-500 block text-xs">University</span> <span class="font-medium">{{ $application->university_name }}</span></p>
                            <p><span class="text-gray-500 block text-xs">Course</span> <span class="font-medium">{{ $application->course_name }}</span></p>
                            <p><span class="text-gray-500 block text-xs">Level</span> <span class="font-medium">{{ $application->study_level }}</span></p>
                            <p><span class="text-gray-500 block text-xs">Duration</span> <span class="font-medium">{{ $application->start_year }} - {{ $application->end_year }}</span></p>
                        </div>
                    </div>
                    <div class="border border-green-200 dark:border-green-800 rounded p-4 bg-green-50 dark:bg-green-900/20">
                        <h4 class="font-bold mb-3 text-green-900 dark:text-green-200">Banking Details</h4>
                        <div class="space-y-2 text-sm">
                            <p><span class="text-gray-500 block text-xs">Bank Name</span> <span class="font-medium">{{ $application->bank_name }}</span></p>
                            <p><span class="text-gray-500 block text-xs">Account Number</span> <span class="font-medium">{{ $application->account_number }}</span></p>
                        </div>
                    </div>
                </div>

                <div class="bg-indigo-50 dark:bg-indigo-900/30 p-4 rounded-lg border border-indigo-100 dark:border-indigo-800 mb-8 flex justify-around">

                <!-- Documents Details -->
                <h3 class="text-lg font-bold text-indigo-700 dark:text-indigo-400 mb-4 border-b border-gray-100 dark:border-gray-700 pb-2">Dokumen Sokongan (Supporting Documents)</h3>
                <div class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-6 mb-8 border border-gray-200 dark:border-gray-700">
                    <ul class="space-y-3">
                        <li class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">1. Salinan Kad Pengenalan Pelajar</span>
                            @if($application->doc_student_ic)
                                <a href="{{ Storage::url($application->doc_student_ic) }}" target="_blank" class="text-sm text-indigo-600 hover:text-indigo-800 font-bold underline">Muat Turun / Lihat</a>
                            @else
                                <span class="text-sm text-red-500 italic">Tiada Dokumen</span>
                            @endif
                        </li>
                        <li class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">2. Salinan Sijil Lahir Pelajar</span>
                            @if($application->doc_student_birth_cert)
                                <a href="{{ Storage::url($application->doc_student_birth_cert) }}" target="_blank" class="text-sm text-indigo-600 hover:text-indigo-800 font-bold underline">Muat Turun / Lihat</a>
                            @else
                                <span class="text-sm text-red-500 italic">Tiada Dokumen</span>
                            @endif
                        </li>
                        <li class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">3. Salinan Kad Pengenalan Ibu</span>
                            @if($application->doc_mother_ic)
                                <a href="{{ Storage::url($application->doc_mother_ic) }}" target="_blank" class="text-sm text-indigo-600 hover:text-indigo-800 font-bold underline">Muat Turun / Lihat</a>
                            @else
                                <span class="text-sm text-red-500 italic">Tiada Dokumen</span>
                            @endif
                        </li>
                        <li class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">4. Salinan Kad Pengenalan Bapa</span>
                            @if($application->doc_father_ic)
                                <a href="{{ Storage::url($application->doc_father_ic) }}" target="_blank" class="text-sm text-indigo-600 hover:text-indigo-800 font-bold underline">Muat Turun / Lihat</a>
                            @else
                                <span class="text-sm text-red-500 italic">Tiada Dokumen</span>
                            @endif
                        </li>
                        <li class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">5. Salinan Surat Tawaran IPT</span>
                            @if($application->doc_offer_letter)
                                <a href="{{ Storage::url($application->doc_offer_letter) }}" target="_blank" class="text-sm text-indigo-600 hover:text-indigo-800 font-bold underline">Muat Turun / Lihat</a>
                            @else
                                <span class="text-sm text-red-500 italic">Tiada Dokumen</span>
                            @endif
                        </li>
                    </ul>
                </div>

                <div class="bg-indigo-50 dark:bg-indigo-900/30 p-4 rounded-lg border border-indigo-100 dark:border-indigo-800 mb-8 flex justify-around">
                    <div class="text-center">
                        <span class="block text-xs uppercase text-indigo-500 font-bold">Dependents</span>
                        <span class="font-bold text-2xl text-indigo-900 dark:text-indigo-200">{{ $application->total_dependents }}</span>
                    </div>
                    <div class="text-center">
                        <span class="block text-xs uppercase text-indigo-500 font-bold">Total Income</span>
                        <span class="font-bold text-2xl text-indigo-900 dark:text-indigo-200">RM {{ number_format($application->total_income, 2) }}</span>
                    </div>
                </div>

                @if($application->payment)
                <div class="bg-green-50 dark:bg-green-900/30 p-4 rounded-lg border border-green-200 dark:border-green-800 border-l-4 border-l-green-500 mb-6">
                    <h4 class="font-semibold text-green-800 dark:text-green-300 mb-2">Payment Information (Funds Disbursed)</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="block text-xs text-green-600 dark:text-green-400">Amount Paid</span>
                            <span class="font-bold text-lg">RM {{ number_format($application->payment->amount, 2) }}</span>
                        </div>
                        <div>
                            <span class="block text-xs text-green-600 dark:text-green-400">Transaction ID</span>
                            <span class="font-mono text-sm bg-white dark:bg-gray-800 px-2 py-1 rounded inline-block mt-1">{{ $application->payment->transaction_id }}</span>
                        </div>
                    </div>
                </div>
                @endif

                <div class="pt-4 border-t border-gray-200 dark:border-gray-700 flex justify-end space-x-4">
                    @if($application->status === 'pending')
                        <a href="{{ route('applicant.applications.edit', $application) }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition shadow">Update Application</a>
                    @endif
                    <a href="{{ route('applicant.applications.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded hover:bg-gray-300 dark:hover:bg-gray-600 transition">Back</a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
