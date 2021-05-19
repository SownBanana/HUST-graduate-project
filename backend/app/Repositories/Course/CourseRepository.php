<?php
namespace App\Repositories\Course;

use App\Repositories\BaseRepository;
use App\Repositories\Room\RoomRepository;
use App\Repositories\Traits\Paginatable;

class CourseRepository extends BaseRepository implements CourseRepositoryInterface
{
    use Paginatable;

    protected $roomRepository;

    public function __construct(RoomRepository $roomRepository)
    {
        $this->roomRepository = $roomRepository;
        parent::__construct();
    }

    //lấy model tương ứng
    public function getModel()
    {
        return \App\Models\Course::class;
    }

    public function create($attributes = [])
    {
        $course = parent::create($attributes);
        $course->room()->save($this->roomRepository->create());
        return $course;
    }

    public function getCourse()
    {
        return $this->model->all()->take(5);
    }
}
