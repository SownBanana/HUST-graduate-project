<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Notifications\EditorChoiceCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class SetEditorChoiceController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request)
    {
        $course = Course::findOrFail($request->id);
        $course->update([
            'is_editor_choice' => $request->choice
        ]);
        Notification::send($course->instructor, new EditorChoiceCourse($course));
        return response()->json(['status' => 'success']);
    }
}
