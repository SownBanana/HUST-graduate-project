<?php

namespace App\Http\Controllers\CourseController;

use App\Enums\CourseType;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Resources\CourseResource;
use App\Models\Answer;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\LiveLesson;
use App\Models\Question;
use App\Models\Section;
use App\Repositories\Answer\AnswerRepository;
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
    protected $answerRepository;

    public function __construct(
        CourseRepository $courseRepository,
        SectionRepository $sectionRepository,
        LessonRepository $lessonRepository,
        QuestionRepository $questionRepository,
        LiveLessonRepository $liveLessonRepository,
        AnswerRepository $answerRepository
    )
    {
        $this->courseRepository = $courseRepository;
        $this->sectionRepository = $sectionRepository;
        $this->questionRepository = $questionRepository;
        $this->lessonRepository = $lessonRepository;
        $this->liveLessonRepository = $liveLessonRepository;
        $this->answerRepository = $answerRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $matchThese = [];
        $matches = ['instructor_id', 'status'];
        foreach ($matches as $field) {
            if ($request->filled($field) && $request->$field != 'vlearn_all_value') {
                $matchThese[$field] = $request->$field;
            }
        }
        $search = "";
        if ($request->has('search')) {
            $search = $request->search;
        }
        $perPage = 9;
        $columns = array('*');
        $time = "desc";
        if ($request->has('perPage')) {
            $perPage = $request->perPage;
        }
        if ($request->has('columns')) {
            $columns = $request->columns;
        }
        if ($request->has('time')) {
            $time = $request->time;
        }
        $query = $this->courseRepository
            ->with('instructor')
            ->leftJoin('course_student', 'courses.id', '=', 'course_student.course_id')
            ->groupBy('courses.id')
//            ->whereNotNull('course_student.rate')
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
            ->where($matchThese);
        if ($request->filled('statuses')) {
            $statuses = explode(',', $request->statuses);
            $query->whereIn('status', $statuses);
        }
        if ($request->filled('types')) {
            $types = explode(',', $request->types);
            $query->whereIn('type', $types);
        }
        if (!$request->filled('search')) {
            if ($request->filled('orderBy')) {
                $query->orderBy('courses.' . $request->orderBy, $request->order);
            } else {
                $query->orderBy('courses.updated_at', $time);
            }
        }
        if (Auth::user()->role == UserRole::Student) {
            $query->where('status', CourseType::Publish);
        }
//        $subQuery = clone $query;
//        $likeQuery = $subQuery->where('title', 'LIKE', '%' . $search . '%');
//        if ($request->filled('search')) {
//            $query = $query->whereRaw('match(courses.title, courses.introduce) against (\'' . $search . '\' with QUERY EXPANSION)')
//                ->union($likeQuery);
//        }
        return response()->json(['status' => 'success', 'data' =>
            $query->where('title', 'LIKE', '%' . $search . '%')->paginate($perPage, $columns)], 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
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
                // Update index of elastic search
            }

            if (isset($courseData["sections"])) {
                $newSectionsData = [];
                $oldSectionsData = [];
                if (isset($courseData['id'])) {
                    foreach ($courseData["sections"] as $sectionData) {
                        if (!isset($sectionData['id'])) {
                            if (!Section::where('uuid', $sectionData['uuid'])->first()) {
                                $newSectionsData[] = $sectionData;
                            }
                        } else {
                            $oldSectionsData[] = $sectionData;
                        }
                    }
                } else {
                    $newSectionsData = $courseData["sections"];
                }
                if (count($newSectionsData) > 0) {
                    $sections = $course->sections()->createMany(shallow_copy_array_of_array($newSectionsData));
                    for ($i = 0; $i < count($sections); $i++) {
                        $section = $sections[$i];
                        $sectionData = $newSectionsData[$i];
                        $lessons = $section->lessons()->createMany($sectionData['lessons']);
                        foreach ($lessons as $lesson) {
                            $lesson->room()->create();
                        }
                        $liveLessons = $section->liveLessons()->createMany($sectionData['live_lessons']);
                        foreach ($liveLessons as $liveLesson) {
                            $liveLesson->room()->create();
                        }
                        $section->questions()->createMany($sectionData['questions']);
                        foreach ($sectionData['questions'] as $questionData) {
                            if (isset($questionData['answers'])) {
                                $this->answerRepository->createMany($questionData['answers']);
                            }
                        }
                    }
                }
                foreach ($oldSectionsData as $sectionData) {
                    $section = $this->sectionRepository->update($sectionData['id'], shallow_copy_array($sectionData));
                    $newLessonsData = [];
                    $oldLessonsData = [];
                    $newLiveLessonsData = [];
                    $oldLiveLessonsData = [];
                    $newQuestionsData = [];
                    $oldQuestionsData = [];
                    if (isset($sectionData["lessons"])) {
                        foreach ($sectionData["lessons"] as $lessonData) {
                            if (!isset($lessonData['id'])) {
                                if (!Lesson::where('uuid', $lessonData['uuid'])->first()) {
                                    $newLessonsData[] = $lessonData;
                                }
                            } else {
                                $oldLessonsData[] = $lessonData;
                            }
                        }
                    }
                    if (isset($sectionData["live_lessons"])) {
                        foreach ($sectionData["live_lessons"] as $liveLessonData) {
                            if (!isset($liveLessonData['id'])) {
                                if (!LiveLesson::where('uuid', $liveLessonData['uuid'])->first()) {
                                    $newLiveLessonsData[] = $liveLessonData;
                                }
                            } else {
                                $oldLiveLessonsData[] = $liveLessonData;
                            }
                        }
                    }
                    if (isset($sectionData["questions"])) {
                        foreach ($sectionData["questions"] as $questionData) {
                            if (!isset($questionData['id'])) {
                                if (!Question::where('uuid', $questionData['uuid'])->first()) {
                                    $newQuestionsData[] = $questionData;
                                }
                            } else {
                                $oldQuestionsData[] = $questionData;
                            }
                        }
                    }

                    $lessons = $section->lessons()->createMany(shallow_copy_array_of_array($newLessonsData));
                    foreach ($lessons as $lesson) {
                        $lesson->room()->create();
                    }
                    foreach ($oldLessonsData as $lessonData) {
                        $this->lessonRepository->update($lessonData['id'], $lessonData);
                    }

                    $liveLessons = $section->liveLessons()->createMany(shallow_copy_array_of_array($newLiveLessonsData));
                    foreach ($liveLessons as $liveLesson) {
                        $liveLesson->room()->create();
                    }
                    foreach ($oldLiveLessonsData as $liveLessonData) {
                        $this->liveLessonRepository->update($liveLessonData['id'], $liveLessonData);
                    }

                    $section->questions()->createMany(shallow_copy_array_of_array($newQuestionsData));
                    foreach ($oldQuestionsData as $questionData) {
                        $question = $this->questionRepository->update($questionData['id'], $questionData);
                        $newAnswersData = [];
                        $oldAnswersData = [];
                        if (isset($questionData["answers"])) {
                            foreach ($questionData["answers"] as $answerData) {
                                if (!isset($answerData['id'])) {
                                    if (!Answer::where('uuid', $answerData['uuid'])->first()) {
                                        $newAnswersData[] = $answerData;
                                    }
                                } else {
                                    $oldAnswersData[] = $answerData;
                                }
                            }
                        }
                        $question->answers()->createMany($newAnswersData);
                        foreach ($oldAnswersData as $answerData) {
                            $this->answerRepository->update($answerData['id'], $answerData);
                        }
                    }
                }
            }
            $this->sectionRepository->whereIn('id', $request->deleteSections)->delete();
            $this->lessonRepository->whereIn('id', $request->deleteLessons)->delete();
            $this->liveLessonRepository->whereIn('id', $request->deleteLiveLessons)->delete();
            $this->questionRepository->whereIn('id', $request->deleteQuestions)->delete();
            $this->answerRepository->whereIn('id', $request->deleteAnswers)->delete();
            DB::commit();
            return \response()
                ->json([
                    "status" => "success",
                    "course" => $this->courseRepository
                        ->with(['topics', 'sections', 'sections.lessons', 'sections.liveLessons', 'sections.questions', 'sections.questions.answers'])
                        ->find($course->id)
                ]);
        } catch (Exception $e) {
            DB::rollBack();
//            throw $e;
            return \response(["status" => "error", "message" => $e]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $statistic = DB::table('courses')
            ->join('course_student', 'courses.id', '=', 'course_student.course_id')
            ->where('courses.id', $id)
            ->groupBy('rate')
            ->selectRaw('rate, count(rate) as rate_count')
            ->orderBy('rate')
            ->get();
        $query = $this->courseRepository
            ->with([
                'instructor',
                'sections.lessons:section_id,id,name,estimate_time',
                'sections.questions',
                'sections.liveLessons',
                'students:id,name,username,avatar_url',
                'students.sections' => function ($query) use ($id) {
                    $query->where('sections.course_id', $id)
                        ->withCount('questions');
                },
            ]);

        $bought = false;
        $sectionCheckpoint = null;
        $lessonCheckpoint = null;
        $user = Auth::user();
        try {       //remove when remigrate
            if ($user) {
                $bought = Auth::user()->boughtCourses->contains($id);
                if ($bought) {
                    $query = $query
                        ->join('course_student', 'courses.id', '=', 'course_student.course_id')
                        ->where('course_student.student_id', $user->id)
                        ->select('*', 'rate', 'comment');
                    $sectionCheckpoint = $user->boughtCourses
                        ->find($id)->pivot->section_checkpoint;
                    $lessonCheckpoint = $user->sections
                        ->find($sectionCheckpoint)->pivot->lesson_checkpoint;
                }
            }
        } catch (Exception $e) {
        }
        // $bought = Auth::user();
        return response()->json([
            'status' => 'success',
            'data' => new CourseResource($query->withCount('students AS total')->findOrFail($id)),
            'bought' => $bought,
            'statistic' => $statistic,
            'lessonCheckpoint' => $lessonCheckpoint,
            'sectionCheckpoint' => $sectionCheckpoint
        ], 200);
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
