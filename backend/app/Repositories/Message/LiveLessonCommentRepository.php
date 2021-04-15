<?php
namespace App\Repositories\Message;

class LiveLessonCommentRepository extends BaseMessageRepository implements MessageRepositoryInterface
{
    public function createWithEvent($attributes = [], $data)
    {
        // event(new PrivateMessageSend($data));
        $this->create($attributes);
    }
}
