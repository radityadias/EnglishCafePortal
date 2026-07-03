<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveRequest extends Model
{
    protected $table = 'leave_requests';
    protected $fillable = [
        'user_id',
        'type',
        'reason',
        'leave_type_id',
        'start_date',
        'end_date',
        'status',
        'image',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function leaveType() : BelongsTo
    {
        return $this->belongsTo(LeaveType::class);
    }
}
