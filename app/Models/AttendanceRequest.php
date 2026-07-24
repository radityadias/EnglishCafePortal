<?php

namespace App\Models;

use App\Enums\AttendanceStatus;
use App\Enums\ConfirmationStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceRequest extends Model
{
    protected $table = 'attendance_requests';
    protected $fillable = [
        'user_id',
        'type',
        'status',
        'reason',
        'date',
        'requested_time',
        'image_path',
    ];

    protected function casts(): array
    {
        return [
            'status' => ConfirmationStatus::class,
            'type' => AttendanceStatus::class,
        ];
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    //
}
