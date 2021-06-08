<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sender_id',
        'room_id',
        'content'
    ];

    /**
     * Get the room that owns the Message
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Get all of the mentions for the Message
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function mentions()
    {
        return $this->hasMany(Mention::class);
    }

    /**
     * Get the sender that owns the Message
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Get all of the assets for the Message
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function assets()
    {
        return $this->morphMany(Asset::class, 'assetable');
    }
//    /**
//     * Get all of the assets for the Message
//     *
//     * @return \Illuminate\Database\Eloquent\Relations\HasMany
//     */
//    public function assets()
//    {
//        return $this->hasMany(Asset::class);
//    }
}
