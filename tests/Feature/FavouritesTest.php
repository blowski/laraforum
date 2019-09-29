<?php
declare(strict_types=1);

namespace Tests\Feature;

use App\Reply;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FavouritesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function guests_cannot_favourite_anything(): void
    {
        $this->withoutExceptionHandling();
        $this->expectException(AuthenticationException::class);

        $this->post("/replies/1/favourites/");
    }

    /** @test */
    function an_authenticated_user_can_favourite_any_reply(): void
    {
        $this->signIn();

        $reply = create(Reply::class);
        $this->post("/replies/{$reply->id}/favourites/");
        $this->assertCount(1, $reply->favourites);
    }

    /** @test */
    function an_authenticated_user_can_only_favourite_a_reply_once(): void
    {
        $this->withoutExceptionHandling();
        $this->signIn();

        $reply = create(Reply::class);

        try {
            $this->post("/replies/{$reply->id}/favourites/");
            $this->post("/replies/{$reply->id}/favourites/");
            $this->assertCount(1, $reply->favourites);
        } catch(QueryException $ex) {
            $this->fail("Did not expect to insert the same record twice");
        }

    }


}
