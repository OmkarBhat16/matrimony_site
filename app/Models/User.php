<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'gender',
        'public_id',
        'password',
        'role',
        'verification_step',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'deleted_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $user): void {
            if (blank($user->public_id)) {
                $user->public_id = static::generatePublicId($user->gender ?? 'other');
            }
        });
    }

    public function setPhoneNumberAttribute($value): void
    {
        $this->attributes['phone_number'] = $value === null
            ? null
            : preg_replace('/\D+/', '', (string) $value);
    }

    public static function generatePublicId(string $gender): string
    {
        $gender = in_array($gender, ['male', 'female', 'other'], true) ? $gender : 'other';

        return DB::transaction(function () use ($gender): string {
            $counter = DB::table('user_id_counters')
                ->where('gender', $gender)
                ->lockForUpdate()
                ->first();

            if ($counter === null) {
                DB::table('user_id_counters')->insert([
                    'gender' => $gender,
                    'next_sequence' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $counter = DB::table('user_id_counters')
                    ->where('gender', $gender)
                    ->lockForUpdate()
                    ->first();
            }

            $nextSequence = ((int) $counter->next_sequence) + 1;

            DB::table('user_id_counters')
                ->where('gender', $gender)
                ->update([
                    'next_sequence' => $nextSequence,
                    'updated_at' => now(),
                ]);

            $prefix = match ($gender) {
                'female' => 'F',
                'male' => 'M',
                default => 'O',
            };

            return sprintf('%s-%05d', $prefix, $nextSequence);
        });
    }

    // -------------------------------------------------------------------------
    // Verification helpers
    // -------------------------------------------------------------------------

    public function isApproved(): bool
    {
        return $this->verification_step === 'approved';
    }

    public function needsOnboarding(): bool
    {
        return $this->verification_step === 'step1_complete';
    }

    public function isPendingReview(): bool
    {
        return $this->verification_step === 'step2_pending';
    }

    public function isUser (): bool
    {
        return $this->role === 'user';
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'superadmin';
    }

    public function canAccessProfileManagementPanel(): bool
    {
        return in_array($this->role, ['profile_manager', 'superadmin'], true);
    }

    public function canAccessContentManagement(): bool
    {
        return in_array($this->role, ['content_editor', 'superadmin'], true);
    }

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }
}
