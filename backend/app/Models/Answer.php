<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Answer extends Model
{
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'question_id',
        'content',
        'is_true',
        'uuid'
    ];
    /**
     * Get the question t the Answer
     *
     *
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
