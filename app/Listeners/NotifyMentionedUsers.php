<?php

namespace App\Listeners;

use App\Events\ThreadReceivedNewReply;
use App\Notifications\YouWereMentioned;
use App\User;

class NotifyMentionedUsers
{
    public function handle(ThreadReceivedNewReply $event)
    {
        collect($event->getReply()->getMentionedUsers())
            ->map(function(string $name): ?User {
                return User::whereName($name)->first();
            })
            ->filter()
            ->each(function(User $user) use ($event): void {
                $user->notify(new YouWereMentioned($event->getReply()));
            })
        ;
    }
}
