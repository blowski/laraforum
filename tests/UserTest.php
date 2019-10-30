<?php
declare(strict_types=1);

namespace Tests;

use App\Reply;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_user_can_fetch_their_most_recent_reply(): void
    {
        $user = create(User::class);
        $reply = create(Reply::class, ['user_id' => $user->id]);
        $this->assertEquals($reply->id, $user->lastReply->id);
    }
}
