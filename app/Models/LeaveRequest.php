<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveRequest extends Model
{
    protected $table = 'leave_requests';
    protected $fillable = [
        'user_id',
        'leave_type_id',
        'start_date',
        'end_date',
        'status',
        'image',
    ];

    public function users() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function leaveType() : BelongsTo
    {
        return $this->belongsTo(LeaveType::class);
    }
}
