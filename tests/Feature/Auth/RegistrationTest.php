<?php

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'ic_type' => 'Baru',
        'ic_number' => '981212-01-5555',
        'date_of_birth' => '1998-12-12',
        'place_of_birth' => 'Selangor',
        'marital_status' => 'Bujang',
        'race' => 'Melayu',
        'religion' => 'Islam',
        'citizen' => 'Warganegara',
        'address_line1' => 'No. 123, Jalan Selangor',
        'city' => 'Shah Alam',
        'postcode' => '40000',
        'district' => 'Petaling',
        'state_nation' => 'Selangor',
        'phone_number' => '+60123456789',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});
