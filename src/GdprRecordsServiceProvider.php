<?php

namespace JeffersonGoncalves\GdprRecords;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class GdprRecordsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-gdpr-records')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigrations();
    }
}
