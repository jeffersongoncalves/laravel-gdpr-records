<?php

namespace JeffersonGoncalves\GdprRecords\Facades;

use Illuminate\Support\Facades\Facade;
use JeffersonGoncalves\GdprRecords\RecordsManager;

/**
 * @method static array{controller: array<string, mixed>, dpo: array<string, mixed>, activities: list<array<string, mixed>>} export()
 * @method static array{name: string|null, email: string|null, phone: string|null} dpo()
 *
 * @see RecordsManager
 */
class GdprRecords extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return RecordsManager::class;
    }
}
