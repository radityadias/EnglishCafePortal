<?php

namespace App\Models;

use App\Enums\AttendanceStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
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

    protected function casts(): array
    {
        return [
            'status' => AttendanceStatus::class,
        ];
    }

    protected function type(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->checkout_time ? 'Checkout' : 'Check In'
        );
    }

    protected function time(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->checkout_time ? $this->checkout_time : $this->checkin_time
        );
    }

    public function user() : BelongsTo {
        return $this->belongsTo(User::class);
    }
}
