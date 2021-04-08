<?php

namespace Tests\Unit\Traits;

use App\Models\Course;
use App\Repositories\Course\CourseRepository;
use PHPUnit\Framework\TestCase;

class PaginatableTest extends TestCase
{
    /**
    * test get model
    *
    * @return void
    */
    public function testGetModel()
    {
        $courseRepository = new CourseRepository;

        $data = $courseRepository->testModelFromTraits();
        // $data = $courseRepository->_getModel();
        $this->assertEquals(Course::class, get_class($data));
    }
}
