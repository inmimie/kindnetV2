<?php

use App\Models\User;
use App\Models\CharityType;
use App\Models\Application;
use App\Notifications\ApplicationApprovedNotification;
use Illuminate\Support\Facades\Notification;

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
});

test('admin approving application sends database notification to applicant', function () {
    Notification::fake();

    $response = $this->actingAs($this->admin)
        ->put(route('admin.applications.update', $this->pendingApp), [
            'status' => 'approved',
        ]);

    $response->assertRedirect(route('admin.applications.show', $this->pendingApp));
    
    Notification::assertSentTo(
        $this->applicant,
        ApplicationApprovedNotification::class,
        function ($notification, $channels) {
            return in_array('database', $channels);
        }
    );
});

test('applicant can view notifications page and mark a notification as read', function () {
    // Manually create a notification in database
    $this->pendingApp->update([
        'status' => 'approved',
        'approved_at' => now(),
    ]);
    
    $this->applicant->notify(new ApplicationApprovedNotification($this->pendingApp));

    expect($this->applicant->unreadNotifications->count())->toBe(1);

    // Get notifications page
    $response = $this->actingAs($this->applicant)
        ->get(route('applicant.notifications.index'));

    $response->assertStatus(200);
    $response->assertSee('Medical Aid');
    $response->assertSee('approved');

    // Mark as read
    $notification = $this->applicant->unreadNotifications->first();
    $readResponse = $this->actingAs($this->applicant)
        ->post(route('applicant.notifications.read', $notification->id));

    $readResponse->assertRedirect();
    expect($this->applicant->fresh()->unreadNotifications->count())->toBe(0);
});

test('applicant can mark all notifications as read', function () {
    // Manually create notifications in database
    $this->pendingApp->update([
        'status' => 'approved',
        'approved_at' => now(),
    ]);
    
    $this->applicant->notify(new ApplicationApprovedNotification($this->pendingApp));

    expect($this->applicant->unreadNotifications->count())->toBe(1);

    // Mark all as read
    $readResponse = $this->actingAs($this->applicant)
        ->post(route('applicant.notifications.read-all'));

    $readResponse->assertRedirect();
    expect($this->applicant->fresh()->unreadNotifications->count())->toBe(0);
});

test('applicant can get unread notifications count and payload', function () {
    // Manually create a notification in database
    $this->pendingApp->update([
        'status' => 'approved',
        'approved_at' => now(),
    ]);
    
    $this->applicant->notify(new ApplicationApprovedNotification($this->pendingApp));

    $response = $this->actingAs($this->applicant)
        ->get(route('applicant.notifications.unread-count'));

    $response->assertStatus(200);
    $response->assertJson([
        'unread_count' => 1,
        'latest_unread' => [
            'charity_type_name' => 'Medical Aid',
            'message' => "Your charity application #{$this->pendingApp->id} for Medical Aid has been approved.",
        ]
    ]);
});
