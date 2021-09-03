<?php

namespace Dyrynda\Defibrillator\Tests;

use Dyrynda\Defibrillator\DefibrillatorServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            DefibrillatorServiceProvider::class,
        ];
    }
}
