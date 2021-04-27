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
        return $this->model->all()->take(5)->get();
    }

    public function create($attributes = [])
    {
        
        $course = $this->model->create($attributes);
        // $course->sections()->createMany(
        //     $attributes['sections']
        // );
        // foreach ($course->sections() as $section ) {
        //     $section->le
        // }
        return $course;
    }
}
