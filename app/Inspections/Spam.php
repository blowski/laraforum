<?php
declare(strict_types=1);

namespace App\Inspections;

class Spam
{
    /**
     * @var Inspector[]
     */
    private $inspectors;

    public function __construct()
    {
        $this->inspectors = [
            InvalidKeywords::class,
            KeyHeldDown::class,
        ];
    }

    public function detect(string $body): bool
    {
        foreach($this->inspectors as $inspector) {
            if(app($inspector)->inspect($body)) {
                return true;
            }
        }

        return false;
    }



}
