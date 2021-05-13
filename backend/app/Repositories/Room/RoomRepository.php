<?php
namespace App\Repositories\Room;

use App\Enums\RoomType;
use App\Repositories\BaseRepository;

class RoomRepository extends BaseRepository implements RoomRepositoryInterface
{
    public function getModel()
    {
        return \App\Models\Room::class;
    }

    public function getRoom($type = null)
    {
        if ($type == null) {
            return $this->model->all()->take(5)->get();
        } else {
            switch ($type) {
                case RoomType::ChatRoom:
                case RoomType::CourseComment:
                case RoomType::LessonComment:
                case RoomType::LiveLessonComment:
                    return $this->model->where('roomable_type', $type)->take(5)->get();
                default:
                    return null;
            }
        }
    }
}
