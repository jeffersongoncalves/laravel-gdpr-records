<?php

namespace JeffersonGoncalves\GdprRecords;

use JeffersonGoncalves\GdprRecords\Commands\RopaCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class GdprRecordsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('gdpr-records')
            ->hasConfigFile()
            ->hasViews()
            ->hasTranslations()
            ->hasMigrations(['create_gdpr_processing_activities_table'])
            ->hasCommand(RopaCommand::class);
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(RecordsManager::class);
    }
}
