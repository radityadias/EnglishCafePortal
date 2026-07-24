<?php

namespace App\Models;

use App\Enums\LeaveType;
use Illuminate\Database\Eloquent\Model;
use App\Enums\ConfirmationStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveRequest extends Model
{
    protected $table = 'leave_requests';
    protected $fillable = [
        'user_id',
        'type',
        'reason',
        'start_date',
        'end_date',
        'status',
        'image_path',
    ];

    protected function casts(): array
    {
        return [
            'status' => ConfirmationStatus::class,
            'type' => LeaveType::class
        ];
    }
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
