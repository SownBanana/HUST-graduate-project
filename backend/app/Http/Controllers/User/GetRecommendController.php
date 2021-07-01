<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

class GetRecommendController extends Controller
{

    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke($id)
    {
        $recommendsNumber = 8;
        if ($id) {
            $client = new Client([
                'base_uri' => config('app.recommend_url')
            ]);
            try {
                $response = $client->request('GET', 'api/users/' . $id);
                $data = json_decode($response->getBody());
            } catch (Exception $e) {
                $data = [];
            }
            $rcmCoursesQuery = Course::with('instructor')
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
                users.id as instructor_id
                ')
                ->whereIn('courses.id', $data);
            if (!empty($data)) {
                $rcmCoursesQuery->orderByRaw('FIELD(courses.id, '.implode(',', $data).')');
            }
            $rcmCourses = $rcmCoursesQuery->take($recommendsNumber)->get();

            $leftOvers = $recommendsNumber - count($rcmCourses);
            $highRateCourse =
                Course::with('instructor')
                    ->leftJoin('course_student', 'courses.id', '=', 'course_student.course_id')
                    ->groupBy('courses.id')
                    ->havingRaw('COUNT(CASE course_student.student_id WHEN '.$id.' THEN 1 END) = 0')
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
                    users.id as instructor_id
                    ')
                    ->orderBy('rate_avg', 'desc')->take($leftOvers)->get();
            $courses = $rcmCourses->merge($highRateCourse);
        } else {
            $courses = Course::with('instructor')
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
            users.id as instructor_id
            ')
            ->orderBy('rate_avg', 'desc')->take($recommendsNumber)->get();
        }
        return response()->json([
            'status' => 'success',
            'data' => $courses
        ]);
    }
}
