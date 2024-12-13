<?php

namespace App\Console\Commands;

use App\Http\Controllers\Admin\DigiFlazzController;
use Illuminate\Console\Command;

class GetCategory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:category';

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
        $digiflazzController = new DigiFlazzController();
        $priceListResponse = $digiflazzController->getPriceList();
    }
}
