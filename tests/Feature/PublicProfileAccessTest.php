<?php

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('allows approved users with profiles to view other profiles', function () {
    $viewer = User::factory()->create([
        'role' => 'user',
        'verification_step' => 'approved',
    ]);

    UserProfile::factory()->for($viewer)->create();

    $target = User::factory()->create([
        'role' => 'user',
        'verification_step' => 'approved',
    ]);

    UserProfile::factory()->for($target)->create();

    $this->actingAs($viewer)
        ->get(route('profile.show', $target))
        ->assertOk();
});

it('allows staff users to view other profiles without their own profile', function () {
    $viewer = User::factory()->create([
        'role' => 'profile_manager',
        'verification_step' => 'approved',
    ]);

    $target = User::factory()->create([
        'role' => 'user',
        'verification_step' => 'approved',
    ]);

    UserProfile::factory()->for($target)->create();

    $this->actingAs($viewer)
        ->get(route('profile.show', $target))
        ->assertOk();
});

it('keeps blocking unapproved users from viewing other profiles', function () {
    $viewer = User::factory()->create([
        'role' => 'user',
        'verification_step' => 'step2_pending',
    ]);

    UserProfile::factory()->for($viewer)->create();

    $target = User::factory()->create([
        'role' => 'user',
        'verification_step' => 'approved',
    ]);

    UserProfile::factory()->for($target)->create();

    $this->actingAs($viewer)
        ->get(route('profile.show', $target))
        ->assertForbidden();
});
