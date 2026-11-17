<?php

namespace JeffersonGoncalves\GdprRecords\Commands;

use Illuminate\Console\Command;
use JeffersonGoncalves\GdprRecords\RecordsManager;

class RopaCommand extends Command
{
    protected $signature = 'gdpr:ropa {--json : Print the full records as JSON}';

    protected $description = 'Show the records of processing activities (GDPR Art. 30)';

    public function handle(RecordsManager $records): int
    {
        $ropa = $records->export();

        if ($this->option('json')) {
            $this->line((string) json_encode($ropa, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

            return self::SUCCESS;
        }

        $this->table(
            ['Activity', 'Legal basis', 'Personal data', 'Recipients', 'International', 'Retention'],
            array_map(fn (array $activity): array => [
                $activity['name'],
                $activity['legal_basis'] ?? '-',
                implode(', ', array_map(fn (array $row): string => "{$row['table']}.{$row['column']}", $activity['personal_data'])) ?: '-',
                implode(', ', array_column($activity['recipients'], 'name')) ?: '-',
                implode(', ', array_column($activity['international_transfers'], 'country')) ?: '-',
                $activity['retention'] ?? '-',
            ], $ropa['activities']),
        );

        if ($ropa['dpo']['email'] === null) {
            $this->warn('No data protection officer contact: set gdpr-records.dpo (Art. 37(7)).');
        }

        return self::SUCCESS;
    }
}
