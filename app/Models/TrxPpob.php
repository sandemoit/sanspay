<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrxPpob extends Model
{
    use HasFactory;

    protected $table = 'trx_ppob';
    protected $guarded = [];

    // Relasi ke tabel users
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id')
            ->withDefault();
    }

    // Relasi ke tabel products_ppob
    public function product(): BelongsTo
    {
        return $this->belongsTo(ProductPpob::class, 'code', 'code')
            ->withDefault();
    }

    // Tambahkan accessor untuk memastikan relasi selalu ada nilai default
    protected $with = ['user', 'product']; // Eager load relations
}
