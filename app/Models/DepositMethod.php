<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepositMethod extends Model
{
    use HasFactory;

    protected $table = 'deposit_method';
    protected $guarded = [];

    public function depositmethod()
    {
        return $this->hasMany(Deposit::class, 'code', 'payment_method');
    }
}
