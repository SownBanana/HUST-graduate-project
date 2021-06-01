<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asset extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'url',
        'type',
        'owner_id',
        'assetable_type',
        'assetable_id',
        'size',
        'is_public'
    ];

//    /**
//     * Get the message that owns the Asset
//     */
//    public function message()
//    {
//        return $this->belongsTo(Message::class);
//    }

    public function assetable()
    {
        return $this->morphTo();
    }

    /**
     * Get the user that owner the Asset
     *
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
