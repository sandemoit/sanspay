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
            ['code' => 'CIMBVA', 'name' => 'CIMB Niaga Virtual Account', 'type' => 'va'],
            ['code' => 'BNIVA', 'name' => 'BNI Virtual Account', 'type' => 'va'],
            ['code' => 'BRIVA', 'name' => 'BRI Virtual Account', 'type' => 'va'],
            ['code' => 'MANDIRIVA', 'name' => 'Bank Mandiri Virtual Account', 'type' => 'va'],
            ['code' => 'PERMATAVA', 'name' => 'PermataBank Virtual Account', 'type' => 'va'],
            ['code' => 'GOPAY', 'name' => 'GoPay', 'type' => 'emoney'],
            ['code' => 'QRIS', 'name' => 'QRIS', 'type' => 'emoney'],
        ]);
    }
}
