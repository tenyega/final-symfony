<?php

namespace App\Taxes;

use Symfony\Bundle\FrameworkBundle\Test\TestBrowserToken;

class Detector
{

    protected $seuil;

    public function __construct(int $seuil)
    {
        $this->seuil = $seuil;
    }

    public function detect(float $number): bool
    {
        if ($number > $this->seuil) {
            return true;
        } else return false;
    }
}
