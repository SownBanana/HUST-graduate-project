<?php

namespace App\Http\Controllers\CourseController;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Repositories\Course\CourseRepository;
use App\Repositories\Lesson\LessonRepository;
use App\Repositories\LiveLesson\LiveLessonRepository;
use App\Repositories\Question\QuestionRepository;
use App\Repositories\Section\SectionRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CourseResourceController extends Controller
{
    protected $courseRepository;
    protected $sectionRepository;
    protected $lessonRepository;
    protected $questionRepository;
    protected $liveLessonRepository;

    public function __construct(
        CourseRepository $courseRepository,
        SectionRepository $sectionRepository,
        LessonRepository $lessonRepository,
        QuestionRepository $questionRepository,
        LiveLessonRepository $liveLessonRepository
    ) {
        $this->courseRepository = $courseRepository;
        $this->sectionRepository = $sectionRepository;
        $this->questionRepository = $questionRepository;
        $this->lessonRepository = $lessonRepository;
        $this->liveLessonRepository = $liveLessonRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return response()->json(['data'=>$this->courseRepository->paginate($request->perPage, $request->colums)], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    // public function getChildrenData($parentEloquent, $parentData, $childrenKey, $parentRelationName){
    //     return array_map(function($child) use ($parentEloquent, $parentRelationName){
    //         return [...$child, $parentRelationName => $parentEloquent->id];
    //     }, $parentData[$childrenKey]);
    // }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $courseData = $request->course;
        // dump($courseData);
        
        DB::beginTransaction();
        try {
            $courseData['instructor_id'] = Auth::user()->id;
            $course = $this->courseRepository->create(shallow_copy_array($courseData));

            if (isset($courseData["sections"])) {
                $sections = $course->sections()->createMany(shallow_copy_array_of_array($courseData["sections"]));
                for ($i=0; $i < count($sections); $i++) {
                    $section = $sections[$i];
                    $sectionData = $courseData["sections"][$i];

                    $section->lessons()->createMany(shallow_copy_array_of_array($sectionData['lessons']));
                    // $section->questions()->createMany(shallow_copy_array_of_array($sectionData['questions']));
                    // $section->liveLessons()->createMany(shallow_copy_array_of_array($sectionData['live_lessons']));
                }
            }
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $course = $this->courseRepository->findOrFail($id);
        return response()->json(['data'=>$course], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
