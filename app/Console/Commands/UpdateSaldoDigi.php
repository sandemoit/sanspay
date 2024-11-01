<?php

namespace App\Console\Commands;

use App\Helpers\DigiFlazz;
use App\Models\Provider;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateSaldoDigi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:saldodigi';

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
        $provider = Provider::where('code', 'DigiFlazz')->first();

        $username = $provider->username;
        $proApiKey = $provider->product_api_key;

        $DigiFlazz = DigiFlazz::makeRequest('/cek-saldo', [
            'cmd' => 'deposit',
            'username' => $username,
            'sign' => DigiFlazz::generateSignature($username, $proApiKey, 'depo')
        ]);

        if (isset($DigiFlazz['data']['deposit'])) {
            $saldo = $DigiFlazz['data']['deposit'];
            $provider->update(['saldo' => $saldo]);
            return print('Saldo DigiFlazz: ' . $saldo);
        } else {
            Log::error('Failed to retrieve balance: ' . ($DigiFlazz['message'] ?? 'Unknown error'));
        }
    }
}
