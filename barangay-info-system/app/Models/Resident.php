<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resident extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'household_id', 'first_name', 'middle_name', 'last_name', 'suffix',
        'birthdate', 'place_of_birth', 'citizenship', 'religion', 'educational_attainment',
        'gender', 'civil_status', 'relationship_to_household_head', 'date_of_residency',
        'is_registered_voter', 'voter_precinct_number', 'occupation', 'contact_number',
        'email', 'philhealth_id', 'is_senior_citizen', 'is_pwd', 'pwd_id_number',
        'is_solo_parent', 'solo_parent_id_number', 'is_4ps_beneficiary',
        'is_indigenous_person', 'is_active'
    ];

    protected $casts = [
        'birthdate' => 'date',
        'date_of_residency' => 'date',
        'is_registered_voter' => 'boolean',
        'is_senior_citizen' => 'boolean',
        'is_pwd' => 'boolean',
        'is_solo_parent' => 'boolean',
        'is_4ps_beneficiary' => 'boolean',
        'is_indigenous_person' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function household()
    {
        return $this->belongsTo(Household::class);
    }

    public function documentRequests()
    {
        return $this->hasMany(DocumentRequest::class);
    }

    public function eventParticipants()
    {
        return $this->hasMany(EventParticipant::class);
    }

    public function blotterParties()
    {
        return $this->hasMany(BlotterParty::class);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function getFullNameAttribute()
    {
        return collect([$this->first_name, $this->middle_name, $this->last_name, $this->suffix])
            ->filter()
            ->implode(' ');
    }

    public function getAgeAttribute()
    {
        return $this->birthdate->age;
    }
}
