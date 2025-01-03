<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferSaldo extends Model
{
    use HasFactory;

    protected $table = 'transfer_saldo';
    protected $guarded = [];
}
