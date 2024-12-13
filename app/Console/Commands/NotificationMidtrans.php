<?php

namespace App\Console\Commands;

use App\Http\Controllers\MidtransController;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class NotificationMidtrans extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'midtrans:callback';

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
        //
    }
}
