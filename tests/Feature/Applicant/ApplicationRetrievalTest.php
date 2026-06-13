<?php

use App\Models\User;
use App\Models\CharityType;
use App\Models\Application;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->user = User::factory()->create([
        'role' => 'applicant',
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'phone_number' => '0123456789',
    ]);

    $this->charityType = CharityType::create([
        'name' => 'Education Fund',
        'description' => 'Help students',
    ]);
});

test('create page shows auth defaults when no previous application exists', function () {
    $response = $this->actingAs($this->user)
        ->get(route('applicant.applications.create', ['charity_type_id' => $this->charityType->id]));

    $response->assertStatus(200);
    $response->assertSee('John Doe');
    $response->assertSee('john@example.com');
    $response->assertSee('0123456789');
});

test('create page pre-fills from previous application details if present', function () {
    // Create a previous application for this user
    $this->user->applications()->create([
        'charity_type_id' => $this->charityType->id,
        'applicant_name' => 'John Senior',
        'applicant_ic' => '950101-14-5555',
        'applicant_dob' => '1995-01-01',
        'applicant_gender' => 'Lelaki',
        'applicant_marital_status' => 'Single',
        'applicant_address' => '123 Test St',
        'applicant_phone' => '0111111111',
        'applicant_email' => 'senior@example.com',
        'applicant_occupation' => 'Freelancer',
        'father_name' => 'Father Doe',
        'father_occupation' => 'Retired',
        'father_income' => 1500,
        'mother_name' => 'Mother Doe',
        'mother_occupation' => 'Housewife',
        'mother_income' => 0,
        'total_income' => 1500,
        'total_dependents' => 3,
        'course_name' => 'Computer Science',
        'study_level' => 'Diploma',
        'university_name' => 'UTM',
        'start_year' => 2020,
        'end_year' => 2023,
        'bank_name' => 'Maybank',
        'account_number' => '1234567890',
        'doc_student_ic' => 'documents/stub_ic.pdf',
        'doc_student_birth_cert' => 'documents/stub_birth.pdf',
        'doc_mother_ic' => 'documents/stub_mother.pdf',
        'doc_father_ic' => 'documents/stub_father.pdf',
        'doc_offer_letter' => 'documents/stub_offer.pdf',
    ]);

    $response = $this->actingAs($this->user)
        ->get(route('applicant.applications.create', ['charity_type_id' => $this->charityType->id]));

    $response->assertStatus(200);
    
    // Check pre-filled values
    $response->assertSee('John Senior');
    $response->assertSee('950101-14-5555');
    $response->assertSee('1995-01-01');
    $response->assertSee('123 Test St');
    $response->assertSee('0111111111');
    $response->assertSee('senior@example.com');
    $response->assertSee('Freelancer');
    $response->assertSee('Father Doe');
    $response->assertSee('Mother Doe');
    $response->assertSee('Computer Science');
    $response->assertSee('UTM');
    $response->assertSee('1234567890');
    
    // Check view document link presence
    $response->assertSee('stub_ic.pdf');
    $response->assertSee('stub_birth.pdf');
    $response->assertSee('stub_mother.pdf');
    $response->assertSee('stub_father.pdf');
    $response->assertSee('stub_offer.pdf');
});

test('submitting a new application correctly carries over documents from previous application', function () {
    Storage::fake('public');

    // Create a previous application for this user
    $this->user->applications()->create([
        'charity_type_id' => $this->charityType->id,
        'applicant_name' => 'John Doe',
        'applicant_ic' => '950101-14-5555',
        'applicant_dob' => '1995-01-01',
        'applicant_gender' => 'Lelaki',
        'applicant_marital_status' => 'Single',
        'applicant_address' => '123 Test St',
        'applicant_phone' => '0123456789',
        'applicant_email' => 'john@example.com',
        'applicant_occupation' => 'Freelancer',
        'father_name' => 'Father Doe',
        'father_occupation' => 'Retired',
        'father_income' => 1500,
        'mother_name' => 'Mother Doe',
        'mother_occupation' => 'Housewife',
        'mother_income' => 0,
        'total_income' => 1500,
        'total_dependents' => 3,
        'course_name' => 'Computer Science',
        'study_level' => 'Diploma',
        'university_name' => 'UTM',
        'start_year' => 2020,
        'end_year' => 2023,
        'bank_name' => 'Maybank',
        'account_number' => '1234567890',
        'doc_student_ic' => 'documents/prev_ic.pdf',
        'doc_student_birth_cert' => 'documents/prev_birth.pdf',
        'doc_mother_ic' => 'documents/prev_mother.pdf',
        'doc_father_ic' => 'documents/prev_father.pdf',
        'doc_offer_letter' => 'documents/prev_offer.pdf',
    ]);

    // Submit new application but with only ONE new document (mother IC), others left blank to test carry over
    $newOfferLetter = UploadedFile::fake()->create('new_offer.pdf', 100);

    $response = $this->actingAs($this->user)
        ->post(route('applicant.applications.store'), [
            'charity_type_id' => $this->charityType->id,
            'applicant_name' => 'John Doe II',
            'applicant_ic' => '950101-14-5555',
            'applicant_dob' => '1995-01-01',
            'applicant_gender' => 'Lelaki',
            'applicant_marital_status' => 'Single',
            'applicant_address' => '123 Test St',
            'applicant_phone' => '0123456789',
            'applicant_email' => 'john@example.com',
            'applicant_occupation' => 'Freelancer',
            'father_name' => 'Father Doe',
            'father_occupation' => 'Retired',
            'father_income' => 1500,
            'mother_name' => 'Mother Doe',
            'mother_occupation' => 'Housewife',
            'mother_income' => 0,
            'total_dependents' => 3,
            'course_name' => 'Computer Science',
            'study_level' => 'Diploma',
            'university_name' => 'UTM',
            'start_year' => 2020,
            'end_year' => 2023,
            'bank_name' => 'Maybank',
            'account_number' => '1234567890',
            // No new uploads for ic, birth, mother_ic, father_ic - should be carried over
            'doc_offer_letter' => $newOfferLetter, // New upload
        ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(route('applicant.applications.index'));

    // Fetch the new application (second one)
    $newApp = Application::where('applicant_name', 'John Doe II')->first();
    expect($newApp)->not->toBeNull();

    // Verify new offer letter is stored in public storage and has a new path
    expect($newApp->doc_offer_letter)->not->toBe('documents/prev_offer.pdf');
    Storage::disk('public')->assertExists($newApp->doc_offer_letter);

    // Verify other documents are carried over from the previous application
    expect($newApp->doc_student_ic)->toBe('documents/prev_ic.pdf');
    expect($newApp->doc_student_birth_cert)->toBe('documents/prev_birth.pdf');
    expect($newApp->doc_mother_ic)->toBe('documents/prev_mother.pdf');
    expect($newApp->doc_father_ic)->toBe('documents/prev_father.pdf');
});

test('submitting a medical support application validates medical files and allows empty education fields', function () {
    Storage::fake('public');
    
    $medicalCharityType = CharityType::create([
        'name' => 'Medical Support',
        'description' => 'Help medical needs',
    ]);

    $studentIc = UploadedFile::fake()->create('ic.pdf', 100);
    $birthCert = UploadedFile::fake()->create('birth.pdf', 100);
    $salarySlip = UploadedFile::fake()->create('salary.pdf', 100);
    $marriageCert = UploadedFile::fake()->create('marriage.pdf', 100);
    $medicalReport = UploadedFile::fake()->create('medical.pdf', 100);
    $pharmacyQuote = UploadedFile::fake()->create('quote.pdf', 100);

    $response = $this->actingAs($this->user)
        ->post(route('applicant.applications.store'), [
            'charity_type_id' => $medicalCharityType->id,
            'applicant_name' => 'Medical Applicant',
            'applicant_ic' => '950101-14-5555',
            'applicant_dob' => '1995-01-01',
            'applicant_gender' => 'Lelaki',
            'applicant_marital_status' => 'Single',
            'applicant_address' => '123 Medical St',
            'applicant_phone' => '0123456789',
            'applicant_email' => 'john@example.com',
            'applicant_occupation' => 'Freelancer',
            'father_name' => 'Father Doe',
            'father_occupation' => 'Retired',
            'father_income' => 1500,
            'mother_name' => 'Mother Doe',
            'mother_occupation' => 'Housewife',
            'mother_income' => 0,
            'total_dependents' => 3,
            
            // Education fields are omitted/empty
            'course_name' => '',
            'study_level' => '',
            'university_name' => '',
            'start_year' => '',
            'end_year' => '',
            
            'bank_name' => 'Maybank',
            'account_number' => '1234567890',
            
            'doc_student_ic' => $studentIc,
            'doc_student_birth_cert' => $birthCert,
            'doc_salary_slip' => $salarySlip,
            'doc_marriage_cert' => $marriageCert,
            'doc_medical_report' => $medicalReport,
            'doc_pharmacy_quote' => $pharmacyQuote,
        ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(route('applicant.applications.index'));

    $newApp = Application::where('applicant_name', 'Medical Applicant')->first();
    expect($newApp)->not->toBeNull();
    expect($newApp->doc_salary_slip)->not->toBeNull();
    expect($newApp->doc_marriage_cert)->not->toBeNull();
    expect($newApp->doc_medical_report)->not->toBeNull();
    expect($newApp->doc_pharmacy_quote)->not->toBeNull();
    
    // Education fields should be null/empty
    expect($newApp->course_name)->toBeNull();
    expect($newApp->university_name)->toBeNull();
    
    Storage::disk('public')->assertExists($newApp->doc_salary_slip);
    Storage::disk('public')->assertExists($newApp->doc_marriage_cert);
    Storage::disk('public')->assertExists($newApp->doc_medical_report);
    Storage::disk('public')->assertExists($newApp->doc_pharmacy_quote);
});

test('submitting a medical support application carries over medical documents from a previous medical application', function () {
    Storage::fake('public');
    
    $medicalCharityType = CharityType::create([
        'name' => 'Medical Support',
        'description' => 'Help medical needs',
    ]);

    // Create previous medical application
    $this->user->applications()->create([
        'charity_type_id' => $medicalCharityType->id,
        'applicant_name' => 'Prev Medical App',
        'applicant_ic' => '950101-14-5555',
        'applicant_dob' => '1995-01-01',
        'applicant_gender' => 'Lelaki',
        'applicant_marital_status' => 'Single',
        'applicant_address' => '123 Medical St',
        'applicant_phone' => '0123456789',
        'applicant_email' => 'john@example.com',
        'applicant_occupation' => 'Freelancer',
        'father_name' => 'Father Doe',
        'father_occupation' => 'Retired',
        'father_income' => 1500,
        'mother_name' => 'Mother Doe',
        'mother_occupation' => 'Housewife',
        'mother_income' => 0,
        'total_income' => 1500,
        'total_dependents' => 3,
        'bank_name' => 'Maybank',
        'account_number' => '1234567890',
        'doc_student_ic' => 'documents/prev_med_ic.pdf',
        'doc_student_birth_cert' => 'documents/prev_med_birth.pdf',
        'doc_salary_slip' => 'documents/prev_med_salary.pdf',
        'doc_marriage_cert' => 'documents/prev_med_marriage.pdf',
        'doc_medical_report' => 'documents/prev_med_report.pdf',
        'doc_pharmacy_quote' => 'documents/prev_med_quote.pdf',
    ]);

    // Submit new medical application without uploaded files (they should carry over)
    $response = $this->actingAs($this->user)
        ->post(route('applicant.applications.store'), [
            'charity_type_id' => $medicalCharityType->id,
            'applicant_name' => 'New Medical App',
            'applicant_ic' => '950101-14-5555',
            'applicant_dob' => '1995-01-01',
            'applicant_gender' => 'Lelaki',
            'applicant_marital_status' => 'Single',
            'applicant_address' => '123 Medical St',
            'applicant_phone' => '0123456789',
            'applicant_email' => 'john@example.com',
            'applicant_occupation' => 'Freelancer',
            'father_name' => 'Father Doe',
            'father_occupation' => 'Retired',
            'father_income' => 1500,
            'mother_name' => 'Mother Doe',
            'mother_occupation' => 'Housewife',
            'mother_income' => 0,
            'total_dependents' => 3,
            'bank_name' => 'Maybank',
            'account_number' => '1234567890',
        ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(route('applicant.applications.index'));

    $newApp = Application::where('applicant_name', 'New Medical App')->first();
    expect($newApp)->not->toBeNull();
    expect($newApp->doc_student_ic)->toBe('documents/prev_med_ic.pdf');
    expect($newApp->doc_student_birth_cert)->toBe('documents/prev_med_birth.pdf');
    expect($newApp->doc_salary_slip)->toBe('documents/prev_med_salary.pdf');
    expect($newApp->doc_marriage_cert)->toBe('documents/prev_med_marriage.pdf');
    expect($newApp->doc_medical_report)->toBe('documents/prev_med_report.pdf');
    expect($newApp->doc_pharmacy_quote)->toBe('documents/prev_med_quote.pdf');
});

test('submitting a living allowance application validates living documents and allows empty education fields', function () {
    Storage::fake('public');
    
    $livingCharityType = CharityType::create([
        'name' => 'Living Allowance',
        'description' => 'Help with living needs',
    ]);

    $studentIc = UploadedFile::fake()->create('ic.pdf', 100);
    $birthCert = UploadedFile::fake()->create('birth.pdf', 100);
    $bankStatement = UploadedFile::fake()->create('statement.pdf', 100);
    $salarySlip = UploadedFile::fake()->create('salary.pdf', 100);

    // Submit with empty education fields and verify no errors
    $response = $this->actingAs($this->user)
        ->post(route('applicant.applications.store'), [
            'charity_type_id' => $livingCharityType->id,
            'applicant_name' => 'Living Applicant',
            'applicant_ic' => '950101-14-5555',
            'applicant_dob' => '1995-01-01',
            'applicant_gender' => 'Lelaki',
            'applicant_marital_status' => 'Single',
            'applicant_address' => '123 Living St',
            'applicant_phone' => '0123456789',
            'applicant_email' => 'john@example.com',
            'applicant_occupation' => 'Freelancer',
            'father_name' => 'Father Doe',
            'father_occupation' => 'Retired',
            'father_income' => 1500,
            'mother_name' => 'Mother Doe',
            'mother_occupation' => 'Housewife',
            'mother_income' => 0,
            'total_dependents' => 3,
            
            // Empty education fields
            'course_name' => '',
            'study_level' => '',
            'university_name' => '',
            'start_year' => '',
            'end_year' => '',
            
            'bank_name' => 'Maybank',
            'account_number' => '1234567890',
            
            'doc_student_ic' => $studentIc,
            'doc_student_birth_cert' => $birthCert,
            'doc_bank_statement' => $bankStatement,
            'doc_salary_slip' => $salarySlip,
        ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(route('applicant.applications.index'));

    $newApp = Application::where('applicant_name', 'Living Applicant')->first();
    expect($newApp)->not->toBeNull();
    expect($newApp->doc_bank_statement)->not->toBeNull();
    expect($newApp->doc_salary_slip)->not->toBeNull();
    expect($newApp->doc_mother_ic)->toBeNull();
    
    // Education fields should be null/empty
    expect($newApp->course_name)->toBeNull();
    expect($newApp->university_name)->toBeNull();
    
    Storage::disk('public')->assertExists($newApp->doc_bank_statement);
    Storage::disk('public')->assertExists($newApp->doc_salary_slip);
});

test('submitting a living allowance application carries over documents from a previous living application', function () {
    Storage::fake('public');
    
    $livingCharityType = CharityType::create([
        'name' => 'Living Allowance',
        'description' => 'Help with living needs',
    ]);

    // Create previous living application
    $this->user->applications()->create([
        'charity_type_id' => $livingCharityType->id,
        'applicant_name' => 'Prev Living App',
        'applicant_ic' => '950101-14-5555',
        'applicant_dob' => '1995-01-01',
        'applicant_gender' => 'Lelaki',
        'applicant_marital_status' => 'Single',
        'applicant_address' => '123 Living St',
        'applicant_phone' => '0123456789',
        'applicant_email' => 'john@example.com',
        'applicant_occupation' => 'Freelancer',
        'father_name' => 'Father Doe',
        'father_occupation' => 'Retired',
        'father_income' => 1500,
        'mother_name' => 'Mother Doe',
        'mother_occupation' => 'Housewife',
        'mother_income' => 0,
        'total_income' => 1500,
        'total_dependents' => 3,
        'course_name' => 'Computer Science',
        'study_level' => 'Diploma',
        'university_name' => 'UTM',
        'start_year' => 2020,
        'end_year' => 2023,
        'bank_name' => 'Maybank',
        'account_number' => '1234567890',
        'doc_student_ic' => 'documents/prev_liv_ic.pdf',
        'doc_student_birth_cert' => 'documents/prev_liv_birth.pdf',
        'doc_bank_statement' => 'documents/prev_liv_statement.pdf',
        'doc_salary_slip' => 'documents/prev_liv_salary.pdf',
    ]);

    // Submit new application without uploaded files (they should carry over)
    $response = $this->actingAs($this->user)
        ->post(route('applicant.applications.store'), [
            'charity_type_id' => $livingCharityType->id,
            'applicant_name' => 'New Living App',
            'applicant_ic' => '950101-14-5555',
            'applicant_dob' => '1995-01-01',
            'applicant_gender' => 'Lelaki',
            'applicant_marital_status' => 'Single',
            'applicant_address' => '123 Living St',
            'applicant_phone' => '0123456789',
            'applicant_email' => 'john@example.com',
            'applicant_occupation' => 'Freelancer',
            'father_name' => 'Father Doe',
            'father_occupation' => 'Retired',
            'father_income' => 1500,
            'mother_name' => 'Mother Doe',
            'mother_occupation' => 'Housewife',
            'mother_income' => 0,
            'total_dependents' => 3,
            'course_name' => 'Computer Science',
            'study_level' => 'Diploma',
            'university_name' => 'UTM',
            'start_year' => 2020,
            'end_year' => 2023,
            'bank_name' => 'Maybank',
            'account_number' => '1234567890',
        ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(route('applicant.applications.index'));

    $newApp = Application::where('applicant_name', 'New Living App')->first();
    expect($newApp)->not->toBeNull();
    expect($newApp->doc_student_ic)->toBe('documents/prev_liv_ic.pdf');
    expect($newApp->doc_student_birth_cert)->toBe('documents/prev_liv_birth.pdf');
    expect($newApp->doc_bank_statement)->toBe('documents/prev_liv_statement.pdf');
    expect($newApp->doc_salary_slip)->toBe('documents/prev_liv_salary.pdf');
});

