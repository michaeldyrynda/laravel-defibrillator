<?php

namespace VendorName\Skeleton;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use VendorName\Skeleton\Commands\SkeletonCommand;

class SkeletonServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name(':package_slug')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_:package_slug_table')
            ->hasCommand(SkeletonCommand::class);
    }
}
