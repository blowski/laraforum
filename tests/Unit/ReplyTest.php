<?php

namespace Tests\Unit;

use App\Reply;
use App\Thread;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReplyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_has_an_owner()
    {
        $thread = create(Thread::class);
        $this->assertInstanceOf(User::class, $thread->creator);
    }

    /** @test */
    function it_knows_if_it_was_just_published(): void
    {
        $reply = create(Reply::class);
        $this->assertTrue($reply->wasJustPublished());

        $reply = create(Reply::class, [
            'created_at' => Carbon::now()->subMonth(),
        ]);
        $this->assertFalse($reply->wasJustPublished());
    }

    /** @test */
    function it_can_detect_all_mentioned_users_in_the_body(): void
    {
        $reply = create(Reply::class, [
            'body' => '@alice wants to talk to @bob'
        ]);
        $mentioendUsers = $reply->getMentionedUsers();
        $this->assertCount(2, $mentioendUsers);
        $this->assertContains('alice', $mentioendUsers);
        $this->assertContains('bob', $mentioendUsers);

    }
}
