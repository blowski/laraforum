<?php

namespace Tests\Feature;

use App\Thread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ThreadsTest extends TestCase
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
        $response = $this->get('/threads/'.$this->thread->id);

        $response->assertStatus(200);
        $response->assertSee($this->thread->title);
    }




}
