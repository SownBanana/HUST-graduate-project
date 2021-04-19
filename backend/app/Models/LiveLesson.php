<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LiveLesson extends Model
{
    /**
    * Allow all attribute are mass assignable.
    *
    * @var array
    */
    protected $guarded = [];
    /**
     * Get the section that owns the LiveLesson
     *
     */
    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function room()
    {
        return $this->morphOne(Room::class, 'roomable');
    }
}
