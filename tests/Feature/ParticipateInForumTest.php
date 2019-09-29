<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ParticipateInForumTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    function unauthenticated_users_may_not_reply()
    {
        $this->withoutExceptionHandling();

        $this->expectException(AuthenticationException::class);
        $this->post('/threads/foo/1/replies/', []);
    }

    /** @test */
    function an_authenticated_user_may_participate_in_forum_threads()
    {
        $this->signIn();

        /** @var Thread $thread */
        $thread = create(Thread::class);

        /** @var Reply $reply */
        $reply = make(Reply::class);

        $this->post($thread->path() . '/replies/', $reply->toArray());

        $this->get($thread->path())
             ->assertSee($reply->body);
    }

    /** @test */
    function a_reply_requires_a_body(): void
    {
        $this->withoutExceptionHandling();
        $this->signIn();
        $this->expectException(ValidationException::class);

        $thread = create(Thread::class);
        $reply = make(Reply::class, ['body' => null]);

        $this->post($thread->path().'/replies/', $reply->toArray());
    }
}
