<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $table = 'user_profile';

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'date_of_birth',
        'gender',
        'marital_status',
        'phone_number',
        'profile_picture',
        'bio',
        'religion',
        'caste',
        'mother_tongue',
        'education',
        'occupation',
        'annual_income',
        'state',
        'city',
        'address',
        'height_cm',
        'weight_kg',
        'dietary_preferences',
        'smoking_habits',
        'drinking_habits',
        'hobbies_interests',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'annual_income' => 'decimal:2',
        'weight_kg' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
