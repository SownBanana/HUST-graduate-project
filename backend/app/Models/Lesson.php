<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function room()
    {
        return $this->morphOne(Room::class, 'roomable');
    }
}
