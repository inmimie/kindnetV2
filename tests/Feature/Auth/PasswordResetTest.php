<?php

use App\Models\User;
use App\Mail\OTPMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

test('reset password link screen can be rendered', function () {
    $response = $this->get('/forgot-password');

    $response->assertStatus(200);
});

test('reset password code can be requested', function () {
    Mail::fake();

    $user = User::factory()->create();

    $response = $this->post('/forgot-password', ['email' => $user->email]);

    $response->assertRedirect(route('password.verify-code-form'));
    $this->assertEquals($user->email, session('reset_email'));

    Mail::assertSent(OTPMail::class, function ($mail) use ($user) {
        return $mail->hasTo($user->email) && strlen($mail->code) === 6;
    });

    $this->assertDatabaseHas('password_reset_tokens', [
        'email' => $user->email,
    ]);
});

test('verify code screen can be rendered', function () {
    $user = User::factory()->create();

    $this->post('/forgot-password', ['email' => $user->email]);

    $response = $this->get('/verify-code');

    $response->assertStatus(200);
});

test('password reset screen can be rendered after successful verification', function () {
    $user = User::factory()->create();

    $this->post('/forgot-password', ['email' => $user->email]);

    $tokenRecord = DB::table('password_reset_tokens')->where('email', $user->email)->first();

    $response = $this->post('/verify-code', ['code' => $tokenRecord->token]);

    $response->assertRedirect(route('password.reset'));
    $this->assertTrue(session('reset_verified'));

    $resetResponse = $this->get('/reset-password');
    $resetResponse->assertStatus(200);
});

test('password can be reset with valid session verification', function () {
    $user = User::factory()->create();

    $this->post('/forgot-password', ['email' => $user->email]);

    $tokenRecord = DB::table('password_reset_tokens')->where('email', $user->email)->first();

    $this->post('/verify-code', ['code' => $tokenRecord->token]);

    $response = $this->post('/reset-password', [
        'password' => 'new-password',
        'password_confirmation' => 'new-password',
    ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('login'));

    $this->assertDatabaseMissing('password_reset_tokens', [
        'email' => $user->email,
    ]);

    $this->assertFalse(session()->has('reset_email'));
    $this->assertFalse(session()->has('reset_verified'));
});
