<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpiredGood extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'quantity', 'expiration_date'];

    protected $casts = [
        'expiration_date' => 'date',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
