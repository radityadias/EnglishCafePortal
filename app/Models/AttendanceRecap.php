<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceRecap extends Model
{
    protected $table = 'attendance_recaps';
    protected $fillable = [
        'user_id',
        'total_hours',
        'sick_leaves',
        'absent_leaves',
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
