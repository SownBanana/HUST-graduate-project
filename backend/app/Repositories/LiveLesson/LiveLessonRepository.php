<?php
namespace App\Repositories\LiveLesson;

use App\Repositories\BaseRepository;

class LiveLessonRepository extends BaseRepository
{
    public function getModel()
    {
        return \App\Models\LiveLesson::class;
    }
}
