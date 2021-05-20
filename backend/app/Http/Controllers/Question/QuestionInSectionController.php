<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestionInSectionController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke($section_id)
    {
//        $user = Auth::user();
//        if ($user->sections->contains($section_id)) {
        $questions = Question::with('answers:id,uuid,question_id,content')
            ->where('section_id', $section_id)
            ->join('answers', 'questions.id', 'answers.question_id')
            ->where('is_true', 1)
            ->groupBy('questions.id')
            ->select('questions.*')
            ->selectRaw('count(*) > 1 as is_multi')
            ->get();
        return response()->json(['status' => 'success', 'data' => $questions]);
//        } else abort(403);
//        return 403;
    }
}
