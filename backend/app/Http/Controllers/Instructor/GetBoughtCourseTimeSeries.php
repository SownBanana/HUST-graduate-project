<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GetBoughtCourseTimeSeries extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request)
    {
        $data = Course::whereInstructorId(Auth::id())
            ->join('course_student', 'courses.id', '=', 'course_student.course_id')
            ->whereNotNull('course_student.created_at')
            ->selectRaw('course_student.created_at as time, 1 as data')
            ->get();
        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }
}
