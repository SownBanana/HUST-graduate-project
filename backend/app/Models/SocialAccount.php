<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class SocialAccount extends Model
{
    protected $fillable = [
        'user_id',
        'social_id',
        'social_provider',
        'social_name',
        'social_email',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
