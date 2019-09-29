<?php

namespace Tests\Feature;

use App\Channel;
use App\Thread;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Validation\ValidationException;
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

    /** @test */
    function a_thread_requires_a_title(): void
    {
        $this->signIn();

        $thread = make(Thread::class, ['title' => null]);

        $this->post('/threads/', $thread->toArray())
            ->assertSessionHasErrors();
    }

    /** @test */
    function a_thread_requires_a_body(): void
    {
        $this->signIn();

        $thread = make(Thread::class, ['body' => null]);

        $this->post('/threads/', $thread->toArray())
            ->assertSessionHasErrors();
    }

    /** @test */
    function a_thread_requires_a_channel(): void
    {
        $this->withoutExceptionHandling();
        $this->signIn();
        $this->expectException(ValidationException::class);

        $this->publishThread(['channel_id' => null]);
    }

    /** @test */
    function a_thread_requires_a_valid_channel(): void
    {
        factory(Channel::class, 2)->create();

        $this->withoutExceptionHandling();
        $this->signIn();
        $this->expectException(ValidationException::class);

        $this->publishThread(['channel_id' => 999]);
    }

    private function publishThread(array $overrides = []): TestResponse
    {
        $thread = make(Thread::class, $overrides);
        return $this->post('/threads/', $thread->toArray());
    }
}
