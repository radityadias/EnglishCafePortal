<?php

namespace App\Models;

use App\Enums\ConfirmationStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceRequest extends Model
{
    protected $table = 'attendance_requests';
    protected $fillable = [
        'user_id',
        'status',
        'reason',
        'date',
        'image_path',
    ];

    protected function casts(): array
    {
        return [
            'status' => ConfirmationStatus::class,
        ];
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    //
}
