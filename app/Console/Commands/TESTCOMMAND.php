<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TESTCOMMAND extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Command';

    /**
     * Execute the console command.
     */
    public function handle()
    {
       $this->info("COMMAND IS RUNNING");
       Log::info("SCHEDULE IS RUNNING");
    }
}
