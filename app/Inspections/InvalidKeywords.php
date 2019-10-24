<?php
declare(strict_types=1);

namespace App\Inspections;

class InvalidKeywords implements Inspector
{
    public function inspect(string $string): bool
    {
        $invalidKeywords = [
            'yahoo customer support',
        ];

        foreach($invalidKeywords as $keyword) {
            if(stristr($string, $keyword)) {
                return true;
            }
        }

        return false;
    }
}
