<?php

namespace App\Http\Controllers\Chat;

use App\Enums\CourseType;
use App\Events\LessonCommentEvent;
use App\Events\LiveLessonCommentEvent;
use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\Message;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request)
    {
        try {
            $user = Auth::user();
            $data = $request->all();
            $data['sender_id'] = $user->id;
            $message = Message::create($data);
            $assetIds = $request->assets;
            $data['assets'] = [];
            foreach ($assetIds as $assetId) {
                $asset = Asset::find($assetId);
                $message->assets()->save($asset);
                $data['assets'][] = $asset;
            }
            if ($request->type === CourseType::LIVE) {
                event(new LiveLessonCommentEvent($data));
            } else {
                event(new LessonCommentEvent($data));
            }
            return response()->json(["status" => "success", "room_id" => $data['room_id']]);
        } catch (Exception $e) {
//            throw $e;
            return response()->json(["status" => "error", "message" => $e]);
        }
    }
}
