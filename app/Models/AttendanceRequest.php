<?php

namespace App\Models;

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

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    //
}
