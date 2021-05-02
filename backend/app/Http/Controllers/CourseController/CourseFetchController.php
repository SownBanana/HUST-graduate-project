<?php

namespace App\Http\Controllers\CourseController;

use App\Http\Controllers\Controller;
use App\Repositories\Course\CourseRepository;
use Illuminate\Http\Request;

class CourseFetchController extends Controller
{
    protected $courseRepository;


    public function __construct(
        CourseRepository $courseRepository
    ) {
        $this->courseRepository = $courseRepository;
    }
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke($id)
    {
        $course = $this->courseRepository->with(['sections', 'sections.lessons'])->findOrFail($id);
        return response()->json(['status'=>'success','data'=>$course], 200);
    }
}
