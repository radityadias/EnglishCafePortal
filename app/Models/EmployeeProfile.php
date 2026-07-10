<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeProfile extends Model
{
    protected $table = 'employee_profiles';
    protected $fillable = [
        'nickname',
        'phone_backup',
        'birth_date',
        'birth_place',
        'address',
        'bank_number',
        'bank_name',
        'bank_account_name',
        'work_time_start',
        'work_time_end',
        'cv_path',
        'ktp_path',
        'other_path',
        'user_id',
        'division_id',
        'branch_id',
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function division() : BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    public function branch() : BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function workingTime() : BelongsTo
    {
        return $this->belongsTo(WorkingTime::class);
    }
}
