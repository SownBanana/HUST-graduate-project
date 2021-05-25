<?php

namespace App\Http\Controllers\Chat;

use App\Enums\RoomType;
use App\Http\Controllers\Controller;
use App\Repositories\Message\PrivateMessageRepository;
use App\Repositories\Room\RoomRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GetRoomController extends Controller
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
        $user = Auth::user();
        $room = null;
        $type = 'old';
        $rooms = $user->rooms()->where('roomable_type', RoomType::ChatRoom)->get();
//        dump($id);
        foreach ($rooms as $r) {
            $friend = $r->users()->where('users.id', $id)->first();
            if ($friend) {
                $room = $r;
                break;
            }
        }
        if (!$room) {
            $type = 'new';
            $room = $this->roomRepository->create([
                'roomable_type' => RoomType::ChatRoom
            ]);
            $room->users()->attach([$user->id, $id]);
        }

        return response()->json([
            'status' => 'success',
            'type' => $type,
            'id' => $room->id,
            'data' => $this->roomRepository->with(['users', 'messages'])->where('id', $room->id)->get()
        ]);
    }
}
