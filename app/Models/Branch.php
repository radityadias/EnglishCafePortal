<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Branch extends Model
{
    protected $table = 'branches';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'name',
    ];

    public function hasMany() : HasMany
    {
        return $this->hasMany(Employee::class);
    }
}
