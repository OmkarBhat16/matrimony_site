<?php

namespace App\Services;

use App\Models\UserProfile;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;

class ProfileImageManager
{
    /**
     * Store a published image directly into slot.ext, replacing any current image.
     */
    public function storeCurrentImage(UserProfile $profile, int $slot, UploadedFile $file): void
    {
        $folder = $profile->imageFolder();
        File::ensureDirectoryExists($folder);

        $this->deleteCurrentImageVariants($profile, $slot);

        $file->move($folder, $this->filename($slot, $file->getClientOriginalExtension()));
    }

    /**
     * Store a pending replacement as slot_new.ext, preserving the current image.
     */
    public function storePendingImage(UserProfile $profile, int $slot, UploadedFile $file): void
    {
        $folder = $profile->imageFolder();
        File::ensureDirectoryExists($folder);

        $this->deletePendingImageVariants($profile, $slot);

        $file->move($folder, $this->filename($slot, $file->getClientOriginalExtension(), true));
    }

    /**
     * Move a pending image into the published slot, deleting the old current file first.
     */
    public function approvePendingImage(UserProfile $profile, int $slot): void
    {
        $pendingPath = $profile->pendingImagePath($slot);

        if ($pendingPath === null || !File::exists($pendingPath)) {
            return;
        }

        $this->deleteCurrentImageVariants($profile, $slot);

        File::move($pendingPath, $this->currentPathFromPending($pendingPath));
    }

    /**
     * Delete a pending image without touching the current published file.
     */
    public function rejectPendingImage(UserProfile $profile, int $slot): void
    {
        $this->deletePendingImageVariants($profile, $slot);
    }

    public function hasCurrentImage(UserProfile $profile, int $slot): bool
    {
        return $profile->imagePath($slot) !== null;
    }

    public function hasPendingImage(UserProfile $profile, int $slot): bool
    {
        return $profile->pendingImagePath($slot) !== null;
    }

    /**
     * Delete any published file for the given slot.
     */
    public function deleteCurrentImageVariants(UserProfile $profile, int $slot): void
    {
        foreach (UserProfile::IMAGE_EXTENSIONS as $extension) {
            $path = $profile->imagePath($slot, null, $extension);
            if ($path && File::exists($path)) {
                File::delete($path);
            }
        }
    }

    /**
     * Delete any pending replacement file for the given slot.
     */
    public function deletePendingImageVariants(UserProfile $profile, int $slot): void
    {
        foreach (UserProfile::IMAGE_EXTENSIONS as $extension) {
            $path = $profile->imagePath($slot, 'new', $extension);
            if ($path && File::exists($path)) {
                File::delete($path);
            }
        }
    }

    public function currentImageUrl(UserProfile $profile, int $slot): ?string
    {
        return $profile->imageUrl($slot);
    }

    public function pendingImageUrl(UserProfile $profile, int $slot): ?string
    {
        return $profile->pendingImageUrl($slot);
    }

    private function filename(int $slot, string $extension, bool $pending = false): string
    {
        $extension = strtolower($extension ?: 'jpg');

        return $slot . ($pending ? '_new' : '') . '.' . $extension;
    }

    private function currentPathFromPending(string $pendingPath): string
    {
        return preg_replace('/_new(?=\.[^.]+$)/', '', $pendingPath) ?? $pendingPath;
    }
}
