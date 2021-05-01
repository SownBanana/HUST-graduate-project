<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LiveLesson extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'section_id',
        'name',
        'schedule_time',
    ];

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
