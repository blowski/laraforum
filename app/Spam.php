<?php
declare(strict_types=1);

namespace App;

class Spam
{
    public function detect(string $body): bool
    {
        return $this->detectInvalidKeywords($body);
    }

    private function detectInvalidKeywords(string $body): bool
    {
        $invalidKeywords = [
            'yahoo customer support',
        ];

        foreach($invalidKeywords as $keyword) {
            if(stristr($body, $keyword)) {
                return true;
            }
        }

        return false;
    }


}
