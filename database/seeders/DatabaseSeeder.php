<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserProfile;
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
        // Generate 10 random user profiles (creates associated users automatically)
        $profiles = UserProfile::factory(10)->create();

        // Copy boilerplate images to each profile
        foreach ($profiles as $profile) {
            $this->copyBoilerplateImages($profile);
        }

        // Create tester user with profile
        $tester = User::factory()->create([
            'name' => 'tester',
            'username' => 'tester',
            'email' => 'test@example.com',
            'phone_number' => '9876543210',
            'password' => bcrypt('qwerty123'),
            'verification_step' => 'approved',
            'role' => 'user',
        ]);
        $testerProfile = UserProfile::factory()->create(['user_id' => $tester->id]);
        $this->copyBoilerplateImages($testerProfile);

        // Create admin user with profile
        $admin = User::factory()->create([
            'name' => 'admin',
            'username' => 'admin',
            'email' => 'admin@admin.com',
            'phone_number' => '9999999999',
            'password' => bcrypt('qwerty123'),
            'verification_step' => 'approved',
            'role' => 'admin',
        ]);
        $adminProfile = UserProfile::factory()->create(['user_id' => $admin->id]);
        $this->copyBoilerplateImages($adminProfile);
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
}
