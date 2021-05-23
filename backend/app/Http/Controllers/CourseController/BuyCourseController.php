<?php

namespace App\Http\Controllers\CourseController;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Notifications\BuyCourse;
use App\Repositories\Course\CourseRepository;
use App\Repositories\Lesson\LessonRepository;
use App\Repositories\Section\SectionRepository;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class BuyCourseController extends Controller
{
    protected $courseRepository;
    protected $sectionRepository;
    protected $lessonRepository;
    protected $questionRepository;
    protected $liveLessonRepository;

    public function __construct(
        CourseRepository $courseRepository,
        SectionRepository $sectionRepository,
        LessonRepository $lessonRepository
    )
    {
        $this->courseRepository = $courseRepository;
        $this->sectionRepository = $sectionRepository;
        $this->lessonRepository = $lessonRepository;
    }

    /**
     * Handle the incoming request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke($id)
    {
        $user = Auth::user();
        if ($user->role == UserRole::Student && !$user->boughtCourses->contains($id)) {
            $course = $this->courseRepository->find($id);
            if ($course) {
                $user->boughtCourses()->attach($course->id);
                try {
                    $user->rooms()->attach($course->room->id);
                } catch (Exception $e) {
                }
                foreach ($course->sections() as $section) {
                    $user->sections()->attach($section->id, [
                        'lesson_checkpoint' => null,
                        'highest_point' => 0
                    ]);
                    foreach ($section->lessons() as $lesson) {
                        if ($lesson->room()) {
                            $user->rooms()->attach($lesson->room->id);
                        }
                    }
                }
                Notification::send($course->instructor, new BuyCourse(
                    $user,
                    $course,
                    [
                        'timestamp' => now()
                    ]
                ));
                return response()->json(["status" => "success"]);
            } else {
                return response()->json(["status" => "Not found"]);
            }
        } else {
            return response()->json(["status" => "You bought this course before"]);
        }
    }
}
