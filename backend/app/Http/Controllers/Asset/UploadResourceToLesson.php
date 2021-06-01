<?php

namespace App\Http\Controllers\Asset;

use App\Enums\CourseType;
use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\Lesson;
use App\Models\LiveLesson;
use Illuminate\Http\Request;

class UploadResourceToLesson extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request)
    {
        if ($request->type === CourseType::LIVE) {
            $lesson = LiveLesson::find($request->lesson_id);
        } else {
            $lesson = Lesson::find($request->lesson_id);
        }
        $assetIds = $request->assets;
        foreach ($assetIds as $assetId) {
            $asset = Asset::find($assetId);
            $lesson->assets()->save($asset);
        }
        if ($request->type === CourseType::LIVE) {
            return response()->json(["status" => "success", "data" => LiveLesson::with('assets.owner:id,name,username')->find($request->lesson_id)]);
        } else {
            return response()->json(["status" => "success", "data" => Lesson::with('assets.owner:id,name,username')->find($request->lesson_id)]);
        }
    }
}
