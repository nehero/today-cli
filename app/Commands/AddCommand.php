<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use LaravelZero\Framework\Commands\Command;

class AddCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'add';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Add an item to today';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $user = DB::table('users')->first();
        if (!$user) {
            return $this->error("You are not logged in");
        }
        $body = $this->ask("What would you like to remember?");
        $result = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => "Bearer {$user->token}"
        ])->post("http://localhost:8000/api/items", [
            'body' => $body,
        ])->json();
        if (!$result['item']) {
            return $this->error("Something went wrong");
        }
        $this->info("Item added");
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
