<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    /**
     * Get the parent that owns the Topic
     *
     */
    public function parent()
    {
        //If error try to change Topic to self
        return $this->belongsTo(Topic::class, 'parent_id');
        //or https://stackoverflow.com/questions/20923773/how-to-create-self-referential-relationship-in-laravel/31824108
    }

    /**
     * Get all of the children for the Topic
     *
     */
    public function children()
    {
        //Like above
        return $this->hasMany(Topic::class, 'parent_id');
    }

    /**
     * The users that belong to the Topic
     *
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_topic');
    }
    /**
     * The courses that belong to the Topic
     *
     */
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_topic');
    }
}
