<?php
declare(strict_types=1);

namespace Tests\Feature;

use App\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class SubscribeToThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function a_user_can_subscribe_to_threads(): void
    {
        $this->signIn();
        $thread = create(Thread::class);
        $this
            ->withoutExceptionHandling()
            ->post("{$thread->path()}/subscriptions/")
            ->assertSuccessful()
        ;
        self::assertCount(1, $thread->subscriptions);
    }
}
