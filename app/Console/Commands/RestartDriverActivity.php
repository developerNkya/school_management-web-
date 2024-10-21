<?php

namespace App\Console\Commands;

use App\Models\Driver;
use Illuminate\Console\Command;

class RestartDriverActivity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'RestartDriverActivity:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        info("Restarting Driver Activities: ". now());
        Driver::query()->update(['activity' => null]);

    }
}
