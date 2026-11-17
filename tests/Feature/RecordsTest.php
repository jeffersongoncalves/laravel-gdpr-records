<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Blade;
use JeffersonGoncalves\Gdpr\Enums\LegalBasis;
use JeffersonGoncalves\GdprRecords\Facades\GdprRecords;
use JeffersonGoncalves\GdprRecords\Models\ProcessingActivity;
use JeffersonGoncalves\GdprRecords\Tests\Fixtures\Customer;

function billing(): ProcessingActivity
{
    return ProcessingActivity::create([
        'name' => 'Billing',
        'purpose' => 'Issue invoices',
        'legal_basis' => LegalBasis::Contract,
        'data_subjects' => ['customers'],
        'models' => [Customer::class],
        'recipients' => [['name' => 'Stripe', 'role' => 'processor', 'country' => 'US']],
        'international_transfers' => [['country' => 'US', 'mechanism' => 'standard_clauses']],
        'retention' => '5 years',
    ]);
}

it('exports the records joined with the declared personal data', function () {
    billing();

    $ropa = GdprRecords::export();

    expect($ropa['controller']['name'])->toBe('Acme Ltd')
        ->and($ropa['dpo']['email'])->toBe('dpo@acme.test')
        ->and($ropa['activities'][0])
        ->name->toBe('Billing')
        ->legal_basis->toBe('contract')
        ->personal_data->toBe([
            ['table' => 'customers', 'column' => 'email', 'category' => 'personal', 'retention' => '5 years'],
            ['table' => 'customers', 'column' => 'tax_id', 'category' => 'personal', 'retention' => null],
        ])
        ->international_transfers->toBe([['country' => 'US', 'mechanism' => 'standard_clauses']]);
});

it('prints the records as a table or JSON', function () {
    billing();

    Artisan::call('gdpr:ropa');
    expect(Artisan::output())->toContain('Billing')->toContain('customers.email')->toContain('Stripe');

    Artisan::call('gdpr:ropa', ['--json' => true]);
    expect(json_decode(Artisan::output(), true)['activities'][0]['name'])->toBe('Billing');
});

it('warns when the DPO contact is missing', function () {
    config()->set('gdpr-records.dpo', []);

    Artisan::call('gdpr:ropa');

    expect(Artisan::output())->toContain('No data protection officer contact');
});

it('renders the privacy policy from the records', function () {
    billing();

    $html = Blade::render('<x-gdpr-records::privacy-policy rights-url="/privacy/requests" />');

    expect($html)->toContain('Acme Ltd is the controller')
        ->toContain('Billing')
        ->toContain('Performance of a contract with you (Art. 6(1)(b))')
        ->toContain('email, tax id')
        ->toContain('Stripe (processor)')
        ->toContain('US (standard contractual clauses)')
        ->toContain('5 years')
        ->toContain('Have your data erased (Art. 17)')
        ->toContain('Our representative in the EU: Rep GmbH')
        ->toContain('lodge a complaint with a supervisory authority')
        ->toContain('href="https://dpa.example"')
        ->not->toContain('withdraw it at any time')
        ->toContain('href="/privacy/requests"')
        ->toContain('mailto:dpo@acme.test');

    app()->setLocale('de');

    expect(Blade::render('<x-gdpr-records::privacy-policy />'))
        ->toContain('Datenschutzerklärung')
        ->toContain('Erfüllung eines Vertrags mit Ihnen (Art. 6 Abs. 1 lit. b)')
        ->toContain('Stripe (Auftragsverarbeiter)');
});

it('mentions consent withdrawal when an activity relies on consent', function () {
    ProcessingActivity::create(['name' => 'Newsletter', 'purpose' => 'Marketing emails', 'legal_basis' => LegalBasis::Consent]);

    expect(Blade::render('<x-gdpr-records::privacy-policy />'))->toContain('withdraw it at any time');
});

it('renders the public DPO contact', function () {
    $html = Blade::render('<x-gdpr-records::dpo class="footer" />');

    expect($html)->toContain('Anna Schmidt')
        ->toContain('mailto:dpo@acme.test')
        ->toContain('tel:+49304000000')
        ->toContain('class="gdpr-dpo footer"')
        ->and(Blade::render('<x-gdpr-records::dpo label="DPO" />'))->toContain('DPO:');

    config()->set('gdpr-records.dpo', []);

    expect(trim(Blade::render('<x-gdpr-records::dpo />')))->toBe('');
});
