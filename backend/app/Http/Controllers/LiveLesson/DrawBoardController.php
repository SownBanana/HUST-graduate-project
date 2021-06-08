<?php

namespace App\Http\Controllers\LiveLesson;

use App\Http\Controllers\Controller;
use App\Models\LiveLesson;
use Illuminate\Http\Request;

class DrawBoardController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request, $id)
    {
        //Check auth
        $liveLesson = LiveLesson::find($id);
        $liveLesson->board = $request->data;
        $liveLesson->save();
        return response()->json(['status' => 'success']);
    }
}
