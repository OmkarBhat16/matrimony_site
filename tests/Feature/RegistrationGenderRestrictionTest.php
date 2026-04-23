<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('does not offer the other gender option on the registration page', function () {
    $this->get(route('register'))
        ->assertOk()
        ->assertDontSee('value="other"', false);
});

it('rejects other as a registration gender', function () {
    $response = $this->post(route('register.submit'), [
        'name' => 'Test User',
        'phone_number' => '9876543210',
        'gender' => 'other',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertSessionHasErrors(['gender']);
});

it('never generates an o-prefixed public id for unsupported genders', function () {
    expect(User::generatePublicId('other'))->toStartWith('M-');
});
