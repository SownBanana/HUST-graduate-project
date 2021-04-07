<?php
namespace App\Repositories\Course;

use App\Repositories\BaseRepository;

class CourseRepository extends BaseRepository implements CourseRepositoryInterface
{
    //lấy model tương ứng
    public function getModel()
    {
        return \App\Models\Course::class;
    }

    public function getCourse()
    {
        return $this->model->all()->take(5)->get();
    }
}
