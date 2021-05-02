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
        $matchThese = [];
        $matches = ['instructor_id', 'status'];
        foreach ($matches as $field) {
            if ($request->has($field)) {
                $matchThese[$field] = $request->$field;
            }
        }
        $perPage = 9;
        $columns = array('*');
        if ($request->has('perPage')) {
            $perPage = $request->perPage;
        }
        if ($request->has('columns')) {
            $columns = $request->columns;
        }
        return response()->json(['status'=>'success','data'=>$this->courseRepository->where($matchThese)->paginate($perPage, $columns)], 200);
    }


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
            if (isset($courseData['id'])) {
                $course = $this->courseRepository->update($courseData['id'], shallow_copy_array($courseData));
            } else {
                $course = $this->courseRepository->create(shallow_copy_array($courseData));
            }

            if (isset($courseData["sections"])) {
                $newSectionsData = [];
                $oldSectionsData = [];
                if (isset($courseData['id'])) {
                    foreach ($courseData["sections"] as $sectionData) {
                        if (!isset($sectionData['id'])) {
                            $newSectionsData[] = $sectionData;
                        } else {
                            $oldSectionsData[] = $sectionData;
                        }
                    }
                } else {
                    $newSectionsData = $courseData["sections"];
                }
                if (count($newSectionsData) > 0) {
                    $sections = $course->sections()->createMany(shallow_copy_array_of_array($newSectionsData));
                    for ($i=0; $i < count($sections); $i++) {
                        $section = $sections[$i];
                        $sectionData = $newSectionsData[$i];
                        $section->lessons()->createMany($sectionData['lessons']);
                        // $section->questions()->createMany(shallow_copy_array_of_array($sectionData['questions']));
                        // $section->liveLessons()->createMany(shallow_copy_array_of_array($sectionData['live_lessons']));
                    }
                }
                foreach ($oldSectionsData as $sectionData) {
                    $section = $this->sectionRepository->update($sectionData['id'], shallow_copy_array($sectionData));
                    if (isset($sectionData["lessons"])) {
                        $newLessonsData = [];
                        $oldLessonsData = [];
                        foreach ($sectionData["lessons"] as $lessonData) {
                            if (!isset($lessonData['id'])) {
                                $newLessonsData[] = $lessonData;
                            } else {
                                $oldLessonsData[] = $lessonData;
                            }
                        }
                    }
                    $section->lessons()->createMany(shallow_copy_array_of_array($newLessonsData));
                    foreach ($oldLessonsData as $lessonData) {
                        $this->lessonRepository->update($lessonData['id'], $lessonData);
                    }
                }
            }
            $this->sectionRepository->whereIn('id', $request->deleteSections)->delete();
            $this->lessonRepository->whereIn('id', $request->deleteLessons)->delete();
            DB::commit();
            return \response()->json(["status"=>"success", "course"=>$this->courseRepository->with(['sections', 'sections.lessons'])->find($course->id)]);
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
        return response()->json(['status'=>'success','data'=>$course], 200);
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
