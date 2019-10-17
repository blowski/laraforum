<?php

namespace Tests\Feature;

use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_notification_is_prepared_when_a_subscribed_thread_receives_a_new_reply_that_is_not_by_the_current_user(): void
    {
        $user = $this->signIn();

        $thread = create(Thread::class)->subscribe($user);

        self::assertCount(0, $user->notifications);
        $thread->addReply([
            'user_id' => auth()->id(),
            'body' => 'Some reply here',
        ]);
        self::assertCount(0, $user->fresh()->notifications);

        $thread->addReply([
            'user_id' => create(User::class)->id,
            'body' => 'Some other reply here',
        ]);
        self::assertCount(1, $user->fresh()->notifications);
    }

    /** @test */
    function a_user_can_mark_their_notification_as_read(): void
    {
        $user = $this->signIn();

        $thread = create(Thread::class)->subscribe($user);

        $thread->addReply([
            'user_id' => create(User::class)->id,
            'body' => 'Some reply here',
        ]);
        self::assertCount(1, $user->unreadNotifications, "User does not have a notification");

        $notificationId = auth()->user()->unreadNotifications->first()->id;

        $this->withoutExceptionHandling();
        $this->delete("/profiles/{$user->name}/notifications/{$notificationId}/");

        self::assertCount(0, $user->fresh()->unreadNotifications);
    }

    /** @test */
    function a_user_can_fetch_their_unread_notifications(): void
    {
        $user = $this->signIn();

        $thread = create(Thread::class)->subscribe($user);

        $thread->addReply([
            'user_id' => create(User::class)->id,
            'body' => 'Some reply here',
        ]);

        $this->withoutExceptionHandling();
        $response = $this->getJson("/profiles/{$user->name}/notifications/");

        self::assertCount(1, $response->json());

    }
}
