<?php

namespace App\Http\Controllers\CourseController;

use App\Http\Controllers\Controller;
use App\Repositories\Course\CourseRepository;
use App\Repositories\Lesson\LessonRepository;
use App\Repositories\LiveLesson\LiveLessonRepository;
use App\Repositories\Question\QuestionRepository;
use App\Repositories\Section\SectionRepository;
use Illuminate\Http\Request;

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
        LiveLessonRepository $liveLessonRepository,
    )
    {
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
        $courseData = $request->all();
        $course = $this->courseRepository->create($courseData);
        if(array_key_exists("sections", $courseData)){
            $sectionsData = get_children_data_from_array($course, $courseData, 'sections', 'course_id');
            // $sectionsData = array_map(function($section) use ($course){
            //     return [...$section, 'course_id' => $course->id];
            // }, $courseData['sections']);
            $sections = $this->sectionRepository->createMany($sectionsData);
            for ($i=0; $i < count($sections); $i++) { 
                $section = $sections[$i];
                $sectionData = $sectionsData[$i];
                // if(array_key_exists("lessons", $sectionData)){
                //     $lessonsData = array_map(function($lesson) use ($section){
                //         return [...$lesson, 'section_id' => $section->id];
                //     }, $sectionData['lessons']);
                //     $lessons = $this->lessonRepository->createMany($lessonsData);
                // }
                $lessonsData = get_children_data_from_array($section, $sectionData, 'lessons', 'section_id');
                $this->lessonRepository->createMany($lessonsData);
                $questionData = get_children_data_from_array($section, $sectionData, 'questions', 'section_id');
                $this->questionRepository->createMany($questionData);
                $liveLessonData = get_children_data_from_array($section, $sectionData, 'live_lessons', 'section_id');
                $this->liveLessonRepository->createMany($liveLessonData);
            }

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
