<?php

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;

uses(RefreshDatabase::class);

function makeJpegUpload(string $filename, int $kilobytes): UploadedFile
{
    $path = tempnam(sys_get_temp_dir(), 'upload_');

    $base = base64_decode('/9j/4AAQSkZJRgABAQAAAQABAAD/2wCEAAkGBxAQEBAQEA8PEA8PDw8QDw8PEA8QFREWFhURExUYHSggGBolGxUVITEhJSkrLi4uFx8zODMtNygtLisBCgoKDg0OGhAQGy0lICYtLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLf/AABEIAAEAAQMBIgACEQEDEQH/xAAXAAEBAQEAAAAAAAAAAAAAAAABAgME/8QAFhABAQEAAAAAAAAAAAAAAAAAAAER/8QAFQEBAQAAAAAAAAAAAAAAAAAAAwT/xAAUEQEAAAAAAAAAAAAAAAAAAAAA/9oADAMBAAIRAxEAPwDgA//Z');
    $targetBytes = $kilobytes * 1024;
    $padding = max(0, $targetBytes - strlen($base));

    file_put_contents($path, $base.str_repeat("\0", $padding));

    return new UploadedFile($path, $filename, 'image/jpeg', null, true);
}

afterEach(function () {
    foreach (['9910000001', '9910000002'] as $phoneNumber) {
        File::deleteDirectory(resource_path('assets/'.$phoneNumber));
    }
});

it('allows a profile photo upload up to 10mb', function () {
    $user = User::factory()->create([
        'phone_number' => '9910000001',
        'role' => 'user',
        'verification_step' => 'approved',
    ]);

    $profile = UserProfile::factory()->for($user)->create();

    $response = $this->actingAs($user)->post(route('account.images.upload'), [
        'images' => [
            1 => makeJpegUpload('photo.jpg', 10240),
        ],
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success');

    expect(File::exists($profile->imageFolder().DIRECTORY_SEPARATOR.'1_new.jpg'))->toBeTrue();
});

it('rejects a profile photo upload above 10mb', function () {
    $user = User::factory()->create([
        'phone_number' => '9910000002',
        'role' => 'user',
        'verification_step' => 'approved',
    ]);

    UserProfile::factory()->for($user)->create();

    $response = $this->actingAs($user)->post(route('account.images.upload'), [
        'images' => [
            1 => makeJpegUpload('photo.jpg', 10241),
        ],
    ]);

    $response->assertSessionHasErrors(['images.1']);
});
