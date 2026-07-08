<?php

namespace App\Models;

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
            'start_date' => 'date',
            'end_date' => 'date',
            'status' => ConfirmationStatus::class,
        ];
    }
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
