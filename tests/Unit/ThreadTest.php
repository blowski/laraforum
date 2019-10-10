<?php

namespace Tests\Unit;

use App\Channel;
use App\Thread;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ThreadTest extends TestCase
{
    use RefreshDatabase;

    /** @var Thread */
    private $thread;

    protected function setUp(): void
    {
        parent::setUp();

        $this->thread = create(Thread::class);
    }

    /** @test */
    function a_thread_has_replies()
    {
        $this->assertInstanceOf(Collection::class, $this->thread->replies);
    }

    /** @test */
    function a_thread_has_a_creator()
    {
        $this->assertInstanceOf(User::class, $this->thread->creator);
    }

    /** @test */
    function a_thread_can_add_a_reply()
    {
        $this->thread->addReply([
            'body' => 'Foobar',
            'user_id' => 1,
        ]);

        $this->assertCount(1, $this->thread->replies);
    }

    /** @test */
    function it_belongs_to_a_channel(): void
    {
        $thread = create(Thread::class);

        $this->assertInstanceOf(Channel::class, $thread->channel);
    }

    /** @test */
    function it_can_make_a_string_path(): void
    {
        $thread = create(Thread::class);

        self::assertEquals('/threads/'.$thread->channel->slug.'/'.$thread->id, $thread->path());
    }

    /** @test */
    function a_thread_can_be_subscribed_to(): void
    {
        $thread = create(Thread::class);
        $user = $this->signIn();

        $thread->subscribe($user);
        $this->assertEquals(1, $thread->subscriptions()->where(['user_id' => $user->id])->count());
    }

    /** @test */
    function a_thread_can_be_unsubscribed_from(): void
    {
        $thread = create(Thread::class);
        $user = $this->signIn();

        $thread->subscribe($user);
        $thread->unsubscribe($user);

        $this->assertEquals(0, $thread->subscriptions()->where(['user_id' => $user->id])->count());
    }
}
