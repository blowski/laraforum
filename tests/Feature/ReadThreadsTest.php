<?php

namespace Tests\Feature;

use App\Channel;
use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReadThreadsTest extends TestCase
{

    use RefreshDatabase;

    /** @var Thread */
    public $thread;

    protected function setUp(): void
    {
        parent::setUp();

        $this->thread = create(Thread::class);
    }

    /** @test */
    function a_user_can_browse_threads()
    {
        $response = $this->get('/threads');

        $response->assertStatus(200);
        $response->assertSee($this->thread->title);
    }

    /** @test */
    function a_user_can_see_a_single_thread()
    {
        $response = $this->get($this->thread->path());

        $response->assertStatus(200);
        $response->assertSee($this->thread->title);
    }

    /** @test */
    function a_user_can_filter_threads_according_to_a_tag(): void
    {
        $channel = create(Channel::class);
        $threadInChannel = create(Thread::class, ['channel_id' => $channel->id]);
        $threadNotInChannel = create(Thread::class);

        $this->get("/threads/{$channel->slug}/")
            ->assertSee($threadInChannel->title)
            ->assertDontSee($threadNotInChannel->title);
    }

    /** @test */
    function a_user_can_filter_threads_according_to_a_channel(): void
    {
        $this->signIn($johnDoe = create(User::class, ['name' => 'JohnDoe']));

        $threadByJohnDoe = create(Thread::class, ['user_id' => $johnDoe->id]);
        $threadNotByJohnDoe = create(Thread::class);

        $this->get('/threads?by=JohnDoe')
            ->assertSee($threadByJohnDoe->title)
            ->assertDontSee($threadNotByJohnDoe->title);
    }

    /** @test */
    function a_user_can_filter_threads_by_popularity(): void
    {
        $threads = create(Thread::class, [], 2);
        create(Reply::class, ['thread_id'=>$threads[0]->id], 3);
        create(Reply::class, ['thread_id'=>$threads[1]->id], 2);

        $response = $this->getJson('/threads?popular=1')->json();

        // a thread with no replies is created in setUp

        $this->assertEquals([3,2,0], array_column($response, 'replies_count'));
    }

    /** @test */
    function a_user_can_request_all_replies_for_a_given_thread(): void
    {
        $thread = create(Thread::class);
        create(Reply::class, ['thread_id' => $thread->id], 5);

        $response = $this->getJson($thread->path() . '/replies/');
        $response->assertJsonCount(5, 'data');
        $this->assertEquals(5, json_decode($response->content(), true)['total']);
    }

    /** @test */
    function a_user_can_filter_threads_by_those_that_are_unanswered(): void
    {
        $threadWithoutReplies = $this->thread;
        $threadWithReplies = create(Thread::class);
        create(Reply::class, ['thread_id' => $threadWithReplies->id]);

    $response = $this->getJson('/threads?unanswered=1');
        $response->assertJsonCount(1);
        $response->assertSee($threadWithoutReplies->body);
    }


}
