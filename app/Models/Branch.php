<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Branch extends Model
{
    protected $table = 'branches';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'latitude',
        'longitude',
    ];

    public function internships(): HasMany
    {
        return $this->hasMany(InternshipProfile::class);
    }
}
