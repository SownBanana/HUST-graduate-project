<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Http\Resources\CourseScheduleResource;
use App\Http\Resources\ScheduleResource;
use App\Http\Resources\SectionScheduleResource;
use App\Models\Course;
use App\Models\LiveLesson;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FetchSchedule extends Controller
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
        $rs = [];
        if ($user->role == UserRole::Instructor) {
            $courses = $user->ownerCourses;
            $courseIds = $courses->pluck('id');

            $sections = Section::whereIn('course_id', $courseIds)->get();
            $sectionIds = $sections->pluck('id');

            $liveLessons = LiveLesson::with('section:id,course_id', 'section.course:id')->select('id', 'name', 'start_time', 'end_time', 'section_id')
                ->whereIn('section_id', $sectionIds)->get();

        } else if ($user->role == UserRole::Admin) {
            $courses = Course::get();
            $courseIds = $courses->pluck('id');

            $sections = Section::whereIn('course_id', $courseIds)->get();
            $sectionIds = $sections->pluck('id');

            $liveLessons = LiveLesson::with('section:id,course_id', 'section.course:id')->select('id', 'name', 'start_time', 'end_time', 'section_id')
                ->whereIn('section_id', $sectionIds)->get();
        } else {
            $liveLessons = LiveLesson::with('section:id,course_id', 'section.course:id')->select('id', 'name', 'start_time', 'end_time', 'section_id')->whereIn('section_id', function ($query) use ($user) {
                $query->select('sections.id')->from('sections')
                    ->whereIn('course_id', function ($query) use ($user) {
                        $query->select('course_id')->from('course_student')
                            ->where('student_id', $user->id);
                    });
            })->get();

            $courses = $user->boughtCourses;
            $courseIds = $courses->pluck('id');

            $sections = Section::whereIn('course_id', $courseIds)->get();
            $sectionIds = $sections->pluck('id');

            $liveLessons = LiveLesson::with('section:id,course_id', 'section.course:id')->select('id', 'name', 'start_time', 'end_time', 'section_id')
                ->whereIn('section_id', $sectionIds)->get();
        }
        return response()->json([
            'status' => 'success',
            'schedules' => ScheduleResource::collection($liveLessons),
            'courseResources' => CourseScheduleResource::collection($courses),
            'sectionResources' => SectionScheduleResource::collection($sections)
        ]);
    }
}
