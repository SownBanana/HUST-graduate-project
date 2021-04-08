<?php
namespace App\Repositories\Message;

use App\Repositories\BaseRepository;

class BaseMessageRepository extends BaseRepository
{
    public function getModel()
    {
        return \App\Models\Message::class;
    }
}
