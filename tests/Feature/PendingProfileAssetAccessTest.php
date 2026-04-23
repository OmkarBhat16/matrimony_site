<?php

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;

uses(RefreshDatabase::class);

afterEach(function () {
    foreach (['9900000001', '9900000002', '9900000003', '9900000004'] as $phoneNumber) {
        File::deleteDirectory(resource_path('assets/'.$phoneNumber));
    }
});

it('allows a profile owner to view their pending biodata image', function () {
    $owner = User::factory()->create([
        'phone_number' => '9900000001',
        'role' => 'user',
        'verification_step' => 'approved',
    ]);
    $profile = UserProfile::factory()->for($owner)->create();

    File::ensureDirectoryExists($profile->kundliFolder());
    File::put($profile->kundliFolder().DIRECTORY_SEPARATOR.'1_new.jpg', 'pending biodata');

    $this->actingAs($owner)
        ->get(route('profile.kundli.pending.show', $profile))
        ->assertOk();
});

it('allows profile managers to view pending biodata images', function () {
    $owner = User::factory()->create([
        'phone_number' => '9900000002',
        'role' => 'user',
        'verification_step' => 'approved',
    ]);
    $profile = UserProfile::factory()->for($owner)->create();
    $manager = User::factory()->create([
        'role' => 'profile_manager',
        'verification_step' => 'approved',
    ]);

    File::ensureDirectoryExists($profile->kundliFolder());
    File::put($profile->kundliFolder().DIRECTORY_SEPARATOR.'1_new.jpg', 'pending biodata');

    $this->actingAs($manager)
        ->get(route('profile.kundli.pending.show', $profile))
        ->assertOk();
});

it('blocks other users from viewing pending biodata images', function () {
    $owner = User::factory()->create([
        'phone_number' => '9900000003',
        'role' => 'user',
        'verification_step' => 'approved',
    ]);
    $profile = UserProfile::factory()->for($owner)->create();
    $otherUser = User::factory()->create([
        'role' => 'user',
        'verification_step' => 'approved',
    ]);

    File::ensureDirectoryExists($profile->kundliFolder());
    File::put($profile->kundliFolder().DIRECTORY_SEPARATOR.'1_new.jpg', 'pending biodata');

    $this->actingAs($otherUser)
        ->get(route('profile.kundli.pending.show', $profile))
        ->assertForbidden();
});

it('allows a profile owner to view their pending profile photo', function () {
    $owner = User::factory()->create([
        'phone_number' => '9900000004',
        'role' => 'user',
        'verification_step' => 'approved',
    ]);
    $profile = UserProfile::factory()->for($owner)->create();

    File::ensureDirectoryExists($profile->imageFolder());
    File::put($profile->imageFolder().DIRECTORY_SEPARATOR.'1_new.jpg', 'pending photo');

    $this->actingAs($owner)
        ->get(route('profile.images.pending.show', [$profile, 1]))
        ->assertOk();
});
