<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
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