<?php
namespace App\Repositories\Message;

class CourseCommentRepository extends BaseMessageRepository implements MessageRepositoryInterface
{
    public function createWithEvent($attributes = [], $data)
    {
        // event(new PrivateMessageSend($data));
        $this->create($attributes);
    }
}
