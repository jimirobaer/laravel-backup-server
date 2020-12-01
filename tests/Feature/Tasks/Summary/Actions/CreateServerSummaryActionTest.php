<?php

namespace Spatie\BackupServer\Tests\Feature\Tasks\Summary\Actions;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Spatie\BackupServer\Models\Source;
use Spatie\BackupServer\Tasks\Summary\Actions\CreateServerSummaryAction;
use Spatie\BackupServer\Tests\TestCase;
use Spatie\Docker\DockerContainer;

class CreateServerSummaryActionTest extends TestCase
{
    private CreateServerSummaryAction $action;

    public function setUp(): void
    {
        parent::setUp();

        $this->action = app(CreateServerSummaryAction::class);

        Carbon::setTestNow(now()->setTime(2, 0));

        Storage::fake('backups');

        $container = DockerContainer::create('spatie/laravel-backup-server-tests')
            ->name('laravel-backup-server-tests')
            ->mapPort(4848, 22)
            ->stopOnDestruct()
            ->start()
            ->addPublicKey($this->publicKeyPath());

        $container->addFiles(__DIR__ . '/stubs/serverContent/testServer', '/src');

        $source = Source::factory()->create([
            'host' => '0.0.0.0',
            'ssh_port' => '4848',
            'ssh_user' => 'root',
            'ssh_private_key_file' => $this->privateKeyPath(),
            'includes' => ['/src'],
            'cron_expression' => '0 2 * * *',
        ]);

        $this->artisan('backup-server:dispatch-backups');
    }

    /** @test */
    public function it_can_make_a_summary_of_backups()
    {
        $summary = $this->action->execute(now()->subWeek(), now());

        $expectedSummary = [
            'from' => now()->subWeek(),
            'to' => now(),
            'successfulBackups' => 1,
            'failedBackups' => 0,
            'healthyDestinations' => 1,
            'unhealthyDestinations' => 0,
            'healthySources' => 0,
            'unhealthySources' => 1,
            'timeSpentRunningBackupsInSeconds' => 0,

            /* too difficult to predict on local filesystem */
            // 'errorsInLog' => 1,
            // 'destinationFreeSpaceInKb' => $summary->destinationFreeSpaceInKb,
            // 'destinationUsedSpaceInKb' => 4,
        ];

        $actualSummary = $summary->toArray();

        $expectedKeys = array_keys($expectedSummary);
        $actualSummary = array_intersect_key($actualSummary, array_flip($expectedKeys));

        $this->assertEquals($expectedSummary, $actualSummary);
    }
}
