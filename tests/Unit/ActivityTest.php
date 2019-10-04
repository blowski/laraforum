<?php
declare(strict_types=1);

namespace Tests\Unit;

use App\Activity;
use App\Reply;
use App\Thread;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_records_activity_when_a_thread_is_created(): void
    {
        $this->signIn();
        $thread = create(Thread::class);

        $this->assertDatabaseHas('activities', [
            'type' => 'created_thread',
            'user_id' => auth()->id(),
            'subject_id' => $thread->id,
            'subject_type' => Thread::class,
        ]);

        $activity = Activity::first();
        $this->assertEquals($activity->subject->id, $thread->id);
    }

    /** @test */
    function it_records_activity_when_a_reply_is_created(): void
    {
        $this->signIn();
        $reply = create(Reply::class);

        $this->assertDatabaseHas('activities', [
            'type' => 'created_thread',
            'user_id' => auth()->id(),
            'subject_id' => $reply->id,
            'subject_type' => Thread::class,
        ]);

        $activities = Activity::all();
        $this->assertCount(2, $activities);
        $this->assertEquals($activities[1]->subject->id, $reply->id);
    }

    /** @test */
    function it_fetches_a_feed_for_any_user(): void
    {
        $this->signIn();

        create(Thread::class, ['user_id' => auth()->id()], 2);
        auth()->user()->activity()->first()->update(['created_at' => Carbon::now()->subWeek()]);

        $feed = Activity::feed(auth()->user());

        $this->assertTrue($feed->keys()->contains(
            Carbon::now()->format('Y-m-d')
        ));

        $this->assertTrue($feed->keys()->contains(
            Carbon::now()->subWeek()->format('Y-m-d')
        ));
    }
}
