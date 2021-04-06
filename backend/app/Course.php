<?php

namespace App;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'course_student', 'course_id', 'student_id')->withPivot('rate', 'lesson_checkpoint');
    }
}
