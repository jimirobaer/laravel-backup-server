<?php

namespace Spatie\BackupServer\Commands;

use Illuminate\Console\Command;
use Spatie\BackupServer\Models\Backup;
use Spatie\BackupServer\Models\Source;
use Spatie\BackupServer\Support\AlignCenterTableStyle;
use Spatie\BackupServer\Support\AlignRightTableStyle;
use Spatie\BackupServer\Support\Helpers\Format;

class ListSourcesCommand extends Command
{
    protected $name = 'backup-server:list';

    protected $description = 'Display a list of the last backup of all sources';

    public function handle()
    {
        $headers = [
            'Source',
            'Id',
            'Healthy',
            '# of Backups',
            'Youngest Backup Age',
            'Youngest Backup Size',
            'Total Backup Size',
            'Used storage',
        ];

        $rows = Source::get()
            ->sortBy(fn (Source $source) => $source->name)
            ->map(fn (Source $source) => $this->convertToRow($source));

        $columnStyles = collect($headers)
            ->map(function (string $header) {
                if (in_array($header, ['Id', 'Youngest Backup Size', '# of Backups', 'Total Backup Size', 'Used storage'])) {
                    return new AlignRightTableStyle();
                }

                if (in_array($header, ['Healthy'])) {
                    return new AlignCenterTableStyle();
                }

                return null;
            })
            ->filter()
            ->all();

        $this->table($headers, $rows, 'default', $columnStyles);
    }

    protected function convertToRow(Source $source)
    {
        $completedBackups = $source->completedBackups;

        if ($source->completedBackups->isEmpty()) {
            return [
                'name' => $source->name,
                'id' => $source->id,
                'healthy' => Format::emoji(false),
                'backup_count' => '0',
                'newest_backup' => 'No backups present',
                'youngest_backup_size' => '/',
                'backup_size' => '/',
                'used_storage' => '/',
            ];
        }

        $youngestBackup = $completedBackups->youngest();

        return [
            'name' => $source->name,
            'id' => $source->id,
            'health' => Format::emoji($source->isHealthy()),
            'backup_count' => $completedBackups->count(),
            'newest_backup' => Format::ageInDays($youngestBackup->created_at),
            'youngest_backup_size' => Format::KbToHumanReadableSize($youngestBackup->size_in_kb),
            'backup_size' => Format::KbToHumanReadableSize($completedBackups->sizeInKb()),
            'real_used_storage' => Format::KbToHumanReadableSize($completedBackups->realSizeInKb()),
        ];
    }

    protected function getFormattedBackupDate(Backup $backup = null)
    {
        return is_null($backup)
            ? 'No backups present'
            : Format::ageInDays($backup->created_at);
    }
}
