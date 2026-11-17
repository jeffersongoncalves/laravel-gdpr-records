<?php

namespace JeffersonGoncalves\GdprRecords\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;
use JeffersonGoncalves\Gdpr\Attributes\PersonalData;
use JeffersonGoncalves\Gdpr\Concerns\InteractsWithPersonalData;
use JeffersonGoncalves\Gdpr\Contracts\HasPersonalData;

#[PersonalData('email', retention: '5 years')]
#[PersonalData('tax_id')]
class Customer extends Model implements HasPersonalData
{
    use InteractsWithPersonalData;

    protected $table = 'customers';
}
