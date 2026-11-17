<?php

namespace JeffersonGoncalves\GdprRecords\Tests;

use JeffersonGoncalves\GdprRecords\GdprRecordsServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            GdprRecordsServiceProvider::class,
        ];
    }
}
