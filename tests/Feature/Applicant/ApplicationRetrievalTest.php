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
        'applicant_marital_status' => 'Bujang',
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
        'applicant_marital_status' => 'Bujang',
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
            'applicant_marital_status' => 'Bujang',
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
