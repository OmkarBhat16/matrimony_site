<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    public const IMAGE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp'];

    protected $table = 'user_profile';

    protected $fillable = [
        'user_id',
        'full_name',
        'navras_naav',
        'gender',
        'education',
        'occupation',
        'annual_income',
        'date_of_birth',
        'blood_group',
        'day_and_time_of_birth',
        'place_of_birth',
        'jaath',
        'height_cm__Oonchi',
        'skin_complexion__Rang',
        'zodiac_sign__Raas',
        'naadi',
        'gann',
        'devak',
        'kul_devata',
        'fathers_name',
        'mothers_name',
        'marital_status',
        'siblings',
        'uncles',
        'aunts',
        'address',
        'native_address',
        'village_farm',
        'naathe_relationships',
        'primary_image',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'annual_income' => 'decimal:2',
        'primary_image' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function featuredProfile()
    {
        return $this->hasOne(FeaturedProfile::class);
    }

    /**
     * Absolute folder path for this profile's images under resources/assets.
     */
    public function imageFolder(): string
    {
        $phoneNumber = $this->user?->phone_number ?? 'user_'.$this->user_id;
        $safe = preg_replace("/[^a-zA-Z0-9._\-]/", '_', $phoneNumber);

        return resource_path('assets/'.$safe);
    }

    /**
     * Absolute folder path for this profile's kundli image.
     */
    public function kundliFolder(): string
    {
        return $this->imageFolder().DIRECTORY_SEPARATOR.'kundli';
    }

    /**
     * Absolute path for the kundli image, if present.
     */
    public function kundliImagePath(): ?string
    {
        $path = $this->kundliFolder().DIRECTORY_SEPARATOR.'1.jpg';

        return is_file($path) ? $path : null;
    }

    /**
     * Public URL for the kundli image, if present.
     */
    public function kundliImageUrl(): ?string
    {
        if (! $this->kundliImagePath()) {
            return null;
        }

        return route('profile.kundli.show', [
            'userProfile' => $this,
        ]);
    }

    /**
     * Absolute path for the pending kundli image, if present.
     */
    public function pendingKundliImagePath(): ?string
    {
        $path = $this->kundliFolder().DIRECTORY_SEPARATOR.'1_new.jpg';

        return is_file($path) ? $path : null;
    }

    /**
     * Public URL for the pending kundli image, if present.
     */
    public function pendingKundliImageUrl(): ?string
    {
        if (! $this->pendingKundliImagePath()) {
            return null;
        }

        return route('profile.kundli.pending.show', [
            'userProfile' => $this,
        ]);
    }

    /**
     * Base filename for a given slot, with optional suffix for pending files.
     */
    public function imageBaseName(int $slot, ?string $suffix = null): string
    {
        return $slot.($suffix ? '_'.$suffix : '');
    }

    /**
     * Absolute path for a given slot and optional suffix/extension.
     */
    public function imagePath(int $slot, ?string $suffix = null, ?string $extension = null): ?string
    {
        $extension = $extension ? strtolower($extension) : null;

        if ($extension !== null) {
            return $this->imageFolder().DIRECTORY_SEPARATOR.$this->imageBaseName($slot, $suffix).'.'.$extension;
        }

        foreach (self::IMAGE_EXTENSIONS as $ext) {
            $path = $this->imageFolder().DIRECTORY_SEPARATOR.$this->imageBaseName($slot, $suffix).'.'.$ext;
            if (is_file($path)) {
                return $path;
            }
        }

        return null;
    }

    /**
     * Return the stored extension for the current published image, if present.
     */
    public function imageExtension(int $slot): ?string
    {
        $path = $this->imagePath($slot);

        return $path ? strtolower(pathinfo($path, PATHINFO_EXTENSION)) : null;
    }

    /**
     * Return the stored extension for a pending replacement image, if present.
     */
    public function pendingImageExtension(int $slot): ?string
    {
        $path = $this->imagePath($slot, 'new');

        return $path ? strtolower(pathinfo($path, PATHINFO_EXTENSION)) : null;
    }

    /**
     * Return the absolute path for a pending replacement image, if present.
     */
    public function pendingImagePath(int $slot): ?string
    {
        return $this->imagePath($slot, 'new');
    }

    /**
     * Return true when a pending replacement exists for the given slot.
     */
    public function hasPendingImageReplacement(int $slot): bool
    {
        return $this->pendingImagePath($slot) !== null;
    }

    /**
     * Return the public URL for a given slot (1, 2, 3, or 4), or null if the
     * file does not exist.
     */
    public function imageUrl(int $slot): ?string
    {
        foreach (['jpg', 'png', 'jpeg', 'webp'] as $ext) {
            $path = $this->imageFolder().DIRECTORY_SEPARATOR.$this->imageBaseName($slot).'.'.$ext;
            if (is_file($path)) {
                return route('profile.images.show', [
                    'userProfile' => $this,
                    'slot' => $slot,
                ]);
            }
        }

        return null;
    }

    /**
     * URL of the primary image, falling back to null if none uploaded.
     */
    public function primaryImageUrl(): ?string
    {
        return $this->imageUrl($this->primary_image ?? 1);
    }

    /**
     * Return a public URL for a pending replacement image, if present.
     */
    public function pendingImageUrl(int $slot): ?string
    {
        if (! $this->hasPendingImageReplacement($slot)) {
            return null;
        }

        return route('profile.images.pending.show', [
            'userProfile' => $this,
            'slot' => $slot,
        ]);
    }

    /**
     * Returns an array of all uploaded image URLs keyed by slot number.
     * Only slots that have an actual file are included.
     * e.g. [1 => 'http://...', 3 => 'http://...']
     */
    public function allImageUrls(): array
    {
        $urls = [];
        foreach ([1, 2, 3, 4] as $slot) {
            $url = $this->imageUrl($slot);
            if ($url !== null) {
                $urls[$slot] = $url;
            }
        }

        return $urls;
    }
}
