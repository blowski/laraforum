<?php
declare(strict_types=1);

namespace Tests\Feature;

use App\Activity;
use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfilesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_user_has_a_profile(): void
    {
        $user = create(User::class);

        $this->get("/profiles/{$user->name}")
            ->assertSee($user->name);
    }

    /** @test */
    function profiles_desplay_all_threads_created_by_the_associated_user(): void
    {
        $this->signIn();
        $thread = create(Thread::class, ['user_id' => auth()->id()]);

        $response = $this->get("/profiles/".auth()->user()->name);

        $response
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }
}
