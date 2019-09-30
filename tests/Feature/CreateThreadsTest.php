<?php

namespace Tests\Feature;

use App\Channel;
use App\Reply;
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

    /** @test */
    function a_thread_can_be_deleted(): void
    {
        $this->signIn();

        $thread = create(Thread::class);
        $reply = create(Reply::class, ['thread_id' => $thread->id]);

        $this->withoutExceptionHandling();
        $response = $this->json('DELETE', $thread->path());
        $response->assertStatus(204);

        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
    }

    /** @test */
    function guests_cannot_delete_threads(): void
    {
        $thread = create(Thread::class);

        $this->expectException(AuthenticationException::class);
        $this->withoutExceptionHandling();
        $this->json('DELETE', $thread->path());
    }

    private function publishThread(array $overrides = []): TestResponse
    {
        $thread = make(Thread::class, $overrides);
        return $this->post('/threads/', $thread->toArray());
    }
}
