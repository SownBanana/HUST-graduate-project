<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $primaryKey = 'user_id';
    protected $fillable = [
        'receive_email', 'receive_notification', 'receive_course_change', 'receive_flower_new_course'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
