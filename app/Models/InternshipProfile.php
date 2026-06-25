<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InternshipProfile extends Model
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
        'division_id',
        'branch_id',
        'instance_id'
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

    public function instance() : BelongsTo
    {
        return $this->belongsTo(Instance::class);
    }


}
