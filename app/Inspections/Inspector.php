<?php
declare(strict_types=1);

namespace App\Inspections;

interface Inspector
{
    /**
     * @return bool - true if the string contains spam
     */
    public function inspect(string $string): bool;
}
