<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ParticipateInForumTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    function unauthenticated_users_may_not_reply()
    {
        $response = $this->post('/threads/1/replies', []);
        $response->assertRedirect('/login');
    }

    /** @test */
    function an_authenticated_user_may_participate_in_forum_threads()
    {
        $this->be($user = factory(User::class)->create());

        /** @var Thread $thread */
        $thread = factory(Thread::class)->create();

        /** @var Reply $reply */
        $reply = factory(Reply::class)->make();

        $this->post($thread->path() . '/replies/', $reply->toArray());

        $this->get($thread->path())
             ->assertSee($reply->body);
    }
}
