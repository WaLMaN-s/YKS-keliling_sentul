<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class YksPayment extends Model
{
    protected $table = 'yks_payments';
    protected $guarded = ['id'];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(YksBooking::class, 'booking_id');
    }
}
