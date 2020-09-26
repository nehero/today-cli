<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use LaravelZero\Framework\Commands\Command;

class ViewCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'view';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'View your notes for the day';

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
        $items = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => "Bearer {$user->token}"
        ])->get("http://localhost:8000/api/items")->json();
        $this->info($items['item_list']['name']);
        if (empty($items['items'])) {
            $this->info('No items');
        }
        foreach ($items['items'] as $item) {
            $this->info("- {$item['body']}");
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
