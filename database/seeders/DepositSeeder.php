<?php

namespace Database\Seeders;

use App\Models\DepositPayment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepositSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DepositPayment::insert([
            ['code' => '', 'name' => '', 'type' => ''],
            ['code' => 'cimb_va', 'name' => 'Virtual Account Bank CIMB', 'type' => 'va'],
            ['code' => 'bni_va', 'name' => 'Virtual Account Bank BNI', 'type' => 'va'],
            ['code' => 'bri_va', 'name' => 'Virtual Account Bank BRI', 'type' => 'va'],
            ['code' => 'mandiri_va', 'name' => 'Virtual Account Bank Mandiri', 'type' => 'va'],
            ['code' => 'permata_va', 'name' => 'Virtual Account Bank Permata', 'type' => 'va'],
            ['code' => 'gopay', 'name' => 'GoPay & Qris', 'type' => 'gopay'],
            ['code' => 'shopeepay', 'name' => 'Shopeepay & Qris', 'type' => 'shopeepay'],
        ]);
    }
}
