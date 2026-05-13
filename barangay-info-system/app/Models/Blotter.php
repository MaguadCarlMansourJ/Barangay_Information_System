<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blotter extends Model
{
    protected $fillable = [
        'blotter_number', 'type', 'description', 'incident_date',
        'incident_time', 'incident_location', 'status', 'resolution',
        'is_archived', 'reported_by', 'resolved_by', 'resolved_date'
    ];

    protected $casts = [
        'incident_date' => 'date',
        'resolved_date' => 'date',
        'is_archived' => 'boolean',
    ];

    public function reportedBy()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function resolvedBy()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    public function blotterParties()
    {
        return $this->hasMany(BlotterParty::class);
    }

    public function residents()
    {
        return $this->belongsToMany(Resident::class, 'blotter_parties');
    }
}

