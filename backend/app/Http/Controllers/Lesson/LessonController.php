<?php

namespace App\Http\Controllers\Lesson;

use App\Http\Controllers\Controller;
use App\Repositories\Lesson\LessonRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LessonController extends Controller
{
    protected $lessonRepository;

    public function __construct(
        LessonRepository $lessonRepository
    )
    {
        $this->lessonRepository = $lessonRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $lesson = $this->lessonRepository->find($id);
        $section = $lesson->section;
        $course = $lesson->section->course;
        $user = Auth::user();
        if ($user->boughtCourses->contains($course->id)) {
            $user->boughtCourses()->updateExistingPivot($course->id, [
                'section_checkpoint' => $section->id,
            ]);
            if (!$user->sections->contains($section->id)) {
                // Delete this condition after remigrate
                $user->sections()->attach($section->id, [
                    'lesson_checkpoint' => $lesson->id,
                    'highest_point' => 0
                ]);
            } else {
                $user->sections()->updateExistingPivot($section->id, [
                    'lesson_checkpoint' => $lesson->id,
                ]);
            }
            return response()->json(["status" => "success", "data" => $lesson]);
        } elseif ($user->ownerCourses->contains($course->id)) {
            return response()->json(["status" => "success", "data" => $lesson]);
        } else {
            return response()->json(["status" => "fail"]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
