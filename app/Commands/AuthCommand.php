<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use LaravelZero\Framework\Commands\Command;

class AuthCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'auth';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Login to your account';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $email = $this->ask("Enter your email:");
        $password = $this->secret("Enter your password:");
        $result = Http::withHeaders([
            'Accept' => 'application/json',
        ])->post("http://localhost:8000/api/api-keys", [
            'email' => $email,
            'password' => $password,
        ])->json();
        if (!isset($result['token'])) {
            return $this->error("Invalid credentials");
        }
        DB::table('users')->truncate();
        DB::table('users')->insert([
            'name' => $result['user']['name'],
            'email' => $result['user']['email'],
            'user_id' => $result['user']['id'],
            'token' => $result['token'],
        ]);
        $this->info("Logged in as {$result['user']['name']}");
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
