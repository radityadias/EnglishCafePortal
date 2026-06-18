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
        'phone',
        'address',
        'gender',
        'bank_number',
        'division_id',
        'employment_status',
        'total_hours',
        'cv_path',
        'start_date',
        'end_date',
    ];

//    public function casts() : array
//    {
//        return [
//            'cv_path' => 'array'
//        ];
//    }

    public function division() : BelongsTo
    {
        return $this->belongsTo(Division::class, 'division_id');
    }
}
