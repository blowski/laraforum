<?php
declare(strict_types=1);

namespace App\Inspections;

class KeyHeldDown implements Inspector
{
    public function inspect(string $string): bool
    {
        return (bool) preg_match('/(.)\\1{4,}/', $string);
    }
}
