<?php

namespace Dyrynda\Defibrillator;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class DefibrillatorServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-defibrillator')
            ->hasConfigFile();
    }
}
