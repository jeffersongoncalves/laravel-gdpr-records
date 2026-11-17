<?php

namespace JeffersonGoncalves\GdprRecords;

use JeffersonGoncalves\Gdpr\Gdpr;
use JeffersonGoncalves\GdprRecords\Models\ProcessingActivity;

class RecordsManager
{
    public function __construct(protected Gdpr $gdpr) {}

    /**
     * The records of processing activities (Art. 30(1)): controller, representative, DPO, and each activity with
     * the declared personal data of the models it processes.
     *
     * @return array{controller: array<string, mixed>, representative: array<string, mixed>, dpo: array<string, mixed>, activities: list<array<string, mixed>>}
     */
    public function export(): array
    {
        $inventory = collect($this->gdpr->inventory())->groupBy('model');

        return [
            'controller' => (array) config('gdpr-records.controller', []),
            'representative' => (array) config('gdpr-records.representative', []),
            'dpo' => $this->dpo(),
            'activities' => ProcessingActivity::query()->orderBy('name')->get()
                ->map(fn (ProcessingActivity $activity): array => [
                    'name' => $activity->name,
                    'purpose' => $activity->purpose,
                    'legal_basis' => $activity->legal_basis?->value,
                    'data_subjects' => $activity->data_subjects ?? [],
                    'data_categories' => $activity->data_categories ?? [],
                    'personal_data' => collect($activity->models ?? [])
                        ->flatMap(fn (string $model) => $inventory->get($model, collect())->map(fn (array $row): array => [
                            'table' => $row['table'],
                            'column' => $row['column'],
                            'category' => $row['category'],
                            'retention' => $row['retention'],
                        ]))
                        ->values()
                        ->all(),
                    'recipients' => $activity->recipients ?? [],
                    'international_transfers' => $activity->international_transfers ?? [],
                    'retention' => $activity->retention,
                    'security_measures' => $activity->security_measures,
                ])
                ->values()
                ->all(),
        ];
    }

    /**
     * @return array{name: string|null, email: string|null, phone: string|null}
     */
    public function dpo(): array
    {
        $dpo = (array) config('gdpr-records.dpo', []);

        return [
            'name' => $dpo['name'] ?? null,
            'email' => $dpo['email'] ?? null,
            'phone' => $dpo['phone'] ?? null,
        ];
    }
}
