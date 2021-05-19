<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttachTopicController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request)
    {
        $course = Course::find($request->course_id);
        if($course->instructor->id == Auth::id()){
            if(!$course->topics->contains($request->topic_id)){
                $course->topics()->attach($request->topic_id);
            }
            return response()->json(['status'=>"success",
            "data"=>Course::with(['topics','sections', 'sections.lessons', 'sections.questions', 'sections.questions.answers'])
                ->find($course->id)]);
        }else {
            return response()->json(['status'=>'fail', 'user_id'=> Auth::id()]);
        }
    }
}
