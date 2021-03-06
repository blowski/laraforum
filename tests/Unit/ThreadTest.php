<?php

namespace Tests\Unit;

use App\Channel;
use App\Notifications\ThreadWasUpdated;
use App\Thread;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
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

    /** @test */
    function it_knows_if_the_authenticated_user_is_subscribed_to_it(): void
    {
        $thread = create(Thread::class);
        $this->signIn();
        self::assertFalse($thread->isSubscribedTo);
        $thread->subscribe(auth()->user());
        self::assertTrue($thread->isSubscribedTo);
    }

    /** @test */
    function it_notifies_subscribed_users_when_somebody_replies(): void
    {
        Notification::fake();
        $user = $this->signIn();
        $this->thread->subscribe($user);
        $this->thread->addReply([
            'body' => 'Foo',
            'user_id' => create(User::class),
        ]);
        Notification::assertSentTo(auth()->user(), ThreadWasUpdated::class);
    }

    /** @test */
    function a_thread_can_check_if_a_user_has_read_all_replies(): void
    {
        $user = $this->signIn();
        $thread = create(Thread::class);
        $this->assertTrue($thread->hasUpdatesFor($user));

        $user->read($thread);

        $this->assertFalse($thread->hasUpdatesFor($user));
    }
}
