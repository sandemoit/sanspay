<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrxGame extends Model
{
    use HasFactory;

    protected $table = 'trx_game';
    protected $guarded = [];

    // Relasi ke tabel users
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke tabel products_ppob
    public function product(): BelongsTo
    {
        return $this->belongsTo(ProductPpob::class, 'code', 'code');
    }
}
