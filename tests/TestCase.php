<?php

namespace JeffersonGoncalves\GdprRecords\Tests;

use JeffersonGoncalves\Gdpr\GdprServiceProvider;
use JeffersonGoncalves\GdprRecords\GdprRecordsServiceProvider;
use JeffersonGoncalves\GdprRecords\Tests\Fixtures\Customer;
use JeffersonGoncalves\PestPluginDatabaseMigrations\LoadsPackageMigrations;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    use LoadsPackageMigrations;

    protected function getPackageProviders($app): array
    {
        return [
            GdprServiceProvider::class,
            GdprRecordsServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('gdpr.models', [Customer::class]);
        $app['config']->set('gdpr-records.controller.name', 'Acme Ltd');
        $app['config']->set('gdpr-records.dpo', ['name' => 'Anna Schmidt', 'email' => 'dpo@acme.test', 'phone' => '+49 30 4000000']);
        $app['config']->set('gdpr-records.representative', ['name' => 'Rep GmbH', 'contact' => 'rep@example.eu']);
        $app['config']->set('gdpr-records.supervisory_authority', ['name' => 'Example DPA', 'url' => 'https://dpa.example']);
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->loadPackageMigrations(GdprRecordsServiceProvider::class);
    }
}
