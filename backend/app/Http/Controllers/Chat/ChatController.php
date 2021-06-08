<?php

namespace App\Http\Controllers\Chat;

use App\Enums\RoomType;
use App\Http\Controllers\Controller;
use App\Repositories\Message\PrivateMessageRepository;
use App\Repositories\Room\RoomRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    private $privateMessageRepository;
    private $roomRepository;

    public function __construct(PrivateMessageRepository $privateMessageRepository, RoomRepository $roomRepository)
    {
        $this->privateMessageRepository = $privateMessageRepository;
        $this->roomRepository = $roomRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $rooms = $this->roomRepository->with(['users', 'messages.assets'])->where('roomable_type', RoomType::ChatRoom)
            ->join('room_user', 'rooms.id', '=', 'room_user.room_id')
            ->where('room_user.user_id', Auth::id())
            ->get();
        return response()->json(["status" => "success", "data" => $rooms]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $room = $this->roomRepository->with(['users', 'messages.assets'])->find($id);
        if ($room) {
            $user = $room->users()->where('users.id', Auth::id())->first();
            if ($user) {
                return response()->json(["status" => "success", "data" => $room]);
            }
            abort(403);
        }
        return response()->json(["status" => "not found"]);
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
