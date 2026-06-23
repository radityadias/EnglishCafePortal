<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LeaveType extends Model
{
    protected $table = 'leave_types';
    protected $fillable = [
        'name',
        'allowed_days'
    ];

    public $timestamps = false;

    public function leaveRequests() : HasMany {
        return $this->hasMany(LeaveRequest::class);
    }
}
