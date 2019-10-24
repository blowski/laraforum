<?php
declare(strict_types=1);

namespace Tests\Unit;

use App\Spam;
use Tests\TestCase;

class SpamTest extends TestCase
{
    /** @test */
    function it_validates_spam(): void
    {
        $spam = new Spam;
        $body = 'Yahoo Customer Support';
        self::assertTrue($spam->detect($body));
    }
}
