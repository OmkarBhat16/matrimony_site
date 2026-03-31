<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserProfile;
use App\Models\AboutPageContent;
use App\Models\FeaturedProfile;
use App\Models\HomePageContent;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Path to boilerplate images (relative to project root).
     */
    private const BOILERPLATE_DIR = '../scripts/boilerplate_images';

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        HomePageContent::query()->updateOrCreate(['id' => 1], [
            'content' => HomePageContent::defaults(),
        ]);

        AboutPageContent::query()->updateOrCreate(['id' => 1], [
            'content' => AboutPageContent::defaults(),
        ]);

        // Generate 10 random user profiles (creates associated users automatically)
        $profiles = UserProfile::factory(10)->create();
        $profiles->each(function (UserProfile $profile): void {
            $this->ensurePublicId($profile->user);
        });

        // Copy boilerplate images to each profile
        foreach ($profiles as $profile) {
            $this->copyBoilerplateImages($profile);
        }

        foreach ($profiles->take(4) as $profile) {
            FeaturedProfile::query()->updateOrCreate([
                'user_profile_id' => $profile->id,
            ]);
        }

        // Create tester user with profile
        $tester = User::factory()->create([
            'name' => 'tester',
            'email' => 'test@example.com',
            'phone_number' => '9876543210',
            'gender' => 'male',
            'password' => bcrypt('qwerty123'),
            'verification_step' => 'approved',
            'role' => 'user',
        ]);
        $this->ensurePublicId($tester);
        $testerProfile = UserProfile::factory()->create(['user_id' => $tester->id]);
        $this->copyBoilerplateImages($testerProfile);

        // Create profile manager user with profile
        $profileManager = User::factory()->create([
            'name' => 'profile_manager',
            'email' => 'profile_manager@admin.com',
            'phone_number' => '9999999999',
            'gender' => 'male',
            'password' => bcrypt('qwerty123'),
            'verification_step' => 'approved',
            'role' => 'profile_manager',
        ]);
        $this->ensurePublicId($profileManager);
        $profileManagerProfile = UserProfile::factory()->create(['user_id' => $profileManager->id]);
        $this->copyBoilerplateImages($profileManagerProfile);

        // Create content editor user with profile
        $contentEditor = User::factory()->create([
            'name' => 'content_editor',
            'email' => 'content_editor@admin.com',
            'phone_number' => '9988776655',
            'gender' => 'female',
            'password' => bcrypt('qwerty123'),
            'verification_step' => 'approved',
            'role' => 'content_editor',
        ]);
        $this->ensurePublicId($contentEditor);
        $contentEditorProfile = UserProfile::factory()->create(['user_id' => $contentEditor->id]);
        $this->copyBoilerplateImages($contentEditorProfile);

        // Create superadmin user with profile
        $superadmin = User::factory()->create([
            'name' => 'superadmin',
            'email' => 'superadmin@admin.com',
            'phone_number' => '9977886655',
            'gender' => 'male',
            'password' => bcrypt('qwerty123'),
            'verification_step' => 'approved',
            'role' => 'superadmin',
        ]);
        $this->ensurePublicId($superadmin);
        $superadminProfile = UserProfile::factory()->create(['user_id' => $superadmin->id]);
        $this->copyBoilerplateImages($superadminProfile);
    }

    /**
     * Copy the three boilerplate images into a profile's image folder as slots 1, 2, 3.
     */
    private function copyBoilerplateImages(UserProfile $profile): void
    {
        $boilerplateDir = base_path(self::BOILERPLATE_DIR);

        if (!File::isDirectory($boilerplateDir)) {
            $this->command->warn("Boilerplate images directory not found: {$boilerplateDir}");
            return;
        }

        // Get all jpg/png/webp files sorted alphabetically
        $files = collect(File::files($boilerplateDir))
            ->filter(fn ($f) => in_array(strtolower($f->getExtension()), ['jpg', 'jpeg', 'png', 'webp']))
            ->sortBy(fn ($f) => $f->getFilename())
            ->values();

        if ($files->isEmpty()) {
            $this->command->warn("No image files found in boilerplate directory.");
            return;
        }

        $folder = $profile->imageFolder();
        Storage::disk('public')->makeDirectory($folder);

        foreach ($files->take(3) as $index => $file) {
            $slot = $index + 1;
            $ext = strtolower($file->getExtension());
            $destination = $folder . '/' . $slot . '.' . $ext;

            Storage::disk('public')->put(
                $destination,
                File::get($file->getPathname())
            );
        }
    }

    /**
     * Ensure a seeded user has a public ID even when model events are disabled.
     */
    private function ensurePublicId(User $user): void
    {
        if (filled($user->public_id)) {
            return;
        }

        $user->forceFill([
            'public_id' => User::generatePublicId($user->gender ?? 'other'),
        ])->saveQuietly();
    }
}
