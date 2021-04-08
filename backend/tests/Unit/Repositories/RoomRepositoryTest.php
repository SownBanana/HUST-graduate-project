<?php

namespace Tests\Unit\Repositories;

use App\Models\Course;
use App\Models\Room;
use App\Repositories\Course\CourseRepository;
use App\Repositories\Room\RoomRepository;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoomRepositoryTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testAddMorph()
    {
        $roomRepository = new RoomRepository;
        $params = [
                'name' => 'test room',
            ];
        $expectCourseCount = Room::all()->count() + 1;
        $room = $roomRepository->create($params);
        $this->assertEquals($expectCourseCount, Room::all()->count());
    
        $courseRepository = new CourseRepository;
        $params = [
                'instructor_id' => 1,
                'title' => 'Course Test',
                'introduce' => 'I\'m test',
                'price' => 100000,
            ];
        $expectCourseCount = Course::all()->count() + 1;
        $course = $courseRepository->create($params);
        $this->assertEquals($expectCourseCount, Course::all()->count());
        
        $course->room()->save($room);
        $this->assertEquals($room->id, $course->room->id);
        $this->assertEquals($course->id, $room->roomable->id);
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testRoomMorph()
    {
        $this->assertTrue(true);
    }
}
