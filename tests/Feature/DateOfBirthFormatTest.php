<?php

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;

uses(RefreshDatabase::class);

function makeJpegUploadForDateOfBirthTest(string $filename = 'photo.jpg'): UploadedFile
{
    $path = tempnam(sys_get_temp_dir(), 'upload_');

    $jpeg = base64_decode('/9j/4AAQSkZJRgABAQAAAQABAAD/2wCEAAkGBxAQEBAQEA8PEA8PDw8QDw8PEA8QFREWFhURExUYHSggGBolGxUVITEhJSkrLi4uFx8zODMtNygtLisBCgoKDg0OGhAQGy0lICYtLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLf/AABEIAAEAAQMBIgACEQEDEQH/xAAXAAEBAQEAAAAAAAAAAAAAAAABAgME/8QAFhABAQEAAAAAAAAAAAAAAAAAAAER/8QAFQEBAQAAAAAAAAAAAAAAAAAAAwT/xAAUEQEAAAAAAAAAAAAAAAAAAAAA/9oADAMBAAIRAxEAPwDgA//Z');

    file_put_contents($path, $jpeg);

    return new UploadedFile($path, $filename, 'image/jpeg', null, true);
}

it('shows date of birth in dd-mm-yyyy on the edit form', function () {
    $user = User::factory()->create([
        'role' => 'user',
        'verification_step' => 'approved',
    ]);

    UserProfile::factory()->for($user)->create([
        'date_of_birth' => '1999-12-31',
    ]);

    $this->actingAs($user)
        ->get(route('account.edit'))
        ->assertOk()
        ->assertSee('31-12-1999');
});

it('accepts dd-mm-yyyy on onboarding and stores a normalized date', function () {
    $user = User::factory()->create([
        'role' => 'user',
        'verification_step' => 'step1_complete',
    ]);

    $response = $this->actingAs($user)->post(route('onboarding.store'), [
        'full_name' => 'Date Format User',
        'date_of_birth' => '31-12-1999',
        'images' => [
            1 => makeJpegUploadForDateOfBirthTest(),
        ],
        'primary_image' => 1,
    ]);

    $response->assertRedirect('/pending-review');

    $profile = $user->fresh()->profile;

    expect($profile)->not->toBeNull();
    expect($profile?->date_of_birth?->format('d-m-Y'))->toBe('31-12-1999');
    expect($profile?->date_of_birth?->format('Y-m-d'))->toBe('1999-12-31');
});
