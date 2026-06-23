<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkingTime extends Model
{
    protected $table = 'working_times';
    public $timestamps = false;
    protected $fillable = [
        'name',
        'from',
        'to',
    ];

    public function employeeProfiles() : HasMany
    {
        return $this->hasMany(EmployeeProfile::class);
    }
}
