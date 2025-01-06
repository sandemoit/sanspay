<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HistoryOrderController extends Controller
{
    public function historyTransaksi()
    {
        $title = 'History Transaksi';
        return view('users.orders.history', compact('title'));
    }
}
