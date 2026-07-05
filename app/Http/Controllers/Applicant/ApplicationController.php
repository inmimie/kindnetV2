<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\CharityType;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function index()
    {
        $applications = auth()->user()->applications()->with('charityType')->latest()->get();
        return view('applicant.applications.index', compact('applications'));
    }

    public function create(Request $request)
    {
        $selectedCharityType = null;
        if ($request->has('charity_type_id')) {
            $selectedCharityType = CharityType::find($request->charity_type_id);
            if ($selectedCharityType && $selectedCharityType->status === 'closed') {
                return redirect()->route('applicant.dashboard')->with('error', 'This financial aid category is currently closed.');
            }
        }

        $charityTypes = CharityType::all();
        $latestApplication = auth()->user()->applications()->latest()->first();
        return view('applicant.applications.create', compact('charityTypes', 'latestApplication', 'selectedCharityType'));
    }

    public function store(Request $request)
    {
        $charityType = CharityType::find($request->charity_type_id);
        if ($charityType && $charityType->status === 'closed') {
            return redirect()->route('applicant.dashboard')->with('error', 'This financial aid category is currently closed.');
        }

        $validated = $this->validateApplication($request);
        
        $validated['total_income'] = $validated['father_income'] + $validated['mother_income'];

        $this->handleFileUploads($request, $validated);

        // Retrieve latest application if it exists to copy missing files
        $latest = auth()->user()->applications()->latest()->first();
        if ($latest) {
            $isMedical = $charityType && $charityType->name === 'Medical Support';
            $isLiving = $charityType && $charityType->name === 'Living Allowance';
            if ($isMedical) {
                $fileFields = ['doc_student_ic', 'doc_student_birth_cert', 'doc_salary_slip', 'doc_marriage_cert', 'doc_medical_report', 'doc_pharmacy_quote'];
            } elseif ($isLiving) {
                $fileFields = ['doc_student_ic', 'doc_student_birth_cert', 'doc_bank_statement', 'doc_salary_slip'];
            } else {
                $fileFields = ['doc_student_ic', 'doc_student_birth_cert', 'doc_mother_ic', 'doc_father_ic', 'doc_offer_letter'];
            }
            foreach ($fileFields as $field) {
                if (!isset($validated[$field]) && $latest->$field) {
                    $validated[$field] = $latest->$field;
                }
            }
        }

        // Auto qualification filter
        if ($validated['total_income'] > 5000) {
            $validated['status'] = 'rejected';
        } else {
            $validated['status'] = 'pending';
        }

        $application = auth()->user()->applications()->create($validated);

        return redirect()->route('applicant.applications.index')->with('success', 'Application submitted successfully.');
    }

    public function show(Application $application)
    {
        if ($application->user_id !== auth()->id()) abort(403);
        $application->load(['charityType', 'payment']);
        return view('applicant.applications.show', compact('application'));
    }

    public function edit(Application $application)
    {
        if ($application->user_id !== auth()->id()) abort(403);
        if ($application->status !== 'pending') {
            return redirect()->route('applicant.applications.index')->with('error', 'Cannot edit a processed application.');
        }

        $charityTypes = CharityType::all();
        $selectedCharityType = $application->charityType;
        return view('applicant.applications.edit', compact('application', 'charityTypes', 'selectedCharityType'));
    }

    public function update(Request $request, Application $application)
    {
        if ($application->user_id !== auth()->id()) abort(403);
        if ($application->status !== 'pending') {
            return redirect()->route('applicant.applications.index')->with('error', 'Cannot edit a processed application.');
        }

        $validated = $this->validateApplication($request);
        $validated['total_income'] = $validated['father_income'] + $validated['mother_income'];

        $this->handleFileUploads($request, $validated);

        // Auto qualification filter
        if ($validated['total_income'] > 5000) {
            $validated['status'] = 'rejected';
        } else {
            $validated['status'] = 'pending';
        }

        $application->update($validated);

        return redirect()->route('applicant.applications.index')->with('success', 'Application updated successfully.');
    }

    public function destroy(Application $application)
    {
        if ($application->user_id !== auth()->id()) abort(403);
        if ($application->status !== 'pending') {
            return redirect()->route('applicant.applications.index')->with('error', 'Cannot delete a processed application.');
        }

        $application->delete();
        return redirect()->route('applicant.applications.index')->with('success', 'Application deleted successfully.');
    }

    private function validateApplication(Request $request)
    {
        $latest = auth()->user()->applications()->latest()->first();
        $charityType = CharityType::find($request->charity_type_id);
        $isMedical = $charityType && $charityType->name === 'Medical Support';
        $isLiving = $charityType && $charityType->name === 'Living Allowance';

        $rules = [
            'charity_type_id' => 'required|exists:charity_types,id',
            'amount_requested' => 'nullable|numeric|min:1',
            
            // Tab 1 Fields
            'applicant_name' => 'required|string|max:255',
            'applicant_ic' => 'required|string|max:20',
            'applicant_dob' => 'required|date',
            'applicant_gender' => 'required|string|in:Lelaki,Perempuan',
            'applicant_marital_status' => 'required|string|max:50',
            'applicant_address' => 'required|string',
            'applicant_phone' => 'required|string|max:20',
            'applicant_occupation' => 'required|string|max:255',
            'applicant_email' => 'required|email|max:255',
            
            // Tab 2 Fields
            'father_name' => 'required|string|max:255',
            'father_occupation' => 'required|string|max:255',
            'father_income' => 'required|numeric|min:0',
            'mother_name' => 'required|string|max:255',
            'mother_occupation' => 'required|string|max:255',
            'mother_income' => 'required|numeric|min:0',
            'total_dependents' => 'required|integer|min:0',

            // Tab 3 Fields (Bank Details always required)
            'account_number' => 'required|string|max:16',
            'bank_name' => 'required|string|in:Maybank,CIMB Bank,Public Bank,RHB Bank,Hong Leong Bank,AmBank,Bank Islam,Bank Rakyat,BSN,Bank Muamalat,Agrobank',
        ];

        if ($isMedical || $isLiving) {
            // Education fields are not required and nullable
            $rules += [
                'course_name' => 'nullable|string|max:255',
                'study_level' => 'nullable|string|max:255',
                'university_name' => 'nullable|string|max:255',
                'start_year' => 'nullable|integer|min:1900',
                'end_year' => 'nullable|integer',
            ];

            if ($isMedical) {
                // Medical Documents
                $rules += [
                    'doc_student_ic' => ($request->isMethod('post') && !$latest?->doc_student_ic ? 'required' : 'nullable') . '|file|mimes:pdf,jpg,jpeg,png|max:5120',
                    'doc_student_birth_cert' => ($request->isMethod('post') && !$latest?->doc_student_birth_cert ? 'required' : 'nullable') . '|file|mimes:pdf,jpg,jpeg,png|max:5120',
                    'doc_salary_slip' => ($request->isMethod('post') && !$latest?->doc_salary_slip ? 'required' : 'nullable') . '|file|mimes:pdf,jpg,jpeg,png|max:5120',
                    'doc_marriage_cert' => ($request->isMethod('post') && !$latest?->doc_marriage_cert ? 'required' : 'nullable') . '|file|mimes:pdf,jpg,jpeg,png|max:5120',
                    'doc_medical_report' => ($request->isMethod('post') && !$latest?->doc_medical_report ? 'required' : 'nullable') . '|file|mimes:pdf,jpg,jpeg,png|max:5120',
                    'doc_pharmacy_quote' => ($request->isMethod('post') && !$latest?->doc_pharmacy_quote ? 'required' : 'nullable') . '|file|mimes:pdf,jpg,jpeg,png|max:5120',
                ];
            } else {
                // Living Documents (Applicant IC, Birth Cert, Bank Statement, Salary Slip)
                $rules += [
                    'doc_student_ic' => ($request->isMethod('post') && !$latest?->doc_student_ic ? 'required' : 'nullable') . '|file|mimes:pdf,jpg,jpeg,png|max:5120',
                    'doc_student_birth_cert' => ($request->isMethod('post') && !$latest?->doc_student_birth_cert ? 'required' : 'nullable') . '|file|mimes:pdf,jpg,jpeg,png|max:5120',
                    'doc_bank_statement' => ($request->isMethod('post') && !$latest?->doc_bank_statement ? 'required' : 'nullable') . '|file|mimes:pdf,jpg,jpeg,png|max:5120',
                    'doc_salary_slip' => ($request->isMethod('post') && !$latest?->doc_salary_slip ? 'required' : 'nullable') . '|file|mimes:pdf,jpg,jpeg,png|max:5120',
                ];
            }
        } else {
            // Education fields required
            $rules += [
                'course_name' => 'required|string|max:255',
                'study_level' => 'required|string|max:255',
                'university_name' => 'required|string|max:255',
                'start_year' => 'required|integer|min:1900',
                'end_year' => 'required|integer|gte:start_year',
            ];

            // Education Documents
            $rules += [
                'doc_student_ic' => ($request->isMethod('post') && !$latest?->doc_student_ic ? 'required' : 'nullable') . '|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'doc_student_birth_cert' => ($request->isMethod('post') && !$latest?->doc_student_birth_cert ? 'required' : 'nullable') . '|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'doc_mother_ic' => ($request->isMethod('post') && !$latest?->doc_mother_ic ? 'required' : 'nullable') . '|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'doc_father_ic' => ($request->isMethod('post') && !$latest?->doc_father_ic ? 'required' : 'nullable') . '|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'doc_offer_letter' => ($request->isMethod('post') && !$latest?->doc_offer_letter ? 'required' : 'nullable') . '|file|mimes:pdf,jpg,jpeg,png|max:5120',
            ];
        }

        return $request->validate($rules);
    }

    private function handleFileUploads(Request $request, array &$validated)
    {
        $fileFields = [
            'doc_student_ic', 'doc_student_birth_cert', 
            'doc_mother_ic', 'doc_father_ic', 'doc_offer_letter',
            'doc_salary_slip', 'doc_marriage_cert', 'doc_medical_report', 'doc_pharmacy_quote',
            'doc_bank_statement'
        ];
        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                $validated[$field] = $request->file($field)->store('documents', 'public');
            } else {
                unset($validated[$field]);
            }
        }
    }
}
