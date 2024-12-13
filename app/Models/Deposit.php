<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Deposit extends Model
{
    use HasFactory;

    protected $table = 'deposit';
    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'member_id', 'id');
    }

    public function depositmethod(): BelongsTo
    {
        return $this->belongsTo(DepositMethod::class, 'payment_method', 'code');
    }

    public function depositpayment(): BelongsTo
    {
        return $this->belongsTo(DepositPayment::class, 'deposit_payment', 'code');
    }
}
