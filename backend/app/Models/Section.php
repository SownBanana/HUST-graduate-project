<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    /**
    * Allow all attribute are mass assignable.
    *
    * @var array
    */
    protected $guarded = [];
    
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }


    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function liveLessons()
    {
        return $this->hasMany(LiveLesson::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'section_student', 'section_id', 'student_id')->withPivot('lesson_checkpoint', 'highest_point');
    }
}
