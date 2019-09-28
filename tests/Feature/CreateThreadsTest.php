<?php

namespace Tests\Feature;

use App\Thread;
use App\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateThreadsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function guests_may_not_create_threads()
    {
        $this->expectException(AuthenticationException::class);
        $this->post('/threads/', factory(Thread::class)->raw());
    }

    /** @test */
    function an_authenticated_user_can_create_new_forum_threads()
    {
        $this->actingAs($user = factory(User::class)->create());

        $thread = factory(Thread::class)->raw();
        $this->post('/threads/', $thread);

        $this->get('/threads/')
             ->assertSee($thread['title'])
             ->assertSee($thread['body']);
    }
}
