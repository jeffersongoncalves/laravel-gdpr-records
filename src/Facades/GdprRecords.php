<?php

namespace JeffersonGoncalves\GdprRecords\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \JeffersonGoncalves\GdprRecords\GdprRecords
 */
class GdprRecords extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'laravel-gdpr-records';
    }
}
