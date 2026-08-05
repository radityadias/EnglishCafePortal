<?php

namespace App\Models;

use App\Enums\ClassType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClassSession extends Model
{
    protected $table = 'class_sessions';

    protected $fillable = [
        'user_id',
        'attendance_id',
        'date',
        'notes',
        'type',
        'start_time',
        'end_time',
    ];

    public function casts(): array
    {
        return [
            'type' => ClassType::class
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function attendance(): BelongsTo
    {
        return $this->belongsTo(Attendance::class);
    }
}
