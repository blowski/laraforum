<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MentionUsersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function mentioned_users_in_a_reply_are_notified(): void
    {
        $this->withoutExceptionHandling();

        /** @var User $alice */
        $alice = create(User::class, [
            'name' => 'alice',
        ]);
        /** @var User $bob */
        $bob = create(User::class, [
            'name' => 'bob',
        ]);

        $this->signIn($alice);

        /** @var Thread $thread */
        $thread = create(Thread::class);

        /** @var Reply $reply */
        $reply = make(Reply::class, [
            'body' => 'Hey @bob @alice look at this'
        ]);

        $this->postJson(
            $thread->path() . '/replies',
            $reply->toArray()
        );

        $this->assertCount(1, $bob->notifications);
    }
}
