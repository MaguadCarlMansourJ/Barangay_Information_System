<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FailedJob extends Model
{
    // Kept for compatibility, but Failed Jobs admin UI/routes were removed.
    protected $table = 'failed_jobs';
}


