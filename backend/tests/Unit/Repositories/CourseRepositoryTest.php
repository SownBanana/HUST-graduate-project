<?php

namespace Tests\Unit\Repositories;

use App\Models\Course;
use App\Repositories\Course\CourseRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseRepositoryTest extends TestCase
{
    use RefreshDatabase;
    private $course;
    /**
     * test get model
     *
     * @return void
     */
    public function testGetModel()
    {
        $courseRepository = new CourseRepository;

        $data = $courseRepository->getModel();
        $this->assertEquals(Course::class, $data);
    }

    /**
     * test store function model
     *
     * @return void
     */
    public function testCreate()
    {
        $courseRepository = new CourseRepository;
        $params = [
            'instructor_id' => 1,
            'title' => 'Course Test',
            'introduce' => 'I\'m test',
            'price' => 100000,
        ];
        $request = new \Illuminate\Http\Request();
        $request->replace($params);
        
        $expectCourseCount = Course::all()->count() + 1;
        $expectCourseId = Course::all()->last();
        if($expectCourseId){
            $expectCourseId = $expectCourseId->id + 1;
        }else $expectCourseId = 1;
        $course = $courseRepository->create($request->all());
        // Course::create($request->all());
        $this->assertEquals($expectCourseCount, Course::all()->count());
        $this->assertEquals($expectCourseId, $course->id);
    }
}
