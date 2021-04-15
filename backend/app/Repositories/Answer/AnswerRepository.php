<?php
namespace App\Repositories\Answer;

use App\Repositories\BaseRepository;

class AnswerRepository extends BaseRepository
{
    public function getModel()
    {
        return \App\Models\Answer::class;
    }
}
