<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'title', 'description', 'location', 'event_date',
        'start_time', 'end_time', 'max_participants', 'category', 'status', 'banner_image', 'created_by'
    ];

    protected $casts = [
        'event_date' => 'date',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function eventParticipants()
    {
        return $this->hasMany(EventParticipant::class);
    }

    public function residents()
    {
        return $this->belongsToMany(Resident::class, 'event_participants');
    }
}
