<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Auth\Access\AuthorizationException;
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

        $this->assertDatabaseHas('replies', [
            'body' => $reply->body,
        ]);
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

    /** @test */
    function authenticated_users_may_delete_their_own_replies(): void
    {
        $this->signIn();
        $reply = create(Reply::class, ['user_id' => auth()->id()]);

        $reply->thread->path();

        $this->delete("/replies/{$reply->id}/")->assertSuccessful();
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
    }

    /** @test */
    function users_may_not_delete_other_users_replies(): void
    {
        $bob = create(User::class);
        $alice = create(User::class);
        $reply = create(Reply::class, ['user_id' => $alice->id]);

        $this->withoutExceptionHandling();
        $this->expectException(AuthorizationException::class);

        $this->signIn($bob);
        $this->delete("/replies/{$reply->id}/");
    }

    /** @test */
    function unauthenticated_users_may_not_delete_replies(): void
    {
        $reply = create(Reply::class);

        $this->withoutExceptionHandling();
        $this->expectException(AuthenticationException::class);
        $this->delete("/replies/{$reply->id}/");

        $this->assertDatabaseHas('replies', ['id' => $reply->id,]);
    }

    /** @test */
    function users_can_update_their_own_replies(): void
    {
        $this->signIn();
        $reply = create(Reply::class, ['user_id' => auth()->id()]);

        $this->patch("/replies/{$reply->id}/", [
            'body' => 'Changed body',
        ])->assertSuccessful();
        $this->assertDatabaseHas('replies', [
            'id' => $reply->id,
            'body' => 'Changed body',
        ]);
    }

    /** @test */
    function unauthenticated_users_cannot_update_replies(): void
    {
        $this->withoutExceptionHandling();
        $this->expectException(AuthenticationException::class);
        $reply = create(Reply::class);

        $this->patch("/replies/1/", [
            'body' => 'Changed body',
        ]);
        $this->assertDatabaseMissing('replies', [
            'id' => $reply->id,
            'body' => 'Changed body',
        ]);
    }

    /** @test */
    function users_cannot_update_other_users_replies(): void
    {
        $this->withoutExceptionHandling();
        $this->expectException(AuthorizationException::class);

        $alice = create(User::class);
        $bob = create(User::class);
        $alicesReply = create(Reply::class, ['user_id' => $alice->id]);

        $this->signIn($bob);
        $this->patch("/replies/{$alicesReply->id}/", [
            'body' => 'Changed body',
        ]);
        $this->assertDatabaseMissing('replies', [
            'id' => $alicesReply->id,
            'body' => 'Changed body',
        ]);
    }
}
