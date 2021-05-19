<?php

namespace App\Http\Controllers\Chat;

use App\Enums\RoomType;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\Message\PrivateMessageRepository;
use App\Repositories\Room\RoomRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SendPrivateChatController extends Controller
{
    private $privateMessageRepository;
    private $roomRepository;

    public function __construct(PrivateMessageRepository $privateMessageRepository, RoomRepository $roomRepository)
    {
        $this->privateMessageRepository = $privateMessageRepository;
        $this->roomRepository = $roomRepository;
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        try {
            $user = Auth::user();
            $data = $request->all();
            $data['sender_id'] = $user->id;
            $data['to'] = $data['id'];
            // broadcast(new PrivateMessageSend($data));
            $room = null;
            if (isset($request['room_id'])) {
                $room = $this->roomRepository->find($request->room_id);
            }
            if (!$room) {
                $rooms = $user->rooms()->get();
                foreach ($rooms as $r) {
                    $r->users()->where('users.id', $data['id'])->first();
                    if ($r) {
                        $room = $r;
                        dump($room->id);
                        break;
                    }
                }
            }
            if (!$room) {
                $room = $this->roomRepository->create([
                    'roomable_type'=>RoomType::ChatRoom
                ]);
                $room->users()->attach([$user->id, $data['id']]);
            }
            $data['room_id'] = $room->id;
            $this->privateMessageRepository->createWithEvent($data, $data);
            return response()->json(["status"=>"success", "room_id"=>$data['room_id']]);
        } catch (Exception $e) {
            throw $e;
            return response()->json(["status"=>"error", "message"=>$e]);
        }
    }
}
