<?php

namespace App\Models;

use App\Enums\WorkType;
use App\Enums\Division;
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
        'work_type',
        'work_time_start',
        'work_time_end',
        'cv_path',
        'ktp_path',
        'other_path',
        'user_id',
        'division',
        'branch_id',
    ];

    public function casts(): array
    {
        return [
            'work_time' => WorkType::class,
            'division' => Division::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function isKpiTracked(): bool
    {
        return $this->division !== null
            && in_array($this->division, Division::kpiTracked(), true);
    }
}

