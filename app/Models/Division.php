<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Division extends Model
{
    protected $table = 'divisions';
    protected $fillable = [
        'name',
    ];
    public $timestamps = false;

    public function internshipsProfiles() : HasMany
    {
        return $this->hasMany(InternshipProfile::class);
    }

    public function employeesProfiles() : HasMany
    {
        return $this->hasMany(EmployeeProfile::class);
    }

}
