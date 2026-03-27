<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EditUserProfile extends Model
{
    protected $table = 'edit_user_profiles';

    /**
     * Fields that can be compared between the current profile and the edit request.
     */
    public const DIFFABLE_FIELDS = [
        'full_name' => 'Full Name',
        'navras_naav' => 'Navras Naav',
        'gender' => 'Gender',
        'education' => 'Education',
        'occupation' => 'Occupation',
        'annual_income' => 'Annual Income',
        'date_of_birth' => 'Date of Birth',
        'day_and_time_of_birth' => 'Day & Time of Birth',
        'place_of_birth' => 'Place of Birth',
        'jaath' => 'Jaath',
        'height_cm__Oonchi' => 'Height (Oonchi)',
        'skin_complexion__Rang' => 'Skin Complexion (Rang)',
        'zodiac_sign__Raas' => 'Zodiac Sign (Raas)',
        'naadi' => 'Naadi',
        'gann' => 'Gann',
        'devak' => 'Devak',
        'kul_devata' => 'Kul Devata',
        'fathers_name' => "Father's Name",
        'mothers_name' => "Mother's Name",
        'marital_status' => 'Marital Status',
        'siblings' => 'Siblings',
        'uncles' => 'Uncles',
        'aunts' => 'Aunts',
        'address' => 'Residential Address',
        'native_address' => 'Native Address',
        'village_farm' => 'Village Farm',
        'naathe_relationships' => 'Naathe Relationships',
    ];

    protected $fillable = [
        'user_id',
        'edit_type',
        'full_name',
        'navras_naav',
        'gender',
        'education',
        'occupation',
        'annual_income',
        'date_of_birth',
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
        'image_changes',
        'status',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'annual_income' => 'decimal:2',
        'image_changes' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Build a diff array: only fields where the edit value differs from the current profile.
     * Returns [ field_key => ['label' => ..., 'old' => ..., 'new' => ...], ... ]
     */
    public function diff(UserProfile $currentProfile): array
    {
        $changes = [];

        foreach (self::DIFFABLE_FIELDS as $field => $label) {
            $oldVal = (string) ($currentProfile->{$field} ?? '');
            $newVal = (string) ($this->{$field} ?? '');

            if ($oldVal !== $newVal) {
                $changes[$field] = [
                    'label' => $label,
                    'old' => $oldVal ?: '—',
                    'new' => $newVal ?: '—',
                ];
            }
        }

        return $changes;
    }

    /**
     * Slots with pending image replacements, keyed by slot number.
     */
    public function pendingImageSlots(): array
    {
        $imageChanges = $this->image_changes ?? [];

        return array_values(array_filter(array_map('intval', array_keys($imageChanges))));
    }

    public function hasPendingKundliImage(): bool
    {
        $imageChanges = $this->image_changes ?? [];

        return array_key_exists('kundli', $imageChanges);
    }

    public function hasProfileFieldValues(): bool
    {
        foreach (array_keys(self::DIFFABLE_FIELDS) as $field) {
            $value = $this->{$field};

            if ($value !== null && $value !== '') {
                return true;
            }
        }

        return false;
    }

    /**
     * True when this edit contains either field changes or image replacements.
     */
    public function hasPendingChanges(UserProfile $currentProfile): bool
    {
        return ! empty($this->diff($currentProfile)) || ! empty($this->pendingImageSlots()) || $this->hasPendingKundliImage();
    }
}
