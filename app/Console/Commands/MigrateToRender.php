<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Exception;

class MigrateToRender extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:to-render {--migrate : Run migrations on render before copying}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Copy data from local MySQL (connection "mysql") to Render Postgres (connection "render")';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting data copy to Render...');

        if ($this->option('migrate')) {
            $this->info('Running migrations on Render (connection: render)...');
            $this->call('migrate', ['--database' => 'render', '--force' => true]);
        }

        $tables = [
            'branches',
            'buses',
            'routes',
            'seats',
            'users',
            'bookings',
        ];

        foreach ($tables as $table) {
            $this->info("Copying table: {$table}");

            try {
                DB::connection('mysql')->table($table)
                    ->orderBy('id')
                    ->chunk(500, function ($rows) use ($table) {
                        $insert = [];
                        foreach ($rows as $row) {
                            $insert[] = (array) $row;
                        }

                        if (!empty($insert)) {
                            DB::connection('render')->table($table)->insert($insert);
                        }
                    });

                // Reset the sequence on PostgreSQL to the max id
                DB::connection('render')->select(
                    "SELECT pg_catalog.setval(pg_get_serial_sequence('{$table}','id'), COALESCE((SELECT MAX(id) FROM {$table}),1), true);"
                );

                $this->info("Finished copying {$table}");
            } catch (Exception $e) {
                $this->error("Error copying {$table}: " . $e->getMessage());
                return 1;
            }
        }

        $this->info('Data copy complete.');
        return 0;
    }
}
