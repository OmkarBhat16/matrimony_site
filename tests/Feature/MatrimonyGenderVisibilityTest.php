<?php

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('shows approved male users only female profiles on matrimony', function () {
    $viewer = User::factory()->create([
        'role' => 'user',
        'gender' => 'male',
        'verification_step' => 'approved',
    ]);

    UserProfile::factory()->for($viewer)->create([
        'gender' => 'male',
        'full_name' => 'Viewer Profile',
    ]);

    $femaleUser = User::factory()->create([
        'role' => 'user',
        'gender' => 'female',
        'verification_step' => 'approved',
    ]);

    UserProfile::factory()->for($femaleUser)->create([
        'gender' => 'female',
        'full_name' => 'Female Match',
    ]);

    $maleUser = User::factory()->create([
        'role' => 'user',
        'gender' => 'male',
        'verification_step' => 'approved',
    ]);

    UserProfile::factory()->for($maleUser)->create([
        'gender' => 'male',
        'full_name' => 'Male Match',
    ]);

    $this->actingAs($viewer)
        ->get(route('root.matrimony'))
        ->assertOk()
        ->assertSee('Female Match')
        ->assertDontSee('Male Match')
        ->assertDontSee('name="gender"', false);
});

it('shows approved female users only male profiles on matrimony', function () {
    $viewer = User::factory()->create([
        'role' => 'user',
        'gender' => 'female',
        'verification_step' => 'approved',
    ]);

    UserProfile::factory()->for($viewer)->create([
        'gender' => 'female',
        'full_name' => 'Viewer Profile',
    ]);

    $femaleUser = User::factory()->create([
        'role' => 'user',
        'gender' => 'female',
        'verification_step' => 'approved',
    ]);

    UserProfile::factory()->for($femaleUser)->create([
        'gender' => 'female',
        'full_name' => 'Female Match',
    ]);

    $maleUser = User::factory()->create([
        'role' => 'user',
        'gender' => 'male',
        'verification_step' => 'approved',
    ]);

    UserProfile::factory()->for($maleUser)->create([
        'gender' => 'male',
        'full_name' => 'Male Match',
    ]);

    $this->actingAs($viewer)
        ->get(route('root.matrimony'))
        ->assertOk()
        ->assertSee('Male Match')
        ->assertDontSee('Female Match');
});

it('shows all approved profiles to approved non-user accounts', function () {
    $viewer = User::factory()->create([
        'role' => 'profile_manager',
        'gender' => 'male',
        'verification_step' => 'approved',
    ]);

    $femaleUser = User::factory()->create([
        'role' => 'user',
        'gender' => 'female',
        'verification_step' => 'approved',
    ]);

    UserProfile::factory()->for($femaleUser)->create([
        'gender' => 'female',
        'full_name' => 'Female Match',
    ]);

    $maleUser = User::factory()->create([
        'role' => 'user',
        'gender' => 'male',
        'verification_step' => 'approved',
    ]);

    UserProfile::factory()->for($maleUser)->create([
        'gender' => 'male',
        'full_name' => 'Male Match',
    ]);

    $this->actingAs($viewer)
        ->get(route('root.matrimony'))
        ->assertOk()
        ->assertSee('Female Match')
        ->assertSee('Male Match')
        ->assertDontSee('name="gender"', false);
});
