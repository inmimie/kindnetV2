<?php

use App\Models\User;
use App\Models\CharityType;
use App\Models\Application;

beforeEach(function () {
    $this->admin = User::factory()->create([
        'role' => 'admin',
        'name' => 'Admin User',
        'email' => 'admin@example.com',
    ]);

    $this->applicant = User::factory()->create([
        'role' => 'applicant',
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'phone_number' => '+60123456789',
    ]);

    $this->charityType = CharityType::create([
        'name' => 'Medical Aid',
        'description' => 'Medical expenses help',
    ]);

    $this->pendingApp = Application::create([
        'user_id' => $this->applicant->id,
        'charity_type_id' => $this->charityType->id,
        'status' => 'pending',
        'applicant_name' => 'John Doe',
        'applicant_ic' => '990101-14-1234',
        'applicant_dob' => '1999-01-01',
        'applicant_gender' => 'Lelaki',
        'applicant_marital_status' => 'Single',
        'applicant_address' => '123 Hospital St',
        'applicant_phone' => '0123456789',
        'applicant_email' => 'john@example.com',
        'applicant_occupation' => 'Technician',
    ]);

    $this->approvedApp = Application::create([
        'user_id' => $this->applicant->id,
        'charity_type_id' => $this->charityType->id,
        'status' => 'approved',
        'applicant_name' => 'John Doe',
        'applicant_ic' => '990101-14-1234',
        'applicant_dob' => '1999-01-01',
        'applicant_gender' => 'Lelaki',
        'applicant_marital_status' => 'Single',
        'applicant_address' => '123 Hospital St',
        'applicant_phone' => '0123456789',
        'applicant_email' => 'john@example.com',
        'applicant_occupation' => 'Technician',
    ]);
});

test('admin can view application details', function () {
    $response = $this->actingAs($this->admin)
        ->get(route('admin.applications.show', $this->pendingApp));

    $response->assertStatus(200);
    $response->assertSee('Review Application #' . $this->pendingApp->id);
});

test('admin can update pending application status', function () {
    $response = $this->actingAs($this->admin)
        ->put(route('admin.applications.update', $this->pendingApp), [
            'status' => 'approved',
        ]);

    $response->assertRedirect(route('admin.applications.show', $this->pendingApp));
    $response->assertSessionHas('success', 'Application status updated.');
    
    $this->pendingApp->refresh();
    expect($this->pendingApp->status)->toBe('approved');
    expect($this->pendingApp->approved_at)->not->toBeNull();
});

test('admin cannot update approved application status', function () {
    $response = $this->actingAs($this->admin)
        ->put(route('admin.applications.update', $this->approvedApp), [
            'status' => 'rejected',
        ]);

    $response->assertRedirect(route('admin.applications.show', $this->approvedApp));
    $response->assertSessionHas('error', 'Cannot update status of an application that is already approved or rejected.');
    
    $this->approvedApp->refresh();
    expect($this->approvedApp->status)->toBe('approved');
});

test('status change form is visible only when status is pending', function () {
    // Check pending application page (form should be visible)
    $response = $this->actingAs($this->admin)
        ->get(route('admin.applications.show', $this->pendingApp));
    $response->assertSee('Change Status');
    $response->assertSee('Update Status');

    // Check approved application page (form should not be visible)
    $response = $this->actingAs($this->admin)
        ->get(route('admin.applications.show', $this->approvedApp));
    $response->assertDontSee('Change Status');
    $response->assertDontSee('Update Status');
});
