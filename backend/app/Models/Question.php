<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    /**
    * Allow all attribute are mass assignable.
    *
    * @var array
    */
    protected $guarded = [];
    
    public function section()
    {
        return $this->belongsTo(Section::class);
    }
    /**
     * Get all of the answers for the Question
     *
     */
    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
}
