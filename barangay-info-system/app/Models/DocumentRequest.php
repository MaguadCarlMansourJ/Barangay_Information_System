<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentRequest extends Model
{
    protected $fillable = [
        'resident_id', 'document_type_id', 'request_number', 'status',
        'purpose', 'remarks', 'date_requested', 'date_ready', 'date_released',
        'approved_by', 'released_by'
    ];

    protected $casts = [
        'date_requested' => 'date',
        'date_ready' => 'date',
        'date_released' => 'date',
    ];

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }

    public function documentType()
    {
        return $this->belongsTo(DocumentType::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'document_request_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function releasedBy()
    {
        return $this->belongsTo(User::class, 'released_by');
    }
}

