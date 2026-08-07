<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    protected $table = 'appointments';

    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'payment_amount',
        'is_contacted',
        'is_success'
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
