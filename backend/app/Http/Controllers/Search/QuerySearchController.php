<?php

namespace App\Http\Controllers\Search;

use App\Enums\CourseType;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\Course\CourseRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuerySearchController extends Controller
{
    protected $courseRepository;

    public function __construct(CourseRepository $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request)
    {
        $instructors = User::where('role', UserRole::Instructor)
            ->where('id', '!=', Auth::id())
            ->where(function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('username', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            })
            ->take(5)->get();
        $students = User::where('role', UserRole::Student)
            ->where('id', '!=', Auth::id())
            ->where(function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('username', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            })
            ->take(5)->get();

        $courseQuery = $this->courseRepository->with('instructor')
            ->where('status', CourseType::Publish)
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
                'courses.created_at',
                'courses.updated_at'
            )
            ->selectRaw('
            avg(rate) as rate_avg,
            count(student_id) as total,
            users.id as instructor_id
            ');
        $courseQueryClone = clone $courseQuery;
        $fulltextCoursesQuery = $courseQuery
            ->whereRaw('match(courses.title, courses.introduce)
         against (\'' . $request->search . '\' with QUERY EXPANSION)');
        $likeCoursesQuery = $courseQueryClone
            ->where('title', 'like', '%' . $request->search . '%');
        $courses = $fulltextCoursesQuery->union($likeCoursesQuery)
            ->take(10)->get();
//        $courses = $this->courseRepository->with('instructor')->get();
        return response()->json([
            "status" => "success",
            "instructors" => $instructors,
            "students" => $students,
            "courses" => $courses
        ]);
    }
}
