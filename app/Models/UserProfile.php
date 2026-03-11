<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class UserProfile extends Model
{
    use HasFactory;

    protected $table = "user_profile";

    protected $fillable = [
        "user_id",
        "full_name",
        "navras_naav",
        "gender",
        "education",
        "occupation",
        "annual_income",
        "date_of_birth",
        "day_and_time_of_birth",
        "place_of_birth",
        "jaath",
        "height_cm__Oonchi",
        "skin_complexion__Rang",
        "zodiac_sign__Raas",
        "naadi",
        "gann",
        "devak",
        "kul_devata",
        "fathers_name",
        "mothers_name",
        "marital_status",
        "siblings",
        "uncles",
        "aunts",
        "mumbai_address",
        "village_address",
        "village_farm",
        "naathe_relationships",
        "primary_image",
    ];

    protected $casts = [
        "date_of_birth" => "date",
        "annual_income" => "decimal:2",
        "primary_image" => "integer",
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Folder inside storage/app/public where this profile's images live.
     * e.g. "profiles/john@example.com"
     */
    public function imageFolder(): string
    {
        $email = $this->user?->email ?? "user_" . $this->user_id;
        // Sanitise so it's safe as a directory name
        $safe = preg_replace("/[^a-zA-Z0-9@._\-]/", "_", $email);
        return "profiles/" . $safe;
    }

    /**
     * Return the public URL for a given slot (1, 2, or 3), or null if the
     * file does not exist.
     */
    public function imageUrl(int $slot): ?string
    {
        foreach (["jpg", "png", "jpeg", "webp"] as $ext) {
            $path = $this->imageFolder() . "/" . $slot . "." . $ext;
            if (Storage::disk("public")->exists($path)) {
                return Storage::disk("public")->url($path);
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
     * Returns an array of all uploaded image URLs keyed by slot number.
     * Only slots that have an actual file are included.
     * e.g. [1 => 'http://...', 3 => 'http://...']
     */
    public function allImageUrls(): array
    {
        $urls = [];
        foreach ([1, 2, 3] as $slot) {
            $url = $this->imageUrl($slot);
            if ($url !== null) {
                $urls[$slot] = $url;
            }
        }
        return $urls;
    }
}
