<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Internship extends Model
{
    protected $table = 'internships';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'gender',
        'division_id',
        'branch_id',
        'total_hours',
        'cv_path',
        'other_path',
    ];
}
