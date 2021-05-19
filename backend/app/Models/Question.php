<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use SoftDeletes;
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
        'uuid'
    ];

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($question) { // before delete() method call this
            $question->answers()->delete();
            // do the rest of the cleanup...
        });
    }

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
