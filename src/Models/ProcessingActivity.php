<?php

namespace JeffersonGoncalves\GdprRecords\Models;

use Illuminate\Database\Eloquent\Model;
use JeffersonGoncalves\Gdpr\Enums\LegalBasis;

/**
 * One processing activity of the controller (Art. 30(1)).
 *
 * @property string $name
 * @property string $purpose
 * @property LegalBasis|null $legal_basis
 * @property list<string>|null $data_subjects e.g. customers, employees, visitors
 * @property list<string>|null $data_categories free text, beside the declared columns of $models
 * @property list<class-string>|null $models
 * @property list<array{name: string, role?: string, country?: string}>|null $recipients role: processor, controller, public_authority
 * @property list<array{country: string, mechanism?: string}>|null $international_transfers mechanism: adequacy, standard_clauses, binding_corporate_rules, codes_certification, derogation (Chapter V)
 * @property string|null $retention time limits for erasure (Art. 30(1)(f))
 * @property string|null $security_measures Art. 32(1) measures (Art. 30(1)(g))
 */
class ProcessingActivity extends Model
{
    protected $guarded = [];

    public function getTable(): string
    {
        return config('gdpr-records.table', 'gdpr_processing_activities');
    }

    protected function casts(): array
    {
        return [
            'legal_basis' => LegalBasis::class,
            'data_subjects' => 'array',
            'data_categories' => 'array',
            'models' => 'array',
            'recipients' => 'array',
            'international_transfers' => 'array',
        ];
    }
}
