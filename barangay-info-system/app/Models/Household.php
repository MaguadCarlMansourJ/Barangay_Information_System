<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Household extends Model
{
    use HasFactory;

    protected $fillable = ['purok_id', 'house_number', 'address', 'member_count'];

    public function purok()
    {
        return $this->belongsTo(Purok::class);
    }

    public function residents()
    {
        return $this->hasMany(Resident::class);
    }
}

