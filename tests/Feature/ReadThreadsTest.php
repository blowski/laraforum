<?php

namespace Tests\Feature;

use App\Channel;
use App\Reply;
use App\Thread;
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

        $this->thread = factory(Thread::class)->create();
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
    function a_user_can_read_replies_that_are_associated_with_a_thread()
    {
        $reply = create(Reply::class, ['thread_id'=>$this->thread->id]);
        $response = $this->get($this->thread->path());

        $response->assertStatus(200);
        $response->assertSee($reply->body);
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




}
