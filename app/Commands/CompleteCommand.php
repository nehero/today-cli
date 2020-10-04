<?php

namespace App\Commands;

use App\Support\TodayApi;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class CompleteCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'complete';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Mark an item in your list as completed';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(TodayApi $todayApi)
    {
        $result = $todayApi->todaysItems();
        $options = collect($result['items'])->filter(function($item) {
            return empty($item['completed_at']);
        })->map(function($item, $i) {
            return $item['body'];
        });
        $selectedItem = $this->menu($result['item_list']['name'], $options->toArray())->open();
        if (!$selectedItem) {
            return;
        }
        $todayApi->completeItem($result['items'][$selectedItem]['id']);
        $this->info("Item completed");
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
