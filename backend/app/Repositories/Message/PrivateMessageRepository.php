<?php
namespace App\Repositories\Message;

use App\Events\PrivateMessageSend;

class PrivateMessageRepository extends BaseMessageRepository implements MessageRepositoryInterface
{
    public function createWithEvent($attributes = [], $data)
    {
        event(new PrivateMessageSend($data));
        $this->create($attributes);
    }
}
