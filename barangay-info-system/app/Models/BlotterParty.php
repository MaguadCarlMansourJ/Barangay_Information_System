<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlotterParty extends Model
{
    protected $fillable = [
        'blotter_id', 'resident_id', 'party_type', 'statement'
    ];

    public function blotter()
    {
        return $this->belongsTo(Blotter::class);
    }

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }
}

