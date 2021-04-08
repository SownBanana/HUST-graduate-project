<?php
namespace App\Repositories\Message;

use App\Repositories\BaseRepository;

class MessageRepository extends BaseRepository
{
    public function getModel()
    {
        return \App\Models\Message::class;
    }
}
