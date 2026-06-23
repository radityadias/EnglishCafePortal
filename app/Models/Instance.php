<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Instance extends Model
{
    protected $table = 'instances';
    public $timestamps = false;

    protected $fillable = [
        'name'
    ];

    public function internshipProfiles() : HasMany
    {
        return $this->hasMany(InternshipProfile::class);
    }
}
