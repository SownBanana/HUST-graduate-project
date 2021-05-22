<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Section extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'course_id',
        'order',
        'name',
        'uuid',
        'question_duration',
        'start_time',
        'end_time',
        'pass_point',
        'question_step'
    ];

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($section) { // before delete() method call this
            $section->lessons()->delete();
            $section->questions()->delete();
            $section->liveLessons()->delete();
            // do the rest of the cleanup...
        });
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('order');
    }


    public function questions()
    {
        return $this->hasMany(Question::class)->orderBy('order');
    }

    public function liveLessons()
    {
        return $this->hasMany(LiveLesson::class)->orderBy('order');
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'section_student', 'section_id', 'student_id')->withPivot('lesson_checkpoint', 'highest_point', 'updated_at');
    }
}
