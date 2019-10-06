<?php

namespace Tests\Feature;

use App\Channel;
use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Support\Facades\Auth;
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
    function authorised_users_can_delete_threads(): void
    {
        $this->signIn();

        $thread = create(Thread::class, ['user_id' => auth()->id()]);
        $reply = create(Reply::class, ['thread_id' => $thread->id]);

        $this->withoutExceptionHandling();
        $response = $this->delete($thread->path());
        $response->assertRedirect('/threads/');

        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
        $this->assertDatabaseMissing('activities', [
            'subject_id' => $thread->id,
            'subject_type' => get_class($thread),
        ]);
        $this->assertDatabaseMissing('activities', [
            'subject_id' => $reply->id,
            'subject_type' => get_class($reply),
        ]);
    }

    /** @test */
    function guests_cannot_delete_threads(): void
    {
        $thread = create(Thread::class);

        $this->expectException(AuthenticationException::class);
        $this->withoutExceptionHandling();
        $this->delete($thread->path());
    }

    /** @test */
    function authenticated_users_may_not_delete_other_users_threads(): void
    {
        $alice = create(User::class);
        $bob = create(User::class);
        $bobsThread = create(Thread::class, ['user_id' => $bob->id]);

        $this->signIn($alice);
        $this->withoutExceptionHandling();
        $this->expectException(AuthorizationException::class);

        $this->delete($bobsThread->path());
        $this->assertDatabaseHas('threads', ['id' => $bobsThread->id]);
    }


    private function publishThread(array $overrides = []): TestResponse
    {
        $thread = make(Thread::class, $overrides);
        return $this->post('/threads/', $thread->toArray());
    }
}
