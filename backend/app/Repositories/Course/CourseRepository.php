<?php
namespace App\Repositories\Course;

use App\Repositories\BaseRepository;
use App\Repositories\Traits\Paginatable;

class CourseRepository extends BaseRepository implements CourseRepositoryInterface
{
    use Paginatable;
    //lấy model tương ứng
    public function getModel()
    {
        return \App\Models\Course::class;
    }

    public function getCourse()
    {
        return $this->model->all()->take(5);
    }
}
