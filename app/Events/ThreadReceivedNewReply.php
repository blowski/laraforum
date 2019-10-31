<?php

namespace App\Events;

use App\Reply;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ThreadReceivedNewReply
{
    use Dispatchable, SerializesModels;

    private $reply;

    public function __construct(Reply $reply)
    {
        $this->reply = $reply;
    }

    public function getReply(): Reply
    {
        return $this->reply;
    }
}
