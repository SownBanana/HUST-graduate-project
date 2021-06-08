<?php
namespace App\Repositories\Lesson;

use App\Repositories\BaseRepository;
use App\Repositories\Room\RoomRepository;

class LessonRepository extends BaseRepository
{
    protected $roomRepository;

    public function __construct(RoomRepository $roomRepository)
    {
        $this->roomRepository = $roomRepository;
        parent::__construct();
    }

    public function getModel()
    {
        return \App\Models\Lesson::class;
    }

    public function create($attributes = [])
    {
        $lesson = parent::create($attributes);
        $lesson->room()->save($this->roomRepository->create());
        return $lesson;
    }
}
