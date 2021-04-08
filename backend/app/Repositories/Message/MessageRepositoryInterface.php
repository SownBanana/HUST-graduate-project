<?php
namespace App\Repositories\Message;

interface MessageRepositoryInterface
{
    public function createWithEvent($attributes = [], $data);
}
