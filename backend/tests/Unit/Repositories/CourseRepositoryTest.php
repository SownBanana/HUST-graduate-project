<?php

namespace Tests\Unit\Repositories;

use App\Models\Course;
use App\Repositories\Course\CourseRepository;
use Tests\TestCase;

class CourseRepositoryTest extends TestCase
{
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
        $result = $courseRepository->create($request->all());
        // Course::create($request->all());
        $this->assertEquals($expectCourseCount, Course::all()->count());
    }
}
