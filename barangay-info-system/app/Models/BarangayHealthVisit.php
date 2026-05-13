<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class BarangayHealthVisit extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'barangay_health_visits';

    protected $fillable = [
        'resident_id',
        'visit_number',
        'visit_date',
        'visit_time',
        'service_type',
        'complaints',
        'diagnosis',
        'treatment',
        'is_urgent',
        'attended_by',
        'status',
    ];

    protected $casts = [
        'visit_date' => 'date',
        'is_urgent' => 'boolean',
    ];

    public static function serviceTypes(): array
    {
        // Philippine standard-ish categories for barangay health use
        return [
            'Consultation' => 'Consultation',
            'Pre-natal Check-up' => 'Pre-natal Check-up',
            'Post-natal Check-up' => 'Post-natal Check-up',
            'Immunization' => 'Immunization',
            'Medical Certificate' => 'Medical Certificate',
            'Family Planning' => 'Family Planning',
            'Minor Treatment' => 'Minor Treatment',
            'Others' => 'Others',
        ];
    }

    public static function statuses(): array
    {
        return [
            'Scheduled' => 'Scheduled',
            'Done' => 'Done',
            'Cancelled' => 'Cancelled',
        ];
    }

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }

    public function attendedByUser()
    {
        return $this->belongsTo(User::class, 'attended_by');
    }
}

