<?php
namespace App\Repositories\Question;

use App\Repositories\BaseRepository;

class QuestionRepository extends BaseRepository
{
    public function getModel()
    {
        return \App\Models\Question::class;
    }
}
