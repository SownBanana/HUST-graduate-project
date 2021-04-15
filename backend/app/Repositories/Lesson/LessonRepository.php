<?php
namespace App\Repositories\Lesson;

use App\Repositories\BaseRepository;

class LessonRepository extends BaseRepository
{
    public function getModel()
    {
        return \App\Models\Lesson::class;
    }
}
