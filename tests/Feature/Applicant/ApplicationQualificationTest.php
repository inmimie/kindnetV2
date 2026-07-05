<?php

use App\Models\User;
use App\Models\CharityType;
use App\Models\Application;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

beforeEach(function () {
    $this->user = User::factory()->create([
        'role' => 'applicant',
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'phone_number' => '+60123456789',
    ]);

    $this->charityType = CharityType::create([
        'name' => 'Education Fund',
        'description' => 'Help students',
    ]);
});

test('application with total income <= 5000 is pending', function () {
    Storage::fake('public');

    $studentIc = UploadedFile::fake()->create('ic.pdf', 100);
    $birthCert = UploadedFile::fake()->create('birth.pdf', 100);
    $motherIc = UploadedFile::fake()->create('mother_ic.pdf', 100);
    $fatherIc = UploadedFile::fake()->create('father_ic.pdf', 100);
    $offerLetter = UploadedFile::fake()->create('offer.pdf', 100);

    $response = $this->actingAs($this->user)
        ->post(route('applicant.applications.store'), [
            'charity_type_id' => $this->charityType->id,
            'applicant_name' => 'Qualified Student',
            'applicant_ic' => '990101-14-1234',
            'applicant_dob' => '1999-01-01',
            'applicant_gender' => 'Lelaki',
            'applicant_marital_status' => 'Single',
            'applicant_address' => '123 Study St',
            'applicant_phone' => '0123456789',
            'applicant_email' => 'john@example.com',
            'applicant_occupation' => 'Student',
            'father_name' => 'Father Name',
            'father_occupation' => 'Unemployed',
            'father_income' => 2000,
            'mother_name' => 'Mother Name',
            'mother_occupation' => 'Housewife',
            'mother_income' => 0,
            'total_dependents' => 2,
            'course_name' => 'Engineering',
            'study_level' => 'Degree',
            'university_name' => 'UTM',
            'start_year' => 2021,
            'end_year' => 2024,
            'bank_name' => 'Maybank',
            'account_number' => '1234567890',
            'doc_student_ic' => $studentIc,
            'doc_student_birth_cert' => $birthCert,
            'doc_mother_ic' => $motherIc,
            'doc_father_ic' => $fatherIc,
            'doc_offer_letter' => $offerLetter,
        ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(route('applicant.applications.index'));

    $application = Application::where('applicant_name', 'Qualified Student')->first();
    expect($application)->not->toBeNull();
    expect($application->status)->toBe('pending');
});

test('application with total income > 5000 is automatically rejected', function () {
    Storage::fake('public');

    $studentIc = UploadedFile::fake()->create('ic.pdf', 100);
    $birthCert = UploadedFile::fake()->create('birth.pdf', 100);
    $motherIc = UploadedFile::fake()->create('mother_ic.pdf', 100);
    $fatherIc = UploadedFile::fake()->create('father_ic.pdf', 100);
    $offerLetter = UploadedFile::fake()->create('offer.pdf', 100);

    $response = $this->actingAs($this->user)
        ->post(route('applicant.applications.store'), [
            'charity_type_id' => $this->charityType->id,
            'applicant_name' => 'Rich Student',
            'applicant_ic' => '990101-14-5555',
            'applicant_dob' => '1999-01-01',
            'applicant_gender' => 'Lelaki',
            'applicant_marital_status' => 'Single',
            'applicant_address' => '123 Study St',
            'applicant_phone' => '0123456789',
            'applicant_email' => 'john@example.com',
            'applicant_occupation' => 'Student',
            'father_name' => 'Father Name',
            'father_occupation' => 'Manager',
            'father_income' => 6000,
            'mother_name' => 'Mother Name',
            'mother_occupation' => 'Housewife',
            'mother_income' => 0,
            'total_dependents' => 2,
            'course_name' => 'Engineering',
            'study_level' => 'Degree',
            'university_name' => 'UTM',
            'start_year' => 2021,
            'end_year' => 2024,
            'bank_name' => 'Maybank',
            'account_number' => '1234567890',
            'doc_student_ic' => $studentIc,
            'doc_student_birth_cert' => $birthCert,
            'doc_mother_ic' => $motherIc,
            'doc_father_ic' => $fatherIc,
            'doc_offer_letter' => $offerLetter,
        ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(route('applicant.applications.index'));

    $application = Application::where('applicant_name', 'Rich Student')->first();
    expect($application)->not->toBeNull();
    expect($application->status)->toBe('rejected');
});

test('admin incoming applications list excludes rejected applications', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $applicantPending = User::factory()->create(['role' => 'applicant', 'name' => 'Pending applicant']);
    $applicantApproved = User::factory()->create(['role' => 'applicant', 'name' => 'Approved applicant']);
    $applicantRejected = User::factory()->create(['role' => 'applicant', 'name' => 'Rejected applicant']);

    // Create 3 applications: pending, approved, and rejected
    $pending = Application::create([
        'user_id' => $applicantPending->id,
        'charity_type_id' => $this->charityType->id,
        'status' => 'pending',
        'applicant_name' => 'Pending applicant',
        'applicant_ic' => '990101-14-1111',
        'applicant_dob' => '1999-01-01',
        'applicant_gender' => 'Lelaki',
        'applicant_marital_status' => 'Single',
        'applicant_address' => '123 Main St',
        'applicant_phone' => '0123456789',
        'applicant_email' => 'app1@example.com',
        'applicant_occupation' => 'Freelancer',
    ]);

    $approved = Application::create([
        'user_id' => $applicantApproved->id,
        'charity_type_id' => $this->charityType->id,
        'status' => 'approved',
        'applicant_name' => 'Approved applicant',
        'applicant_ic' => '990101-14-2222',
        'applicant_dob' => '1999-01-01',
        'applicant_gender' => 'Lelaki',
        'applicant_marital_status' => 'Single',
        'applicant_address' => '123 Main St',
        'applicant_phone' => '0123456789',
        'applicant_email' => 'app2@example.com',
        'applicant_occupation' => 'Freelancer',
    ]);

    $rejected = Application::create([
        'user_id' => $applicantRejected->id,
        'charity_type_id' => $this->charityType->id,
        'status' => 'rejected',
        'applicant_name' => 'Rejected applicant',
        'applicant_ic' => '990101-14-3333',
        'applicant_dob' => '1999-01-01',
        'applicant_gender' => 'Lelaki',
        'applicant_marital_status' => 'Single',
        'applicant_address' => '123 Main St',
        'applicant_phone' => '0123456789',
        'applicant_email' => 'app3@example.com',
        'applicant_occupation' => 'Freelancer',
    ]);

    $response = $this->actingAs($admin)
        ->get(route('admin.applications.index'));

    $response->assertStatus(200);
    $response->assertSee('Pending applicant');
    $response->assertSee('Approved applicant');
    $response->assertDontSee('Rejected applicant');
});
