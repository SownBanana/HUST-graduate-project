<?php

namespace App\Repositories\Message;

use App\Events\PrivateMessageSend;
use App\Models\Asset;

class PrivateMessageRepository extends BaseMessageRepository implements MessageRepositoryInterface
{
    public function createWithEvent($attributes = [], $data)
    {

        $message = $this->create($attributes);
        $assetIds = $data['assets'];
        $data['assets'] = [];
        foreach ($assetIds as $assetId) {
            $asset = Asset::find($assetId);
            $message->assets()->save($asset);
            $data['assets'][] = $asset;
        }
        event(new PrivateMessageSend($data));
        return $message;
    }
}
