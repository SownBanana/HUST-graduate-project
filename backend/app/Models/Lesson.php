<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lesson extends Model
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
        'estimate_time',
        'video_url',
        'content',
        'order',
        'uuid'
    ];

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
