<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Division extends Model
{
    protected $table = 'divisions';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
    ];
    public $timestamps = false;
    public function Employees() : HasMany
    {
        return $this->hasMany(Employee::class);
    }
}
