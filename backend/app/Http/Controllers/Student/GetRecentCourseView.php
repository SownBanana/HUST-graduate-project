<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GetRecentCourseView extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $recentCoursesNumber = 8;
        $ids = Auth::user()->boughtCourses()
        ->orderBy('course_student.updated_at', 'desc')
        ->take($recentCoursesNumber)->get()->pluck('id');

        $query = Course::whereIn('courses.id', $ids)
        ->with('instructor')
        ->leftJoin('course_student', 'courses.id', '=', 'course_student.course_id')
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
            'courses.is_editor_choice',
            'courses.created_at',
            'courses.updated_at'
        )
        ->selectRaw('
        avg(rate) as rate_avg,
        count(student_id) as total,
        courses.instructor_id
        ');
        if(!empty($ids->toArray()))
                $query->orderByRaw('FIELD(courses.id, '.implode(',', $ids->toArray()).')');
        $courses = $query->take($recentCoursesNumber)->get();

        return response()->json([
            'status' => 'success',
            'data' => $courses
        ]);
    }
}
