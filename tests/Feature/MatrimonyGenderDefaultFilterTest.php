<?php

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('defaults approved basic male users to female profiles on matrimony', function () {
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
        ->assertViewHas('filters', fn (array $filters) => ($filters['gender'] ?? null) === 'female');
});

it('shows all profiles when an approved basic user selects all genders', function () {
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
        ->get(route('root.matrimony', ['gender' => 'all']))
        ->assertOk()
        ->assertSee('Female Match')
        ->assertSee('Male Match')
        ->assertViewHas('filters', fn (array $filters) => ($filters['gender'] ?? null) === 'all');
});

it('does not apply the default gender filter to approved admin users', function () {
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
        ->assertViewHas('filters', fn (array $filters) => ! array_key_exists('gender', $filters));
});
