<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use phpDocumentor\Reflection\Types\This;

class Employee extends Model
{
    protected $table = 'employees';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'gender',
        'division_id',
        'branch_id',
        'working_time_id',
        'total_hours',
        'cv_path',
        'ktp_path',
        'other_path',
    ];

    public function division() : BelongsTo
    {
        return $this->belongsTo(Division::class, 'division_id');
    }

    public function branch() : BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
