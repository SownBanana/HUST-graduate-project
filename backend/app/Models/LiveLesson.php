<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LiveLesson extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'section_id',
        'name',
        'schedule_time',
        'uuid',
        'order',
        'start_time',
        'end_time',
        'content'
    ];

    /**
     * Get the section that owns the LiveLesson
     *
     */
    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id', 'id');
    }

    public function room()
    {
        return $this->morphOne(Room::class, 'roomable');
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
}
