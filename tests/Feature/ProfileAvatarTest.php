<?php

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('user can upload profile picture', function () {
    Storage::fake('public');

    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->patch('/profile/avatar', [
            'profile_picture' => UploadedFile::fake()->image('avatar.jpg'),
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/profile');

    $user->refresh();
    $this->assertNotNull($user->profile_picture);
    Storage::disk('public')->assertExists($user->profile_picture);
});

test('profile picture upload requires valid image type', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->patch('/profile/avatar', [
            'profile_picture' => UploadedFile::fake()->create('document.pdf', 500, 'application/pdf'),
        ]);

    $response->assertSessionHasErrors('profile_picture');
    $this->assertNull($user->refresh()->profile_picture);
});

test('profile picture upload fails if image is too large', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->patch('/profile/avatar', [
            'profile_picture' => UploadedFile::fake()->image('avatar.jpg')->size(3000), // 3MB
        ]);

    $response->assertSessionHasErrors('profile_picture');
    $this->assertNull($user->refresh()->profile_picture);
});

test('user can delete their profile picture', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    
    // Store an initial avatar
    $avatarPath = UploadedFile::fake()->image('avatar.jpg')->store('profile_pictures', 'public');
    $user->profile_picture = $avatarPath;
    $user->save();

    Storage::disk('public')->assertExists($avatarPath);

    $response = $this
        ->actingAs($user)
        ->delete('/profile/avatar');

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/profile');

    $user->refresh();
    $this->assertNull($user->profile_picture);
    Storage::disk('public')->assertMissing($avatarPath);
});
