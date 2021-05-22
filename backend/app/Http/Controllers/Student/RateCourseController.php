<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use League\Flysystem\Exception;

class RateCourseController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request)
    {
        $user = Auth::user();
        if ($request->filled('rate')) {
            $user->boughtCourses()->updateExistingPivot($request->course_id, [
                'rate' => $request->rate,
            ]);
        }
        if ($request->filled('comment')) {
            $user->boughtCourses()->updateExistingPivot($request->course_id, [
                'comment' => $request->comment,
            ]);
        }

        $statistic = DB::table('courses')
            ->join('course_student', 'courses.id', '=', 'course_student.course_id')
            ->where('courses.id', $request->course_id)
            ->groupBy('rate')
            ->selectRaw('rate, count(rate) as rate_count')
            ->orderBy('rate')
            ->get();
        $course = Course::with([
            'instructor',
            'sections.lessons:section_id,id,name,estimate_time',
            'sections.questions',
            'sections.liveLessons',
            'students',
        ])
            ->join('course_student', 'courses.id', '=', 'course_student.course_id')
            ->where('course_student.student_id', Auth::id())
            ->select('*', 'rate', 'comment')
            ->findOrFail($request->course_id);
        // $bought = Auth::user();
        return response()->json([
            'status' => 'success',
            'data' => new CourseResource($course),
            'bought' => true,
            'statistic' => $statistic,
        ], 200);
    }
}
