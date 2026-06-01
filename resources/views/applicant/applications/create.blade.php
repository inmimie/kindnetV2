<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('New Application') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @if($errors->any())
                        <div class="mb-6 px-4 py-3 bg-red-50 border border-red-200 text-red-600 rounded">
                            <ul class="list-disc pl-5">
                                @foreach($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('applicant.applications.store') }}" method="POST" id="appForm" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Tab Navigation -->
                        <div class="flex flex-nowrap overflow-x-auto border-b border-gray-200 dark:border-gray-700 mb-6 scrollbar-hide">
                            <button type="button" id="btn-tab-1" class="whitespace-nowrap px-4 py-3 text-sm font-semibold text-indigo-600 border-b-2 border-indigo-600 dark:text-indigo-400 dark:border-indigo-400 focus:outline-none" onclick="switchTab(1)">
                                1. Applicant
                            </button>
                            <button type="button" id="btn-tab-2" class="whitespace-nowrap px-4 py-3 text-sm font-semibold text-gray-500 border-b-2 border-transparent hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 focus:outline-none" onclick="switchTab(2)">
                                2. Parent/Guardian
                            </button>
                            <button type="button" id="btn-tab-3" class="whitespace-nowrap px-4 py-3 text-sm font-semibold text-gray-500 border-b-2 border-transparent hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 focus:outline-none" onclick="switchTab(3)">
                                3. Education & Bank
                            </button>
                            <button type="button" id="btn-tab-4" class="whitespace-nowrap px-4 py-3 text-sm font-semibold text-gray-500 border-b-2 border-transparent hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 focus:outline-none" onclick="switchTab(4)">
                                4. Documents
                            </button>
                        </div>

                        <!-- TAB 1: Applicant Info -->
                        <div id="tab-1" class="block">
                            <h3 class="text-lg font-bold mb-4 text-indigo-900 dark:text-indigo-300">Applicant & Application Details</h3>
                            
                            <input type="hidden" name="charity_type_id" value="{{ request('charity_type_id') }}">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                                <div>
                                    <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Applicant Name *</label>
                                    <input type="text" name="applicant_name" value="{{ old('applicant_name', $latestApplication?->applicant_name ?? auth()->user()->name) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm" required>
                                </div>
                                <div>
                                    <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">IC Number *</label>
                                    <input type="text" id="applicant_ic_input" name="applicant_ic" value="{{ old('applicant_ic', $latestApplication?->applicant_ic) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm" placeholder="e.g. 900101-14-5555" maxlength="14" required>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-4">
                                <div>
                                    <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Date of Birth *</label>
                                    <input type="date" id="applicant_dob_input" name="applicant_dob" value="{{ old('applicant_dob', $latestApplication?->applicant_dob ? \Carbon\Carbon::parse($latestApplication->applicant_dob)->format('Y-m-d') : '') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm" required>
                                </div>
                                <div>
                                    <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Gender *</label>
                                    <select name="applicant_gender" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm" required>
                                        <option value="">Select</option>
                                        <option value="Lelaki" {{ old('applicant_gender', $latestApplication?->applicant_gender) == 'Lelaki' ? 'selected' : '' }}>Male</option>
                                        <option value="Perempuan" {{ old('applicant_gender', $latestApplication?->applicant_gender) == 'Perempuan' ? 'selected' : '' }}>Female</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Marital Status *</label>
                                    <select name="applicant_marital_status" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm" required>
                                        <option value="">Select</option>
                                        <option value="Bujang" {{ old('applicant_marital_status', $latestApplication?->applicant_marital_status) == 'Bujang' ? 'selected' : '' }}>Single</option>
                                        <option value="Berkahwin" {{ old('applicant_marital_status', $latestApplication?->applicant_marital_status) == 'Berkahwin' ? 'selected' : '' }}>Married</option>
                                        <option value="Lain-lain" {{ old('applicant_marital_status', $latestApplication?->applicant_marital_status) == 'Lain-lain' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Full Address *</label>
                                <textarea name="applicant_address" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm" required>{{ old('applicant_address', $latestApplication?->applicant_address) }}</textarea>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-4">
                                <div>
                                    <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Mobile Phone No. *</label>
                                    <input type="text" name="applicant_phone" value="{{ old('applicant_phone', $latestApplication?->applicant_phone ?? auth()->user()->phone_number) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm" required>
                                </div>
                                <div>
                                    <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Email *</label>
                                    <input type="email" name="applicant_email" value="{{ old('applicant_email', $latestApplication?->applicant_email ?? auth()->user()->email) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm" required>
                                </div>
                                <div>
                                    <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Occupation *</label>
                                    <input type="text" name="applicant_occupation" value="{{ old('applicant_occupation', $latestApplication?->applicant_occupation ?? 'Student') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm" required>
                                </div>
                            </div>
                            
                            

                            <div class="mt-6 flex items-center justify-end">
                                <button type="button" onclick="switchTab(2)" class="px-6 py-2 bg-gray-800 text-white font-semibold rounded hover:bg-gray-700 transition shadow">Next (Parents) &rarr;</button>
                            </div>
                        </div>

                        <!-- TAB 2: Parent/Guardian Info -->
                        <div id="tab-2" class="hidden">
                            <h3 class="text-lg font-bold mb-4 text-indigo-900 dark:text-indigo-300">Parent / Guardian Information</h3>

                            <div class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-xl mb-6 border dark:border-gray-700">
                                <h4 class="font-bold text-gray-800 dark:text-gray-200 mb-3 border-b pb-2">Father's Details</h4>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div>
                                        <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Father's Name *</label>
                                        <input type="text" name="father_name" value="{{ old('father_name', $latestApplication?->father_name) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm" required>
                                    </div>
                                    <div>
                                        <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Father's Occupation *</label>
                                        <input type="text" name="father_occupation" value="{{ old('father_occupation', $latestApplication?->father_occupation) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm" required>
                                    </div>
                                    <div>
                                        <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Father's Income (RM) *</label>
                                        <input type="number" step="0.01" id="father_income_input" name="father_income" value="{{ old('father_income', $latestApplication?->father_income) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm" required>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-xl mb-6 border dark:border-gray-700">
                                <h4 class="font-bold text-gray-800 dark:text-gray-200 mb-3 border-b pb-2">Mother's Details</h4>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div>
                                        <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Mother's Name *</label>
                                        <input type="text" name="mother_name" value="{{ old('mother_name', $latestApplication?->mother_name) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm" required>
                                    </div>
                                    <div>
                                        <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Mother's Occupation *</label>
                                        <input type="text" name="mother_occupation" value="{{ old('mother_occupation', $latestApplication?->mother_occupation) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm" required>
                                    </div>
                                    <div>
                                        <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Mother's Income (RM) *</label>
                                        <input type="number" step="0.01" id="mother_income_input" name="mother_income" value="{{ old('mother_income', $latestApplication?->mother_income) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm" required>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-orange-50 dark:bg-orange-900/20 p-4 rounded-xl mb-6 border border-orange-200 dark:border-orange-800 flex justify-between items-center shadow-inner">
                                <span class="font-bold text-orange-900 dark:text-orange-200 uppercase text-sm tracking-wider">Total Household Income</span>
                                <span id="display_total_income" class="text-2xl font-black text-orange-600 dark:text-orange-400">RM 0.00</span>
                            </div>

                            <div class="bg-indigo-50 dark:bg-indigo-900/30 p-4 rounded-xl border border-indigo-100 dark:border-indigo-800 mb-6">
                                <h4 class="font-bold text-indigo-900 dark:text-indigo-200 mb-3">Dependents & Aid Information</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block font-medium text-sm text-indigo-800 dark:text-indigo-300">Number of Family Dependents *</label>
                                        <input type="number" name="total_dependents" value="{{ old('total_dependents', $latestApplication?->total_dependents) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm" required>
                                    </div>
                                    <div>
                                        <label class="block font-medium text-sm text-indigo-800 dark:text-indigo-300">Requested Amount (RM) (Optional)</label>
                                        <input type="number" step="0.01" name="amount_requested" value="{{ old('amount_requested', $latestApplication?->amount_requested) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm placeholder-gray-400" placeholder="Leave blank if not relevant">
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6 flex items-center justify-between">
                                <button type="button" onclick="switchTab(1)" class="px-6 py-2 bg-white text-gray-700 border border-gray-300 font-semibold rounded hover:bg-gray-50 transition shadow-sm">&larr; Back (Applicant)</button>
                                <button type="button" onclick="switchTab(3)" class="px-6 py-2 bg-gray-800 text-white font-semibold rounded hover:bg-gray-700 transition shadow">Next (Education) &rarr;</button>
                            </div>
                        </div>

                        <!-- TAB 3: Education & Bank Info -->
                        <div id="tab-3" class="hidden">
                            <h3 class="text-lg font-bold mb-4 text-indigo-900 dark:text-indigo-300">Education & Banking Information</h3>

                            <!-- Education -->
                            <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-xl mb-6 border border-blue-200 dark:border-blue-800">
                                <h4 class="font-bold text-blue-900 dark:text-blue-200 mb-3 border-b border-blue-200 dark:border-blue-800 pb-2">Maklumat Pendidikan (Education)</h4>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                                    <div class="md:col-span-2">
                                        <label class="block font-medium text-sm text-blue-800 dark:text-blue-300">University Name (Nama Universiti) *</label>
                                        <input type="text" name="university_name" value="{{ old('university_name', $latestApplication?->university_name) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm" required>
                                    </div>
                                    <div>
                                        <label class="block font-medium text-sm text-blue-800 dark:text-blue-300">Course Name (Nama Kursus) *</label>
                                        <input type="text" name="course_name" value="{{ old('course_name', $latestApplication?->course_name) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm" required>
                                    </div>
                                    <div>
                                        <label class="block font-medium text-sm text-blue-800 dark:text-blue-300">Level of Study (Tahap Pengajian) *</label>
                                        <select name="study_level" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm" required>
                                            <option value="">Select Level</option>
                                            <option value="Sijil" {{ old('study_level', $latestApplication?->study_level) == 'Sijil' ? 'selected' : '' }}>Sijil</option>
                                            <option value="Diploma" {{ old('study_level', $latestApplication?->study_level) == 'Diploma' ? 'selected' : '' }}>Diploma</option>
                                            <option value="Ijazah Sarjana Muda" {{ old('study_level', $latestApplication?->study_level) == 'Ijazah Sarjana Muda' ? 'selected' : '' }}>Ijazah Sarjana Muda (Degree)</option>
                                            <option value="Sarjana" {{ old('study_level', $latestApplication?->study_level) == 'Sarjana' ? 'selected' : '' }}>Sarjana (Master)</option>
                                            <option value="PhD" {{ old('study_level', $latestApplication?->study_level) == 'PhD' ? 'selected' : '' }}>PhD</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block font-medium text-sm text-blue-800 dark:text-blue-300">Start Year (Tahun Mula) *</label>
                                        <input type="number" name="start_year" value="{{ old('start_year', $latestApplication?->start_year) }}" placeholder="YYYY" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm" required>
                                    </div>
                                    <div>
                                        <label class="block font-medium text-sm text-blue-800 dark:text-blue-300">End Year (Tahun Tamat) *</label>
                                        <input type="number" name="end_year" value="{{ old('end_year', $latestApplication?->end_year) }}" placeholder="YYYY" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Bank -->
                            <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-xl mb-6 border border-green-200 dark:border-green-800">
                                <h4 class="font-bold text-green-900 dark:text-green-200 mb-3 border-b border-green-200 dark:border-green-800 pb-2">Maklumat Perbankan (Banking)</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block font-medium text-sm text-green-800 dark:text-green-300">Bank Name (Jenis Bank) *</label>
                                        <select name="bank_name" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm" required>
                                            <option value="">Select Bank</option>
                                            <option value="Maybank" {{ old('bank_name', $latestApplication?->bank_name) == 'Maybank' ? 'selected' : '' }}>Maybank</option>
                                            <option value="CIMB Bank" {{ old('bank_name', $latestApplication?->bank_name) == 'CIMB Bank' ? 'selected' : '' }}>CIMB Bank</option>
                                            <option value="Public Bank" {{ old('bank_name', $latestApplication?->bank_name) == 'Public Bank' ? 'selected' : '' }}>Public Bank</option>
                                            <option value="RHB Bank" {{ old('bank_name', $latestApplication?->bank_name) == 'RHB Bank' ? 'selected' : '' }}>RHB Bank</option>
                                            <option value="Hong Leong Bank" {{ old('bank_name', $latestApplication?->bank_name) == 'Hong Leong Bank' ? 'selected' : '' }}>Hong Leong Bank</option>
                                            <option value="AmBank" {{ old('bank_name', $latestApplication?->bank_name) == 'AmBank' ? 'selected' : '' }}>AmBank</option>
                                            <option value="Bank Islam" {{ old('bank_name', $latestApplication?->bank_name) == 'Bank Islam' ? 'selected' : '' }}>Bank Islam</option>
                                            <option value="Bank Rakyat" {{ old('bank_name', $latestApplication?->bank_name) == 'Bank Rakyat' ? 'selected' : '' }}>Bank Rakyat</option>
                                            <option value="BSN" {{ old('bank_name', $latestApplication?->bank_name) == 'BSN' ? 'selected' : '' }}>BSN (Bank Simpanan Nasional)</option>
                                            <option value="Bank Muamalat" {{ old('bank_name', $latestApplication?->bank_name) == 'Bank Muamalat' ? 'selected' : '' }}>Bank Muamalat</option>
                                            <option value="Agrobank" {{ old('bank_name', $latestApplication?->bank_name) == 'Agrobank' ? 'selected' : '' }}>Agrobank</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block font-medium text-sm text-green-800 dark:text-green-300">Account Number (No Akaun) *</label>
                                        <input type="text" name="account_number" value="{{ old('account_number', $latestApplication?->account_number) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm" required>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6 flex items-center justify-between">
                                <button type="button" onclick="switchTab(2)" class="px-6 py-2 bg-white text-gray-700 border border-gray-300 font-semibold rounded hover:bg-gray-50 transition shadow-sm">&larr; Back (Parents)</button>
                                <button type="button" onclick="switchTab(4)" class="px-6 py-2 bg-gray-800 text-white font-semibold rounded hover:bg-gray-700 transition shadow">Next (Documents) &rarr;</button>
                            </div>
                        </div>

                        <!-- TAB 4: Documents -->
                        <div id="tab-4" class="hidden">
                            <h3 class="text-lg font-bold mb-4 text-indigo-900 dark:text-indigo-300">Dokumen Sokongan (Supporting Documents)</h3>

                            <div class="bg-yellow-50 dark:bg-yellow-900/20 p-4 rounded-xl mb-6 border border-yellow-200 dark:border-yellow-800">
                                <p class="text-sm text-yellow-800 dark:text-yellow-200 mb-4">Sila muat naik salinan dokumen berikut. Format yang dibenarkan: PDF, JPG, PNG (Maksimum 5MB setiap fail).</p>
                                
                                <div class="space-y-4">
                                    <div>
                                        <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">1. Salinan Kad Pengenalan Pelajar (Applicant IC) *</label>
                                        <input type="file" name="doc_student_ic" accept=".pdf,.jpg,.jpeg,.png" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:text-gray-400 dark:file:bg-indigo-900/50 dark:file:text-indigo-300" {{ !$latestApplication?->doc_student_ic ? 'required' : '' }}>
                                        @if($latestApplication?->doc_student_ic)
                                            <p class="text-xs text-green-600 mt-1 flex items-center">
                                                <svg class="w-4 h-4 mr-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                Previously uploaded. 
                                                <a href="{{ asset('storage/' . $latestApplication->doc_student_ic) }}" target="_blank" class="ml-1 text-indigo-600 dark:text-indigo-400 underline hover:text-indigo-850">View file</a>
                                            </p>
                                        @endif
                                    </div>
                                    <div>
                                        <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">2. Salinan Sijil Lahir Pelajar (Applicant Birth Cert) *</label>
                                        <input type="file" name="doc_student_birth_cert" accept=".pdf,.jpg,.jpeg,.png" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:text-gray-400 dark:file:bg-indigo-900/50 dark:file:text-indigo-300" {{ !$latestApplication?->doc_student_birth_cert ? 'required' : '' }}>
                                        @if($latestApplication?->doc_student_birth_cert)
                                            <p class="text-xs text-green-600 mt-1 flex items-center">
                                                <svg class="w-4 h-4 mr-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                Previously uploaded. 
                                                <a href="{{ asset('storage/' . $latestApplication->doc_student_birth_cert) }}" target="_blank" class="ml-1 text-indigo-600 dark:text-indigo-400 underline hover:text-indigo-850">View file</a>
                                            </p>
                                        @endif
                                    </div>
                                    <div>
                                        <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">3. Salinan Kad Pengenalan Ibu (Mother's IC) *</label>
                                        <input type="file" name="doc_mother_ic" accept=".pdf,.jpg,.jpeg,.png" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:text-gray-400 dark:file:bg-indigo-900/50 dark:file:text-indigo-300" {{ !$latestApplication?->doc_mother_ic ? 'required' : '' }}>
                                        @if($latestApplication?->doc_mother_ic)
                                            <p class="text-xs text-green-600 mt-1 flex items-center">
                                                <svg class="w-4 h-4 mr-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                Previously uploaded. 
                                                <a href="{{ asset('storage/' . $latestApplication->doc_mother_ic) }}" target="_blank" class="ml-1 text-indigo-600 dark:text-indigo-400 underline hover:text-indigo-850">View file</a>
                                            </p>
                                        @endif
                                    </div>
                                    <div>
                                        <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">4. Salinan Kad Pengenalan Bapa (Father's IC) *</label>
                                        <input type="file" name="doc_father_ic" accept=".pdf,.jpg,.jpeg,.png" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:text-gray-400 dark:file:bg-indigo-900/50 dark:file:text-indigo-300" {{ !$latestApplication?->doc_father_ic ? 'required' : '' }}>
                                        @if($latestApplication?->doc_father_ic)
                                            <p class="text-xs text-green-600 mt-1 flex items-center">
                                                <svg class="w-4 h-4 mr-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                Previously uploaded. 
                                                <a href="{{ asset('storage/' . $latestApplication->doc_father_ic) }}" target="_blank" class="ml-1 text-indigo-600 dark:text-indigo-400 underline hover:text-indigo-850">View file</a>
                                            </p>
                                        @endif
                                    </div>
                                    <div>
                                        <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">5. Salinan Surat Tawaran IPT (IPT Offer Letter) *</label>
                                        <input type="file" name="doc_offer_letter" accept=".pdf,.jpg,.jpeg,.png" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:text-gray-400 dark:file:bg-indigo-900/50 dark:file:text-indigo-300" {{ !$latestApplication?->doc_offer_letter ? 'required' : '' }}>
                                        @if($latestApplication?->doc_offer_letter)
                                            <p class="text-xs text-green-600 mt-1 flex items-center">
                                                <svg class="w-4 h-4 mr-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                Previously uploaded. 
                                                <a href="{{ asset('storage/' . $latestApplication->doc_offer_letter) }}" target="_blank" class="ml-1 text-indigo-600 dark:text-indigo-400 underline hover:text-indigo-850">View file</a>
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6 flex items-center justify-between">
                                <button type="button" onclick="switchTab(3)" class="px-6 py-2 bg-white text-gray-700 border border-gray-300 font-semibold rounded hover:bg-gray-50 transition shadow-sm">&larr; Back (Education & Bank)</button>
                                
                                <div class="flex items-center">
                                    <a href="{{ route('applicant.dashboard') }}" class="mr-4 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 transition">Cancel</a>
                                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white font-bold rounded hover:bg-indigo-700 transition shadow-md">Submit Application</button>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function switchTab(tabIndex) {
            const tabs = [1, 2, 3, 4];
            
            // Validation logic before moving forward
            if (tabIndex > 1) {
                // Check current tab to see if moving forward
                let currentTab = 1;
                if (!document.getElementById('tab-1').classList.contains('hidden')) currentTab = 1;
                else if (!document.getElementById('tab-2').classList.contains('hidden')) currentTab = 2;
                else if (!document.getElementById('tab-3').classList.contains('hidden')) currentTab = 3;
                else if (!document.getElementById('tab-4').classList.contains('hidden')) currentTab = 4;

                if (tabIndex > currentTab) {
                    // Check validity of current tab
                    const currentTabEl = document.getElementById(`tab-${currentTab}`);
                    const requiredFields = currentTabEl.querySelectorAll('[required]');
                    let allValid = true;
                    requiredFields.forEach(field => {
                        if (!field.value) {
                            allValid = false;
                            field.classList.add('border-red-500');
                        } else {
                            field.classList.remove('border-red-500');
                        }
                    });
                    
                    if (!allValid) {
                        // Force HTML5 validation UI
                        const submitBtn = document.createElement('input');
                        submitBtn.type = 'submit';
                        submitBtn.style.display = 'none';
                        document.getElementById('appForm').appendChild(submitBtn);
                        submitBtn.click();
                        submitBtn.remove();
                        return; 
                    }
                }
            }

            // Hide all tabs & reset buttons
            tabs.forEach(t => {
                const tabEl = document.getElementById(`tab-${t}`);
                if(tabEl) tabEl.classList.add('hidden');
                
                const btn = document.getElementById(`btn-tab-${t}`);
                if(btn) {
                    btn.classList.add('text-gray-500', 'border-transparent');
                    btn.classList.remove('text-indigo-600', 'border-indigo-600', 'dark:text-indigo-400', 'dark:border-indigo-400');
                }
            });

            // Show selected tab
            const activeTab = document.getElementById(`tab-${tabIndex}`);
            if(activeTab) activeTab.classList.remove('hidden');
            
            const activeBtn = document.getElementById(`btn-tab-${tabIndex}`);
            if(activeBtn) {
                activeBtn.classList.add('text-indigo-600', 'border-indigo-600', 'dark:text-indigo-400', 'dark:border-indigo-400');
                activeBtn.classList.remove('text-gray-500', 'border-transparent');
            }
            
            window.scrollTo(0, 0);
        }
        
        // Show correct tab on backend validation failure
        @if($errors->any())
            @if($errors->has('doc_student_ic') || $errors->has('doc_student_birth_cert') || $errors->has('doc_mother_ic') || $errors->has('doc_father_ic') || $errors->has('doc_offer_letter'))
                switchTab(4);
            @elseif($errors->has('course_name') || $errors->has('bank_name') || $errors->has('account_number'))
                switchTab(3);
            @elseif($errors->has('father_name') || $errors->has('mother_name') || $errors->has('total_dependents'))
                switchTab(2);
            @endif
        @endif

        // Auto-extract Date of Birth & Format Malaysian IC
        document.getElementById('applicant_ic_input').addEventListener('input', function(e) {
            let cursorPosition = e.target.selectionStart;
            let ic = e.target.value.replace(/[^0-9]/g, ''); // Extract only numbers
            
            // Auto-format IC: YYMMDD-PB-###G
            let formatted = ic;
            if (ic.length > 6 && ic.length <= 8) {
                formatted = ic.substr(0, 6) + '-' + ic.substr(6);
            } else if (ic.length > 8) {
                formatted = ic.substr(0, 6) + '-' + ic.substr(6, 2) + '-' + ic.substr(8, 4);
            }
            
            // Only update value if different to prevent cursor jumping to end
            if(e.target.value !== formatted && cursorPosition === e.target.value.length) {
                e.target.value = formatted;
            } else if (e.target.value !== formatted) {
                e.target.value = formatted;
            }

            if (ic.length >= 6) {
                let yy = parseInt(ic.substring(0, 2));
                let mm = ic.substring(2, 4);
                let dd = ic.substring(4, 6);

                // Auto-determine century (if year > current 2-digit year, assume 1900s, else 2000s)
                let currentYear2Digit = parseInt(new Date().getFullYear().toString().slice(-2));
                let fullYear = yy > currentYear2Digit ? 1900 + yy : 2000 + yy;

                let dobStr = `${fullYear}-${mm}-${dd}`;
                
                // Only set if valid date
                if (!isNaN(new Date(dobStr).getTime())) {
                    document.getElementById('applicant_dob_input').value = dobStr;
                }
            }
        });

        // Auto-calculate Total Income
        const fIncInput = document.getElementById('father_income_input');
        const mIncInput = document.getElementById('mother_income_input');
        const displayTotal = document.getElementById('display_total_income');

        function updateTotal() {
            let f = parseFloat(fIncInput.value) || 0;
            let m = parseFloat(mIncInput.value) || 0;
            let total = f + m;
            displayTotal.innerText = 'RM ' + total.toLocaleString('en-MY', {minimumFractionDigits: 2, maximumFractionDigits:2});
        }

        fIncInput.addEventListener('input', updateTotal);
        mIncInput.addEventListener('input', updateTotal);
        // Run once on load just in case there's old() data
        updateTotal();

    </script>
</x-app-layout>
