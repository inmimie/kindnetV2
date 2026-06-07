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

    $this->applicant1 = User::factory()->create([
        'role' => 'applicant',
        'name' => 'Alice Smith',
        'email' => 'alice@example.com',
    ]);

    $this->applicant2 = User::factory()->create([
        'role' => 'applicant',
        'name' => 'Bob Jones',
        'email' => 'bob@example.com',
    ]);

    $this->charityType1 = CharityType::create([
        'name' => 'Medical Aid',
        'description' => 'Medical expenses help',
    ]);

    $this->charityType2 = CharityType::create([
        'name' => 'Education Fund',
        'description' => 'Educational help',
    ]);

    // Create a few applications
    $this->app1 = Application::create([
        'user_id' => $this->applicant1->id,
        'charity_type_id' => $this->charityType1->id,
        'status' => 'pending',
        'applicant_name' => 'Alice Smith',
        'applicant_ic' => '990101-14-1234',
        'applicant_dob' => '1999-01-01',
        'applicant_gender' => 'Lelaki',
        'applicant_marital_status' => 'Single',
        'applicant_address' => '123 Hospital St',
        'applicant_phone' => '0123456789',
        'applicant_email' => 'alice@example.com',
        'applicant_occupation' => 'Technician',
    ]);

    $this->app2 = Application::create([
        'user_id' => $this->applicant2->id,
        'charity_type_id' => $this->charityType2->id,
        'status' => 'approved',
        'applicant_name' => 'Bob Jones',
        'applicant_ic' => '990101-14-5678',
        'applicant_dob' => '1999-01-01',
        'applicant_gender' => 'Lelaki',
        'applicant_marital_status' => 'Single',
        'applicant_address' => '456 School St',
        'applicant_phone' => '0123456789',
        'applicant_email' => 'bob@example.com',
        'applicant_occupation' => 'Technician',
    ]);
});

test('admin can access dashboard and see applications', function () {
    $response = $this->actingAs($this->admin)
        ->get(route('admin.dashboard'));

    $response->assertStatus(200);
    $response->assertSee('Alice Smith');
    $response->assertSee('Bob Jones');
});

test('applicant cannot access admin dashboard', function () {
    $response = $this->actingAs($this->applicant1)
        ->get(route('admin.dashboard'));

    $response->assertStatus(403);
});

test('admin can search applications by applicant name', function () {
    $response = $this->actingAs($this->admin)
        ->get(route('admin.dashboard', ['search' => 'Alice']));

    $response->assertStatus(200);
    $response->assertSee('Alice Smith');
    $response->assertDontSee('Bob Jones');
});

test('admin can filter applications by status', function () {
    $response = $this->actingAs($this->admin)
        ->get(route('admin.dashboard', ['status' => 'approved']));

    $response->assertStatus(200);
    $response->assertDontSee('Alice Smith');
    $response->assertSee('Bob Jones');
});

test('admin can filter applications by charity type', function () {
    $response = $this->actingAs($this->admin)
        ->get(route('admin.dashboard', ['charity_type_id' => $this->charityType1->id]));

    $response->assertStatus(200);
    $response->assertSee('Alice Smith');
    $response->assertDontSee('Bob Jones');
});
