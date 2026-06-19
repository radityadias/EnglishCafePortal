<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $table = 'attendances';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'arrival_date',
        'time_in',
        'time_out',
        'status',
        'employee_id',
        'internship_id',
        'leave_type_id',
    ];
}
