<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\File;
use LaravelZero\Framework\Commands\Command;

class InstallCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'install';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Installs necessary files for Today to run';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        File::ensureDirectoryExists(config('database.connections.sqlite.directory'));
        if (! file_exists(config('database.connections.sqlite.database'))) {
            $this->info('Performing first time setup...');
            File::put(config('database.connections.sqlite.database'), '');
            $this->callSilent('migrate');
            $this->info('Done.');
        }
    }

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
