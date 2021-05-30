<?php

namespace App\Http\Controllers\Lesson;

use App\Enums\RoomType;
use App\Http\Controllers\Controller;
use App\Repositories\Room\RoomRepository;

class FetchLessonChatController extends Controller
{
    private $roomRepository;

    public function __construct(RoomRepository $roomRepository)
    {
        $this->roomRepository = $roomRepository;
    }

    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke($id)
    {
        $rooms = $this->roomRepository->with(['users', 'messages.sender', 'messages.assets'])
            ->where('roomable_type', RoomType::LessonComment)
            ->where('roomable_id', $id)
            ->first();
        return response()->json(["status" => "success", "data" => $rooms]);
    }
}
