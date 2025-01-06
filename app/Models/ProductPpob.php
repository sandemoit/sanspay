<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductPpob extends Model
{
    use HasFactory;

    protected $table = 'products_ppob';
    protected $guarded = [];

    public function order()
    {
        return $this->hasMany(TrxPpob::class, 'code', 'code');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'brand', 'brand');
    }
}
