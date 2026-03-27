<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeaturedProfile extends Model
{
    protected $fillable = [
        'user_profile_id',
    ];

    public function userProfile()
    {
        return $this->belongsTo(UserProfile::class);
    }
}
