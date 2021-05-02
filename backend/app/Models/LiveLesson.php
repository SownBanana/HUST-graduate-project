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
        'uuid'
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
