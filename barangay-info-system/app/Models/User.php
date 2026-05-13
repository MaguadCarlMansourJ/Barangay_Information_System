<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'is_active', 'last_login_at', 'profile_photo', 'resident_id'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_login_at' => 'datetime',
    ];

    public function canAccessModule($module)
    {
        $moduleAccess = [
            'Captain' => ['all'],
            'Secretary' => ['Resident Management', 'Document Request', 'Payment Management', 'Event Management', 'Blotter Management'],
            'Treasurer' => ['Payment Management', 'Reports & Analytics'],
            'Staff' => ['Resident Management', 'Document Request', 'Event Management', 'Blotter Management'],
            'Resident' => ['Document Request', 'Event Management']
        ];
        
        return in_array($module, $moduleAccess[$this->role]) || $this->role === 'Captain';
    }

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }
}
