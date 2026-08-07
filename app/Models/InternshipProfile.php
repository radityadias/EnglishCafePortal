<?php

namespace App\Models;

use App\Enums\WorkType;
use App\Enums\Division;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class
InternshipProfile extends Model
{
    protected $table = 'internship_profiles';
    protected $fillable = [
        'nickname',
        'birth_date',
        'birth_place',
        'address',
        'cv_path',
        'user_id',
        'start_date',
        'end_date',
        'work_type',
        'work_time_start',
        'work_time_end',
        'start_date',
        'end_date',
        'school',
        'division',
        'branch_id',
        'instance_id'
    ];

    public function casts()
    {
        return [
            'work_time' => WorkType::class,
            'division' => Division::class,
        ];
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function branch() : BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function instance() : BelongsTo
    {
        return $this->belongsTo(Instance::class);
    }

    public function isKpiTracked() : bool
    {
        return $this->division !== null
            && in_array($this->division, Division::kpiTracked(), true);
    }


}
