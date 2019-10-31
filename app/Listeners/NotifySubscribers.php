<?php

namespace App\Listeners;

use App\Events\ThreadReceivedNewReply;

class NotifySubscribers
{
   public function handle(ThreadReceivedNewReply $event)
    {
        $reply = $event->getReply();

        $reply->thread->subscriptions
            ->where('user_id', '!=', $reply->user_id)
            ->each
            ->notify($reply);
    }
}
