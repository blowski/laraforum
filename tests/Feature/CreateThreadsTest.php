<?php

namespace Tests\Feature;

use App\Thread;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateThreadsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function guests_may_not_create_threads()
    {
        $this->withoutExceptionHandling();

        $this->expectException(AuthenticationException::class);
        $this->post('/threads/', factory(Thread::class)->raw());
    }

    /** @test */
    function guests_may_not_see_the_create_thread_page(): void
    {
        $this->get('/threads/create/')
            ->assertRedirect('/login');
    }

    /** @test */
    function an_authenticated_user_can_create_new_forum_threads()
    {
        $this->signIn();

        $thread = make(Thread::class);
        $this->post('/threads/', $thread->toArray());

        $this->get('/threads/')
             ->assertSee($thread->title)
             ->assertSee($thread->body);
    }
}
