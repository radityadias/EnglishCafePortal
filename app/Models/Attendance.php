<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    protected $table = 'attendances';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'status',
        'arrival_date',
        'checkin_date',
        'checkin_time',
        'checkout_time',
    ];

    public function user() : BelongsTo {
        return $this->belongsTo(User::class);
    }
}
