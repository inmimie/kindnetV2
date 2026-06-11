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

                            @php
                                $defaultAddress = '';
                                if (auth()->user()->address_line1) {
                                    $lines = array_filter([
                                        auth()->user()->address_line1,
                                        auth()->user()->address_line2,
                                        auth()->user()->address_line3,
                                        auth()->user()->postcode . ' ' . auth()->user()->city,
                                        auth()->user()->state_nation
                                    ]);
                                    $defaultAddress = implode(", ", $lines);
                                }
                            @endphp

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                                <div>
                                    <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Applicant Name *</label>
                                    <input type="text" name="applicant_name" value="{{ old('applicant_name', $latestApplication?->applicant_name ?? auth()->user()->name) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-800 cursor-not-allowed shadow-sm" readonly required>
                                </div>
                                <div>
                                    <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">IC Number *</label>
                                    <input type="text" id="applicant_ic_input" name="applicant_ic" value="{{ old('applicant_ic', $latestApplication?->applicant_ic ?? auth()->user()->ic_number) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-800 cursor-not-allowed shadow-sm" placeholder="e.g. 900101-14-5555" maxlength="14" readonly required>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-4">
                                <div>
                                    <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Date of Birth *</label>
                                    <input type="date" id="applicant_dob_input" name="applicant_dob" value="{{ old('applicant_dob', $latestApplication?->applicant_dob ? \Carbon\Carbon::parse($latestApplication->applicant_dob)->format('Y-m-d') : (auth()->user()->date_of_birth ? \Carbon\Carbon::parse(auth()->user()->date_of_birth)->format('Y-m-d') : '')) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-800 cursor-not-allowed shadow-sm" onkeydown="return false;" onfocus="this.blur();" readonly required>
                                </div>
                                <div>
                                    <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Gender *</label>
                                    <select class="appearance-none bg-none mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-800 cursor-not-allowed shadow-sm" disabled required>
                                        <option value="">Select</option>
                                        <option value="Lelaki" {{ old('applicant_gender', $latestApplication?->applicant_gender ?? auth()->user()->gender) == 'Lelaki' ? 'selected' : '' }}>Male</option>
                                        <option value="Perempuan" {{ old('applicant_gender', $latestApplication?->applicant_gender ?? auth()->user()->gender) == 'Perempuan' ? 'selected' : '' }}>Female</option>
                                    </select>
                                    <input type="hidden" name="applicant_gender" value="{{ old('applicant_gender', $latestApplication?->applicant_gender ?? auth()->user()->gender) }}">
                                </div>
                                <div>
                                    <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Marital Status *</label>
                                    <select class="appearance-none bg-none mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-800 cursor-not-allowed shadow-sm" disabled required>
                                        <option value="">Select</option>
                                        <option value="Single" {{ old('applicant_marital_status', $latestApplication?->applicant_marital_status ?? auth()->user()->marital_status) == 'Single' ? 'selected' : '' }}>Single</option>
                                        <option value="Married" {{ old('applicant_marital_status', $latestApplication?->applicant_marital_status ?? auth()->user()->marital_status) == 'Married' ? 'selected' : '' }}>Married</option>
                                        <option value="Divorced" {{ old('applicant_marital_status', $latestApplication?->applicant_marital_status ?? auth()->user()->marital_status) == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                                        <option value="Widowed" {{ old('applicant_marital_status', $latestApplication?->applicant_marital_status ?? auth()->user()->marital_status) == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                        <option value="Other" {{ old('applicant_marital_status', $latestApplication?->applicant_marital_status ?? auth()->user()->marital_status) == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    <input type="hidden" name="applicant_marital_status" value="{{ old('applicant_marital_status', $latestApplication?->applicant_marital_status ?? auth()->user()->marital_status) }}">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Full Address *</label>
                                <textarea name="applicant_address" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm" required>{{ old('applicant_address', $latestApplication?->applicant_address ?? $defaultAddress) }}</textarea>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-4">
                                <div>
                                    <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Mobile Phone No. *</label>
                                    <input type="text" name="applicant_phone" value="{{ old('applicant_phone', $latestApplication?->applicant_phone ?? auth()->user()->phone_number) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm" required>
                                </div>
                                <div>
                                    <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Email *</label>
                                    <input type="email" name="applicant_email" value="{{ old('applicant_email', $latestApplication?->applicant_email ?? auth()->user()->email) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-800 cursor-not-allowed shadow-sm" readonly required>
                                </div>
                                <div>
                                    <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Occupation *</label>
                                    <select id="applicant_occupation_select" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm" required>
                                        <option value="Student">Student</option>
                                        <option value="Government Servant">Government Servant</option>
                                        <option value="Private Sector Employee">Private Sector Employee</option>
                                        <option value="Self-employed">Self-employed</option>
                                        <option value="Businessman">Businessman</option>
                                        <option value="Unemployed">Unemployed</option>
                                        <option value="Retired">Retired</option>
                                        <option value="Housewife">Housewife</option>
                                        <option value="Teacher">Teacher</option>
                                        <option value="Lecturer">Lecturer</option>
                                        <option value="Nurse">Nurse</option>
                                        <option value="Doctor">Doctor</option>
                                        <option value="Accountant">Accountant</option>
                                        <option value="Engineer">Engineer</option>
                                        <option value="Clerk">Clerk</option>
                                        <option value="Driver">Driver</option>
                                        <option value="General Worker">General Worker</option>
                                        <option value="Factory Worker">Factory Worker</option>
                                        <option value="Farmer">Farmer</option>
                                        <option value="Fisherman">Fisherman</option>
                                        <option value="Part-time">Part-time</option>
                                        <option value="Others">Others (Please specify)</option>
                                    </select>
                                    <div id="applicant_occupation_other_container" class="mt-2 hidden">
                                        <label class="block font-medium text-xs text-gray-500 dark:text-gray-400">Please specify occupation *</label>
                                        <input type="text" id="applicant_occupation_other" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm">
                                    </div>
                                    <input type="hidden" name="applicant_occupation" id="applicant_occupation" value="{{ old('applicant_occupation', $latestApplication?->applicant_occupation ?? 'Student') }}">
                                </div>
                            </div>
                            
                            

                            <div class="mt-6 flex items-center justify-end">
                                <button type="button" onclick="switchTab(2)" class="px-6 py-2 bg-gray-800 text-white font-semibold rounded hover:bg-gray-700 transition shadow">Next &rarr;</button>
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
                                        <select id="father_occupation_select" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm" required>
                                            <option value="">Select Occupation</option>
                                            <option value="Government Servant">Government Servant</option>
                                            <option value="Private Sector Employee">Private Sector Employee</option>
                                            <option value="Self-employed">Self-employed</option>
                                            <option value="Businessman">Businessman</option>
                                            <option value="Unemployed">Unemployed</option>
                                            <option value="Retired">Retired</option>
                                            <option value="Housewife">Housewife</option>
                                            <option value="Teacher">Teacher</option>
                                            <option value="Lecturer">Lecturer</option>
                                            <option value="Nurse">Nurse</option>
                                            <option value="Doctor">Doctor</option>
                                            <option value="Accountant">Accountant</option>
                                            <option value="Engineer">Engineer</option>
                                            <option value="Clerk">Clerk</option>
                                            <option value="Driver">Driver</option>
                                            <option value="General Worker">General Worker</option>
                                            <option value="Factory Worker">Factory Worker</option>
                                            <option value="Farmer">Farmer</option>
                                            <option value="Fisherman">Fisherman</option>
                                            <option value="Student">Student</option>
                                            <option value="Others">Others (Please specify)</option>
                                        </select>
                                        <div id="father_occupation_other_container" class="mt-2 hidden">
                                            <label class="block font-medium text-xs text-gray-500 dark:text-gray-400">Please specify occupation *</label>
                                            <input type="text" id="father_occupation_other" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm">
                                        </div>
                                        <input type="hidden" name="father_occupation" id="father_occupation" value="{{ old('father_occupation', $latestApplication?->father_occupation) }}">
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
                                        <select id="mother_occupation_select" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm" required>
                                            <option value="">Select Occupation</option>
                                            <option value="Government Servant">Government Servant</option>
                                            <option value="Private Sector Employee">Private Sector Employee</option>
                                            <option value="Self-employed">Self-employed</option>
                                            <option value="Businessman">Businessman</option>
                                            <option value="Unemployed">Unemployed</option>
                                            <option value="Retired">Retired</option>
                                            <option value="Housewife">Housewife</option>
                                            <option value="Teacher">Teacher</option>
                                            <option value="Lecturer">Lecturer</option>
                                            <option value="Nurse">Nurse</option>
                                            <option value="Doctor">Doctor</option>
                                            <option value="Accountant">Accountant</option>
                                            <option value="Engineer">Engineer</option>
                                            <option value="Clerk">Clerk</option>
                                            <option value="Driver">Driver</option>
                                            <option value="General Worker">General Worker</option>
                                            <option value="Factory Worker">Factory Worker</option>
                                            <option value="Farmer">Farmer</option>
                                            <option value="Fisherman">Fisherman</option>
                                            <option value="Student">Student</option>
                                            <option value="Others">Others (Please specify)</option>
                                        </select>
                                        <div id="mother_occupation_other_container" class="mt-2 hidden">
                                            <label class="block font-medium text-xs text-gray-500 dark:text-gray-400">Please specify occupation *</label>
                                            <input type="text" id="mother_occupation_other" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm">
                                        </div>
                                        <input type="hidden" name="mother_occupation" id="mother_occupation" value="{{ old('mother_occupation', $latestApplication?->mother_occupation) }}">
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
                                <h4 class="font-bold text-indigo-900 dark:text-indigo-200 mb-3">Dependents Information</h4>
                                <div>
                                    <label class="block font-medium text-sm text-indigo-800 dark:text-indigo-300">Number of Family Dependents *</label>
                                    <input type="number" name="total_dependents" value="{{ old('total_dependents', $latestApplication?->total_dependents) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm" required>
                                </div>
                            </div>

                            <div class="mt-6 flex items-center justify-between">
                                <button type="button" onclick="switchTab(1)" class="px-6 py-2 bg-white text-gray-700 border border-gray-300 font-semibold rounded hover:bg-gray-50 transition shadow-sm">&larr; Back</button>
                                <button type="button" onclick="switchTab(3)" class="px-6 py-2 bg-gray-800 text-white font-semibold rounded hover:bg-gray-700 transition shadow">Next &rarr;</button>
                            </div>
                        </div>

                        <!-- TAB 3: Education & Bank Info -->
                        <div id="tab-3" class="hidden">
                            <h3 class="text-lg font-bold mb-4 text-indigo-900 dark:text-indigo-300">Education & Banking Information</h3>

                            <!-- Education -->
                            <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-xl mb-6 border border-blue-200 dark:border-blue-800">
                                <h4 class="font-bold text-blue-900 dark:text-blue-200 mb-3 border-b border-blue-200 dark:border-blue-800 pb-2">Education Information</h4>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                                     <div class="md:col-span-2 relative" id="university_combobox_container">
                                         <label class="block font-medium text-sm text-blue-800 dark:text-blue-300">University Name *</label>
                                         <div class="relative mt-1">
                                             <input type="text" id="university_search_input" class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm pr-10 focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="Search or type university name..." autocomplete="off" required>
                                             <button type="button" id="university_toggle_btn" class="absolute inset-y-0 right-0 flex items-center pr-3">
                                                 <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="none" stroke="currentColor">
                                                     <path d="M7 10l5 5 5-5H7z" fill="currentColor"/>
                                                 </svg>
                                             </button>
                                         </div>
                                         <div id="university_dropdown_list" class="absolute z-10 mt-1 hidden w-full rounded-md bg-white dark:bg-gray-800 shadow-lg border border-gray-200 dark:border-gray-700 max-h-60 overflow-y-auto">
                                             <ul id="university_options_ul" class="py-1 text-sm text-gray-700 dark:text-gray-200">
                                             </ul>
                                         </div>
                                         <input type="hidden" name="university_name" id="university_hidden_input" value="{{ old('university_name', $latestApplication?->university_name) }}">
                                     </div>
                                    <div class="relative" id="course_combobox_container">
                                         <label class="block font-medium text-sm text-blue-800 dark:text-blue-300">Course Name *</label>
                                         <div class="relative mt-1">
                                             <input type="text" id="course_search_input" class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm pr-10 focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="Search or type course name..." autocomplete="off" required>
                                             <button type="button" id="course_toggle_btn" class="absolute inset-y-0 right-0 flex items-center pr-3">
                                                 <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="none" stroke="currentColor">
                                                     <path d="M7 10l5 5 5-5H7z" fill="currentColor"/>
                                                 </svg>
                                             </button>
                                         </div>
                                         <div id="course_dropdown_list" class="absolute z-10 mt-1 hidden w-full rounded-md bg-white dark:bg-gray-800 shadow-lg border border-gray-200 dark:border-gray-700 max-h-60 overflow-y-auto">
                                             <ul id="course_options_ul" class="py-1 text-sm text-gray-700 dark:text-gray-200">
                                             </ul>
                                         </div>
                                         <input type="hidden" name="course_name" id="course_hidden_input" value="{{ old('course_name', $latestApplication?->course_name) }}">
                                     </div>
                                    <div>
                                        <label class="block font-medium text-sm text-blue-800 dark:text-blue-300">Level of Study *</label>
                                        <select name="study_level" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm" required>
                                            <option value="">Select Level</option>
                                            <option value="Sijil" {{ old('study_level', $latestApplication?->study_level) == 'Sijil' ? 'selected' : '' }}>Certificate</option>
                                            <option value="Diploma" {{ old('study_level', $latestApplication?->study_level) == 'Diploma' ? 'selected' : '' }}>Diploma</option>
                                            <option value="Ijazah Sarjana Muda" {{ old('study_level', $latestApplication?->study_level) == 'Ijazah Sarjana Muda' ? 'selected' : '' }}>Bachelor's Degree</option>
                                            <option value="Sarjana" {{ old('study_level', $latestApplication?->study_level) == 'Sarjana' ? 'selected' : '' }}>Master's Degree</option>
                                            <option value="PhD" {{ old('study_level', $latestApplication?->study_level) == 'PhD' ? 'selected' : '' }}>PhD</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block font-medium text-sm text-blue-800 dark:text-blue-300">Start Year *</label>
                                        <input type="number" name="start_year" value="{{ old('start_year', $latestApplication?->start_year) }}" placeholder="YYYY" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm" required>
                                    </div>
                                    <div>
                                        <label class="block font-medium text-sm text-blue-800 dark:text-blue-300">End Year *</label>
                                        <input type="number" name="end_year" value="{{ old('end_year', $latestApplication?->end_year) }}" placeholder="YYYY" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Bank -->
                            <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-xl mb-6 border border-green-200 dark:border-green-800">
                                <h4 class="font-bold text-green-900 dark:text-green-200 mb-3 border-b border-green-200 dark:border-green-800 pb-2">Banking Information</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block font-medium text-sm text-green-800 dark:text-green-300">Bank Name *</label>
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
                                        <label class="block font-medium text-sm text-green-800 dark:text-green-300">Account Number *</label>
                                        <input type="text" id="account_number_input" name="account_number" value="{{ old('account_number', $latestApplication?->account_number) }}" maxlength="16" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm" required>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6 flex items-center justify-between">
                                <button type="button" onclick="switchTab(2)" class="px-6 py-2 bg-white text-gray-700 border border-gray-300 font-semibold rounded hover:bg-gray-50 transition shadow-sm">&larr; Back</button>
                                <button type="button" onclick="switchTab(4)" class="px-6 py-2 bg-gray-800 text-white font-semibold rounded hover:bg-gray-700 transition shadow">Next &rarr;</button>
                            </div>
                        </div>

                        <!-- TAB 4: Documents -->
                        <div id="tab-4" class="hidden">
                            <h3 class="text-lg font-bold mb-4 text-indigo-900 dark:text-indigo-300">Supporting Documents</h3>

                            <div class="bg-yellow-50 dark:bg-yellow-900/20 p-4 rounded-xl mb-6 border border-yellow-200 dark:border-yellow-800">
                                <p class="text-sm text-yellow-800 dark:text-yellow-200 mb-4">Please upload copies of the following documents. Allowed formats: PDF, JPG, PNG (Maximum 5MB per file).</p>
                                
                                <div class="space-y-4">
                                    <div>
                                        <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">1. Applicant IC Copy *</label>
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
                                        <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">2. Applicant Birth Certificate Copy *</label>
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
                                        <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">3. Mother's IC Copy *</label>
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
                                        <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">4. Father's IC Copy *</label>
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
                                        <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">5. IPT Offer Letter Copy *</label>
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
                                <button type="button" onclick="switchTab(3)" class="px-6 py-2 bg-white text-gray-700 border border-gray-300 font-semibold rounded hover:bg-gray-50 transition shadow-sm">&larr; Back</button>
                                
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

        // Occupation select handling (shared variables & functions)
        const commonOccupationMappings = {
            'tidak bekerja': 'Unemployed',
            'unemployed': 'Unemployed',
            'pesara': 'Retired',
            'retired': 'Retired',
            'suri rumah': 'Housewife',
            'housewife': 'Housewife',
            'akauntan': 'Accountant',
            'accountant': 'Accountant',
            'guru': 'Teacher',
            'teacher': 'Teacher',
            'pensyarah': 'Lecturer',
            'lecturer': 'Lecturer',
            'jururawat': 'Nurse',
            'nurse': 'Nurse',
            'doktor': 'Doctor',
            'doctor': 'Doctor',
            'jurutera': 'Engineer',
            'engineer': 'Engineer',
            'kerani': 'Clerk',
            'clerk': 'Clerk',
            'pemandu': 'Driver',
            'driver': 'Driver',
            'bekerja sendiri': 'Self-employed',
            'self-employed': 'Self-employed',
            'self employed': 'Self-employed',
            'pelajar': 'Student',
            'student': 'Student',
            'penjawat awam': 'Government Servant',
            'kakitangan awam': 'Government Servant',
            'kakitangan kerajaan': 'Government Servant',
            'kerajaan': 'Government Servant',
            'government servant': 'Government Servant',
            'pekerja swasta': 'Private Sector Employee',
            'swasta': 'Private Sector Employee',
            'private sector employee': 'Private Sector Employee',
            'peniaga': 'Businessman',
            'businessman': 'Businessman',
            'ahli perniagaan': 'Businessman',
            'pekerja am': 'General Worker',
            'buruh': 'General Worker',
            'general worker': 'General Worker',
            'operator kilang': 'Factory Worker',
            'operator mesin': 'Factory Worker',
            'factory worker': 'Factory Worker',
            'petani': 'Farmer',
            'farmer': 'Farmer',
            'nelayan': 'Fisherman',
            'fisherman': 'Fisherman'
        };

        const presetOccupations = [
            'Government Servant', 'Private Sector Employee', 'Self-employed', 'Businessman', 
            'Unemployed', 'Retired', 'Housewife', 'Teacher', 'Lecturer', 'Nurse', 
            'Doctor', 'Accountant', 'Engineer', 'Clerk', 'Driver', 'General Worker', 
            'Factory Worker', 'Farmer', 'Fisherman', 'Student', 'Employed', 'Part-time'
        ];

        function getPresetValue(value) {
            if (!value) return '';
            const normalized = value.trim().toLowerCase();
            return commonOccupationMappings[normalized] || '';
        }

        // Applicant Occupation select handling
        const occSelect = document.getElementById('applicant_occupation_select');
        const occOtherContainer = document.getElementById('applicant_occupation_other_container');
        const occOtherInput = document.getElementById('applicant_occupation_other');
        const occHiddenInput = document.getElementById('applicant_occupation');

        function toggleApplicantOccupationInput() {
            if (occSelect.value === 'Others') {
                occOtherContainer.classList.remove('hidden');
                occOtherInput.required = true;
                occHiddenInput.value = occOtherInput.value;
            } else {
                occOtherContainer.classList.add('hidden');
                occOtherInput.required = false;
                occHiddenInput.value = occSelect.value;
            }
        }

        function updateApplicantOccupationHidden() {
            if (occSelect.value === 'Others') {
                occHiddenInput.value = occOtherInput.value;
            }
        }

        function initApplicantOccupation() {
            const initialValue = occHiddenInput.value || 'Student';
            const preset = getPresetValue(initialValue);
            if (preset) {
                occSelect.value = preset;
                occOtherContainer.classList.add('hidden');
                occOtherInput.required = false;
            } else if (presetOccupations.includes(initialValue)) {
                occSelect.value = initialValue;
                occOtherContainer.classList.add('hidden');
                occOtherInput.required = false;
            } else {
                occSelect.value = 'Others';
                occOtherContainer.classList.remove('hidden');
                occOtherInput.required = true;
                occOtherInput.value = initialValue;
            }
        }

        occSelect.addEventListener('change', toggleApplicantOccupationInput);
        occOtherInput.addEventListener('input', updateApplicantOccupationHidden);
        initApplicantOccupation();

        // Father Occupation select handling
        const fatherOccSelect = document.getElementById('father_occupation_select');
        const fatherOccOtherContainer = document.getElementById('father_occupation_other_container');
        const fatherOccOtherInput = document.getElementById('father_occupation_other');
        const fatherOccHiddenInput = document.getElementById('father_occupation');

        function toggleFatherOccupationInput() {
            if (fatherOccSelect.value === 'Others') {
                fatherOccOtherContainer.classList.remove('hidden');
                fatherOccOtherInput.required = true;
                fatherOccHiddenInput.value = fatherOccOtherInput.value;
            } else {
                fatherOccOtherContainer.classList.add('hidden');
                fatherOccOtherInput.required = false;
                fatherOccHiddenInput.value = fatherOccSelect.value;
            }
        }

        function updateFatherOccupationHidden() {
            if (fatherOccSelect.value === 'Others') {
                fatherOccHiddenInput.value = fatherOccOtherInput.value;
            }
        }

        function initFatherOccupation() {
            const initialValue = fatherOccHiddenInput.value || '';
            const preset = getPresetValue(initialValue);
            if (initialValue === '') {
                fatherOccSelect.value = '';
                fatherOccOtherContainer.classList.add('hidden');
                fatherOccOtherInput.required = false;
            } else if (preset) {
                fatherOccSelect.value = preset;
                fatherOccOtherContainer.classList.add('hidden');
                fatherOccOtherInput.required = false;
            } else if (presetOccupations.includes(initialValue)) {
                fatherOccSelect.value = initialValue;
                fatherOccOtherContainer.classList.add('hidden');
                fatherOccOtherInput.required = false;
            } else {
                fatherOccSelect.value = 'Others';
                fatherOccOtherContainer.classList.remove('hidden');
                fatherOccOtherInput.required = true;
                fatherOccOtherInput.value = initialValue;
            }
        }

        fatherOccSelect.addEventListener('change', toggleFatherOccupationInput);
        fatherOccOtherInput.addEventListener('input', updateFatherOccupationHidden);
        initFatherOccupation();

        // Mother Occupation select handling
        const motherOccSelect = document.getElementById('mother_occupation_select');
        const motherOccOtherContainer = document.getElementById('mother_occupation_other_container');
        const motherOccOtherInput = document.getElementById('mother_occupation_other');
        const motherOccHiddenInput = document.getElementById('mother_occupation');

        function toggleMotherOccupationInput() {
            if (motherOccSelect.value === 'Others') {
                motherOccOtherContainer.classList.remove('hidden');
                motherOccOtherInput.required = true;
                motherOccHiddenInput.value = motherOccOtherInput.value;
            } else {
                motherOccOtherContainer.classList.add('hidden');
                motherOccOtherInput.required = false;
                motherOccHiddenInput.value = motherOccSelect.value;
            }
        }

        function updateMotherOccupationHidden() {
            if (motherOccSelect.value === 'Others') {
                motherOccHiddenInput.value = motherOccOtherInput.value;
            }
        }

        function initMotherOccupation() {
            const initialValue = motherOccHiddenInput.value || '';
            const preset = getPresetValue(initialValue);
            if (initialValue === '') {
                motherOccSelect.value = '';
                motherOccOtherContainer.classList.add('hidden');
                motherOccOtherInput.required = false;
            } else if (preset) {
                motherOccSelect.value = preset;
                motherOccOtherContainer.classList.add('hidden');
                motherOccOtherInput.required = false;
            } else if (presetOccupations.includes(initialValue)) {
                motherOccSelect.value = initialValue;
                motherOccOtherContainer.classList.add('hidden');
                motherOccOtherInput.required = false;
            } else {
                motherOccSelect.value = 'Others';
                motherOccOtherContainer.classList.remove('hidden');
                motherOccOtherInput.required = true;
                motherOccOtherInput.value = initialValue;
            }
        }

        motherOccSelect.addEventListener('change', toggleMotherOccupationInput);
        motherOccOtherInput.addEventListener('input', updateMotherOccupationHidden);
        initMotherOccupation();

        // University Combobox handling
        function initUniversityCombobox() {
            const searchInput = document.getElementById('university_search_input');
            const toggleBtn = document.getElementById('university_toggle_btn');
            const dropdownList = document.getElementById('university_dropdown_list');
            const optionsUl = document.getElementById('university_options_ul');
            const hiddenInput = document.getElementById('university_hidden_input');

            if (!searchInput || !dropdownList || !optionsUl || !hiddenInput) return;

            const universities = [
                "Universiti Teknologi MARA (UiTM)",
                "Universiti Malaya (UM)",
                "Universiti Kebangsaan Malaysia (UKM)",
                "Universiti Sains Malaysia (USM)",
                "Universiti Putra Malaysia (UPM)",
                "Universiti Teknologi Malaysia (UTM)",
                "Universiti Islam Antarabangsa Malaysia (UIAM)",
                "Universiti Utara Malaysia (UUM)",
                "Universiti Malaysia Sarawak (UNIMAS)",
                "Universiti Malaysia Sabah (UMS)",
                "Universiti Pendidikan Sultan Idris (UPSI)",
                "Universiti Sains Islam Malaysia (USIM)",
                "Universiti Malaysia Terengganu (UMT)",
                "Universiti Malaysia Pahang Al-Sultan Abdullah (UMPSA)",
                "Universiti Malaysia Perlis (UniMAP)",
                "Universiti Sultan Zainal Abidin (UniSZA)",
                "Universiti Pertahanan Nasional Malaysia (UPNM)",
                "Universiti Malaysia Kelantan (UMK)",
                "Universiti Teknikal Malaysia Melaka (UTeM)",
                "Universiti Tun Hussein Onn Malaysia (UTHM)",
                "Multimedia University (MMU)",
                "Universiti Tenaga Nasional (UNITEN)",
                "Universiti Teknologi Petronas (UTP)",
                "Universiti Tunku Abdul Rahman (UTAR)",
                "Wawasan Open University (WOU)",
                "Open University Malaysia (OUM)",
                "Sunway University",
                "Taylor's University",
                "UCSI University",
                "INTI International University",
                "Asia Pacific University of Technology & Innovation (APU)",
                "Management and Science University (MSU)",
                "SEGi University",
                "Infrastructure University Kuala Lumpur (IUKL)",
                "Universiti Kuala Lumpur (UniKL)",
                "Universiti Tun Abdul Razak (UNIRAZAK)",
                "Curtin University Malaysia",
                "Monash University Malaysia",
                "Swinburne University of Technology Sarawak Campus",
                "The University of Nottingham Malaysia Campus",
                "Heriot-Watt University Malaysia",
                "Xiamen University Malaysia",
                "Manipal International University",
                "Nilai University",
                "Quest International University",
                "AIMST University",
                "First City University College",
                "Help University",
                "Mahsa University",
                "Binary University",
                "Perdana University",
                "City University Malaysia",
                "DRB-HICOM University of Automotive Malaysia",
                "Lincoln University College",
                "Meritus University",
                "Perlis Islamic University College (KUIPs)",
                "University of Cyberjaya (UoC)"
            ];

            // Set initial value
            if (hiddenInput.value) {
                searchInput.value = hiddenInput.value;
                if (typeof window.onUniversityChange === 'function') {
                    window.onUniversityChange(hiddenInput.value);
                }
            }

            function renderOptions(filterText = '') {
                optionsUl.innerHTML = '';
                const lowerFilter = filterText.toLowerCase().trim();
                
                // Show "Use custom:" option at the top if user has typed something that isn't an exact match
                if (lowerFilter && !universities.some(u => u.toLowerCase() === lowerFilter)) {
                    const customLi = document.createElement('li');
                    customLi.className = 'px-4 py-2 bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 hover:bg-indigo-600 hover:text-white cursor-pointer transition-colors border-b border-gray-100 dark:border-gray-700 font-medium';
                    customLi.textContent = `Use custom: "${filterText}"`;
                    customLi.addEventListener('mousedown', (e) => {
                        e.preventDefault();
                        selectValue(filterText);
                    });
                    optionsUl.appendChild(customLi);
                }

                const filtered = universities.filter(u => u.toLowerCase().includes(lowerFilter));

                if (filtered.length === 0 && !lowerFilter) {
                    const noValLi = document.createElement('li');
                    noValLi.className = 'px-4 py-2 text-gray-400 dark:text-gray-500 italic';
                    noValLi.textContent = 'No universities found';
                    optionsUl.appendChild(noValLi);
                } else {
                    filtered.forEach(univ => {
                        const li = document.createElement('li');
                        li.className = 'px-4 py-2 hover:bg-indigo-600 hover:text-white cursor-pointer dark:hover:bg-indigo-600 transition-colors';
                        li.textContent = univ;
                        li.addEventListener('mousedown', (e) => {
                            e.preventDefault();
                            selectValue(univ);
                        });
                        optionsUl.appendChild(li);
                    });
                }
            }

            function selectValue(val) {
                searchInput.value = val;
                hiddenInput.value = val;
                hideDropdown();
                if (typeof window.onUniversityChange === 'function') {
                    window.onUniversityChange(val);
                }
            }

            function showDropdown() {
                dropdownList.classList.remove('hidden');
                renderOptions(searchInput.value);
            }

            function hideDropdown() {
                dropdownList.classList.add('hidden');
            }

            searchInput.addEventListener('focus', showDropdown);
            searchInput.addEventListener('input', (e) => {
                showDropdown();
                hiddenInput.value = e.target.value;
                if (typeof window.onUniversityChange === 'function') {
                    window.onUniversityChange(e.target.value);
                }
            });

            searchInput.addEventListener('blur', () => {
                hiddenInput.value = searchInput.value;
                hideDropdown();
                if (typeof window.onUniversityChange === 'function') {
                    window.onUniversityChange(searchInput.value);
                }
            });

            toggleBtn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                if (dropdownList.classList.contains('hidden')) {
                    searchInput.focus();
                    showDropdown();
                } else {
                    hideDropdown();
                }
            });

            document.addEventListener('click', (e) => {
                if (!e.target.closest('#university_combobox_container')) {
                    dropdownList.classList.add('hidden');
                }
            });
        }

        // Course Combobox data and handling
        const courseMap = {
            "Universiti Teknologi MARA (UiTM)": [
                "Diploma in Computer Science",
                "Diploma in Information Technology",
                "Bachelor of Computer Science (Hons)",
                "Bachelor of Information Technology (Hons)",
                "Bachelor of Business Administration (Hons) Finance",
                "Bachelor of Business Administration (Hons) Marketing",
                "Bachelor of Accountancy (Hons)",
                "Bachelor of Civil Engineering (Hons)",
                "Bachelor of Mechanical Engineering (Hons)",
                "Bachelor of Electrical Engineering (Hons)",
                "Bachelor of Chemical Engineering (Hons)",
                "Bachelor of Medicine and Bachelor of Surgery (MBBS)",
                "Bachelor of Pharmacy (Hons)",
                "Bachelor of Nursing (Hons)",
                "Bachelor of Art and Design (Hons)",
                "Bachelor of Communication and Media Studies (Hons)"
            ],
            "Universiti Malaya (UM)": [
                "Bachelor of Computer Science (Software Engineering)",
                "Bachelor of Computer Science (Computer Systems and Networks)",
                "Bachelor of Computer Science (Information Systems)",
                "Bachelor of Information Technology (Multimedia)",
                "Bachelor of Medicine and Bachelor of Surgery (MBBS)",
                "Bachelor of Dental Surgery",
                "Bachelor of Pharmacy (Hons)",
                "Bachelor of Laws (LL.B.)",
                "Bachelor of Economics",
                "Bachelor of Accounting",
                "Bachelor of Business Administration",
                "Bachelor of Engineering (Mechanical)",
                "Bachelor of Engineering (Electrical)",
                "Bachelor of Engineering (Chemical)",
                "Bachelor of Engineering (Civil)",
                "Bachelor of Science (Biomedical Science)"
            ],
            "Universiti Kebangsaan Malaysia (UKM)": [
                "Bachelor of Computer Science (Hons)",
                "Bachelor of Software Engineering (Hons)",
                "Bachelor of Information Technology (Hons)",
                "Bachelor of Medicine and Bachelor of Surgery",
                "Bachelor of Pharmacy (Hons)",
                "Bachelor of Laws (Hons)",
                "Bachelor of Economics (Hons)",
                "Bachelor of Business Administration (Hons)",
                "Bachelor of Accounting (Hons)",
                "Bachelor of Engineering (Mechanical) (Hons)",
                "Bachelor of Engineering (Civil) (Hons)",
                "Bachelor of Engineering (Electrical & Electronic) (Hons)"
            ],
            "Universiti Sains Malaysia (USM)": [
                "Bachelor of Computer Science (Hons)",
                "Bachelor of Software Engineering",
                "Bachelor of Engineering (Mechanical Engineering)",
                "Bachelor of Engineering (Electrical Engineering)",
                "Bachelor of Engineering (Civil Engineering)",
                "Bachelor of Medicine and Bachelor of Surgery (MBBS)",
                "Bachelor of Pharmacy (Hons)",
                "Bachelor of Science (Hons) (Physics)",
                "Bachelor of Science (Hons) (Chemistry)",
                "Bachelor of Science (Hons) (Mathematics)",
                "Bachelor of Management (Hons)",
                "Bachelor of Accounting (Hons)"
            ],
            "Universiti Putra Malaysia (UPM)": [
                "Bachelor of Computer Science (Software Engineering)",
                "Bachelor of Computer Science (Computer Networks)",
                "Bachelor of Science (Agriculture)",
                "Bachelor of Veterinary Medicine",
                "Bachelor of Engineering (Mechanical)",
                "Bachelor of Engineering (Civil)",
                "Bachelor of Engineering (Electrical & Electronic)",
                "Bachelor of Science (Hons) (Biology)",
                "Bachelor of Science (Hons) (Chemistry)",
                "Bachelor of Business Administration",
                "Bachelor of Accounting"
            ],
            "Universiti Teknologi Malaysia (UTM)": [
                "Bachelor of Computer Science (Software Engineering)",
                "Bachelor of Computer Science (Computer Networks & Security)",
                "Bachelor of Computer Science (Graphics & Multimedia)",
                "Bachelor of Engineering (Mechanical)",
                "Bachelor of Engineering (Electrical)",
                "Bachelor of Engineering (Chemical)",
                "Bachelor of Engineering (Civil)",
                "Bachelor of Science (Industrial Chemistry)",
                "Bachelor of Science (Industrial Mathematics)",
                "Bachelor of Science (Industrial Physics)"
            ],
            "Universiti Islam Antarabangsa Malaysia (UIAM)": [
                "Bachelor of Computer Science (Hons)",
                "Bachelor of Information Technology (Hons)",
                "Bachelor of Laws (LL.B.) (Hons)",
                "Bachelor of Laws (Shariah) (Hons)",
                "Bachelor of Economics (Hons)",
                "Bachelor of Business Administration (Hons)",
                "Bachelor of Accounting (Hons)",
                "Bachelor of Islamic Revealed Knowledge and Heritage (Hons)",
                "Bachelor of Medicine (MBBS)",
                "Bachelor of Pharmacy (Hons)",
                "Bachelor of Engineering (Mechanical-Aeronautics) (Hons)"
            ],
            "Universiti Utara Malaysia (UUM)": [
                "Bachelor of Science with Honors (Information Technology)",
                "Bachelor of Business Administration with Honors",
                "Bachelor of Accounting with Honors",
                "Bachelor of Finance with Honors",
                "Bachelor of Economics with Honors",
                "Bachelor of Marketing with Honors",
                "Bachelor of Human Resource Management with Honors",
                "Bachelor of Law with Honors (LL.B)",
                "Bachelor of Multimedia with Honors"
            ]
        };

        const generalCourses = [
            "Diploma in Computer Science",
            "Diploma in Information Technology",
            "Diploma in Business Administration",
            "Diploma in Accounting",
            "Diploma in Nursing",
            "Diploma in Pharmacy",
            "Diploma in Civil Engineering",
            "Diploma in Mechanical Engineering",
            "Diploma in Electrical Engineering",
            "Bachelor of Computer Science (Hons)",
            "Bachelor of Software Engineering (Hons)",
            "Bachelor of Information Technology (Hons)",
            "Bachelor of Business Administration (Hons)",
            "Bachelor of Accounting (Hons)",
            "Bachelor of Finance (Hons)",
            "Bachelor of Marketing (Hons)",
            "Bachelor of Economics (Hons)",
            "Bachelor of Laws (LL.B.)",
            "Bachelor of Medicine and Bachelor of Surgery (MBBS)",
            "Bachelor of Pharmacy (Hons)",
            "Bachelor of Nursing (Hons)",
            "Bachelor of Civil Engineering (Hons)",
            "Bachelor of Mechanical Engineering (Hons)",
            "Bachelor of Electrical Engineering (Hons)",
            "Bachelor of Chemical Engineering (Hons)",
            "Bachelor of Science (Hons) (Physics)",
            "Bachelor of Science (Hons) (Chemistry)",
            "Bachelor of Science (Hons) (Mathematics)",
            "Bachelor of Arts (Hons)",
            "Bachelor of Communication (Hons)"
        ];

        function initCourseCombobox() {
            const searchInput = document.getElementById('course_search_input');
            const toggleBtn = document.getElementById('course_toggle_btn');
            const dropdownList = document.getElementById('course_dropdown_list');
            const optionsUl = document.getElementById('course_options_ul');
            const hiddenInput = document.getElementById('course_hidden_input');

            if (!searchInput || !dropdownList || !optionsUl || !hiddenInput) return;

            // Set initial value
            if (hiddenInput.value) {
                searchInput.value = hiddenInput.value;
            }

            function getCourses() {
                const univInput = document.getElementById('university_hidden_input');
                const selectedUniv = univInput ? univInput.value : '';
                if (selectedUniv && courseMap[selectedUniv]) {
                    return courseMap[selectedUniv];
                }
                return generalCourses;
            }

            function renderOptions(filterText = '') {
                optionsUl.innerHTML = '';
                const lowerFilter = filterText.toLowerCase().trim();
                const courses = getCourses();
                
                // Show "Use custom:" option at the top if user has typed something that isn't an exact match
                if (lowerFilter && !courses.some(c => c.toLowerCase() === lowerFilter)) {
                    const customLi = document.createElement('li');
                    customLi.className = 'px-4 py-2 bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 hover:bg-indigo-600 hover:text-white cursor-pointer transition-colors border-b border-gray-100 dark:border-gray-700 font-medium';
                    customLi.textContent = `Use custom: "${filterText}"`;
                    customLi.addEventListener('mousedown', (e) => {
                        e.preventDefault();
                        selectValue(filterText);
                    });
                    optionsUl.appendChild(customLi);
                }

                const filtered = courses.filter(c => c.toLowerCase().includes(lowerFilter));

                if (filtered.length === 0 && !lowerFilter) {
                    const noValLi = document.createElement('li');
                    noValLi.className = 'px-4 py-2 text-gray-400 dark:text-gray-500 italic';
                    noValLi.textContent = 'No courses found';
                    optionsUl.appendChild(noValLi);
                } else {
                    filtered.forEach(course => {
                        const li = document.createElement('li');
                        li.className = 'px-4 py-2 hover:bg-indigo-600 hover:text-white cursor-pointer dark:hover:bg-indigo-600 transition-colors';
                        li.textContent = course;
                        li.addEventListener('mousedown', (e) => {
                            e.preventDefault();
                            selectValue(course);
                        });
                        optionsUl.appendChild(li);
                    });
                }
            }

            function selectValue(val) {
                searchInput.value = val;
                hiddenInput.value = val;
                hideDropdown();
            }

            function showDropdown() {
                dropdownList.classList.remove('hidden');
                renderOptions(searchInput.value);
            }

            function hideDropdown() {
                dropdownList.classList.add('hidden');
            }

            searchInput.addEventListener('focus', showDropdown);
            searchInput.addEventListener('input', (e) => {
                showDropdown();
                hiddenInput.value = e.target.value;
            });

            searchInput.addEventListener('blur', () => {
                hiddenInput.value = searchInput.value;
                hideDropdown();
            });

            toggleBtn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                if (dropdownList.classList.contains('hidden')) {
                    searchInput.focus();
                    showDropdown();
                } else {
                    hideDropdown();
                }
            });

            document.addEventListener('click', (e) => {
                if (!e.target.closest('#course_combobox_container')) {
                    dropdownList.classList.add('hidden');
                }
            });

            // Expose a function to refresh options when university changes
            window.onUniversityChange = function(univ) {
                // If the dropdown is currently open, re-render it
                if (!dropdownList.classList.contains('hidden')) {
                    renderOptions(searchInput.value);
                }
            };
        }

        initCourseCombobox();
        initUniversityCombobox();

        // Account Number digits-only validation
        const accountInput = document.getElementById('account_number_input');
        if (accountInput) {
            accountInput.addEventListener('input', function(e) {
                e.target.value = e.target.value.replace(/[^0-9]/g, '');
            });
        }

    </script>
</x-app-layout>
