<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Question;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalculatePointController extends Controller
{
    public function checkMultiAnswer($resultQuestions, $answer)
    {
        foreach ($resultQuestions as $resultQuestion) {
            if ($resultQuestion->id == $answer['question_id']) {
                foreach ($resultQuestion->answers as $resultAnswer) {
                    if ($resultAnswer->id == $answer['id']) {
                        if (!isset($answer['is_check'])) $answer['is_check'] = false;
                        if ($resultAnswer->is_true xor $answer['is_check']) {
                            return false;
                        }
                    }
                }
                break;
            }
        }
        return true;
    }

    public function checkSingleAnswer($resultQuestions, $question_id, $answer_id)
    {
        foreach ($resultQuestions as $resultQuestion) {
            if ($resultQuestion->id == $question_id) {
                foreach ($resultQuestion->answers as $resultAnswer) {
                    if ($resultAnswer->is_true) {
                        if ($resultAnswer->id != $answer_id) {
                            return false;
                        }
                        break;
                    }
                }
                break;
            }
        }
        return true;
    }

    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public
    function __invoke(Request $request)
    {
        $questions = $request->questions;
        $section = Section::find($questions[0]['section_id']);
        $total = 0;
        $point = 0;
        $resultQuestions = $section->questions;
        foreach ($questions as $question) {
            $total += 1;
            if ($question['is_multi']) {
                $check = true;
                foreach ($question['answers'] as $answer) {
                    if (!$this->checkMultiAnswer($resultQuestions, $answer)) {
                        $check = false;
                        break;
                    }
                }
                if ($check) $point += 1;
            } else {
                if (isset($question['answer_id']) && $this->checkSingleAnswer($resultQuestions, $question['id'], $question['answer_id'])) {
                    $point += 1;
                }
            }
        }
        $fancyPoint = round(($point / $total), 2) * 100;
        $highestPoint = Auth::user()->sections->find($section->id)->pivot->highest_point;
        if ($highestPoint < $fancyPoint) {
            Auth::user()->sections()->updateExistingPivot($section->id, [
                'highest_point' => $fancyPoint,
                'updated_at' => now()
            ]);
        }
        return response()->json([
            "point" => $point,
            "total" => $total,
            'fancy_point' => $fancyPoint,
//            "pass_point" => $section->pass_point,
//            "is_pass" => ($point / $total) > $section->pass_point,
            "last_highest_point" => $highestPoint
        ]);
    }
}
