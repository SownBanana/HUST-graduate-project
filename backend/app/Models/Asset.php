<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'url',
        'type',
        'owner_id',
        'lesson_id',
        'message_id',
        'course_id',
        'is_public'
    ];

    /**
     * Get the message that owns the Asset
     */
    public function message()
    {
        return $this->belongsTo(Message::class);
    }

    /**
     * Get the lesson that owns the Asset
     *
     */
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    /**
     * Get the lesson that owns the Asset
     *
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the user that owner the Asset
     *
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
