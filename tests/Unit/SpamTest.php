<?php
declare(strict_types=1);

namespace Tests\Unit;

use App\Inspections\Spam;
use Tests\TestCase;

class SpamTest extends TestCase
{
    /** @test */
    function it_detects_invalid_keywords(): void
    {
        $spam = new Spam;
        $body = 'Yahoo Customer Support';
        self::assertTrue($spam->detect($body));
    }

    /** @test */
    function it_detects_key_being_held_down(): void
    {
        $spam = new Spam;
        $body = 'Hello world aaaaa';
        self::assertTrue($spam->detect($body));
    }
}
