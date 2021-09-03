<?php

namespace Dyrynda\Defibrillator\Tests\Fixtures;

class IntrabeatArtisan extends Artisan
{
    public function interval(): int
    {
        return 30;
    }
}
