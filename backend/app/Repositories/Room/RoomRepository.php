<?php
namespace App\Repositories\Room;

use App\Repositories\BaseRepository;

class RoomRepository extends BaseRepository implements RoomRepositoryInterface
{
    public function getModel()
    {
        return \App\Models\Room::class;
    }

    public function getRoom()
    {
        return $this->model->all()->take(5)->get();
    }
}
