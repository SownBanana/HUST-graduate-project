<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BoughtCoursesController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request)
    {
        return response()->json([
            "status" => "success",
            "data" => Course::with('instructor')
                ->leftJoin('course_student', 'courses.id', '=', 'course_student.course_id')
                ->where('course_student.student_id', Auth::id())
                ->groupBy('courses.id')
                ->join('users', 'courses.instructor_id', '=', 'users.id')
                ->select(
                    'courses.id',
                    'courses.title',
                    'courses.type',
                    'courses.introduce',
                    'courses.thumbnail_url',
                    'courses.price',
                    'courses.status',
                    'courses.created_at',
                    'courses.updated_at'
                )
                ->selectRaw('
            avg(rate) as rate_avg,
            count(student_id) as total,
            users.id as instructor_id
            ')
                ->simplePaginate(10)
        ]);
    }
}
