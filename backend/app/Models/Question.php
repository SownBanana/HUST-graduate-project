<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'section_id',
        'question',
        'type',
        'order',
    ];

    
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
