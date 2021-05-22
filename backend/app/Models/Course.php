<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{

    /**
     * Allow all attribute are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'course_student', 'course_id', 'student_id')->withPivot('rate', 'comment', 'section_checkpoint');
    }


    public function sections()
    {
        return $this->hasMany(Section::class)->orderBy('order');;
    }

    /**
     * The topics that belong to the User
     *
     */
    public function topics()
    {
        return $this->belongsToMany(Topic::class, 'course_topic');
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
