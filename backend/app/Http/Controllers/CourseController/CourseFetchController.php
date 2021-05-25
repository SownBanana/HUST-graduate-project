<?php

namespace App\Http\Controllers\CourseController;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Repositories\Course\CourseRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CourseFetchController extends Controller
{
    protected $courseRepository;


    public function __construct(
        CourseRepository $courseRepository
    )
    {
        $this->courseRepository = $courseRepository;
    }

    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke($id)
    {
        $course = $this->courseRepository->findOrFail($id);
        $user = Auth::user();
        switch ($user->role) {
            case UserRole::Instructor:
                if ($course->instructor_id != $user->id) {
                    \abort(403);
                }
                break;
            case UserRole::Student:
                $exists = DB::table('course_student')
                        ->whereCourseId($id)
                        ->whereStudentId($user->id)
                        ->count() > 0;
                if (!$exists) {
                    \abort(403);
                }
                break;
            default:
                # Admin not check
                break;
        }
        $course = $this->courseRepository->with(['topics', 'sections', 'sections.lessons', 'sections.liveLessons', 'sections.questions.answers'])->findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $course], 200);
    }
}
