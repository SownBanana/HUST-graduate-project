<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    /**
    * Allow all attribute are mass assignable.
    *
    * @var array
    */
    protected $guarded = [];
    
    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function room()
    {
        return $this->morphOne(Room::class, 'roomable');
    }

    /**
     * Get all of the assets for the Message
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function assets()
    {
        return $this->hasMany(Asset::class);
    }
}
