<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Models\SocialAccount;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'email', 'password', 'role', 'confirmation_code'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function title()
    {
        $role = $this->role;
        switch ($role) {
            case UserRole::Student:{
                return $this->hasOne('App\Models\Student');
            }
            case UserRole::Instructor:{
                return $this->hasOne('App\Models\Instructor');
            }
            case UserRole::Admin:{
                return $this->hasOne('App\Models\Admin');
            }
            case UserRole::Mod:{
                return $this->hasOne('App\Models\Mod');
            }
        }
    }
    /**
     * Get all of the socialAccounts for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function socialAccounts()
    {
        return $this->hasMany(SocialAccount::class);
    }

    # get token
    public function findForPassport($username)
    {
        if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
            # user sent their email
            return $this->where('email', $username)->first();
        } else {
            # they sent their username instead
            return $this->where('username', $username)->first();
        }
    }
}
