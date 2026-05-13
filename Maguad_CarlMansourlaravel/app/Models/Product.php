<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'category', 'quantity', 'min_stock'];

    public function stockIns()
    {
        return $this->hasMany(StockIn::class);
    }

    public function stockOuts()
    {
        return $this->hasMany(StockOut::class);
    }

    public function damagedGoods()
    {
        return $this->hasMany(DamagedGood::class);
    }

    public function expiredGoods()
    {
        return $this->hasMany(ExpiredGood::class);
    }
}
