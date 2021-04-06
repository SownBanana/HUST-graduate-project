<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
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
}