<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class YksBooking extends Model
{
    protected $table = 'yks_bookings';
    protected $guarded = ['id'];

    public function package(): BelongsTo
    {
        return $this->belongsTo(YksPackage::class, 'package_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getFormattedTotalAttribute(): string
    {
        return 'Rp ' . number_format($this->total_price, 0, ',', '.');
    }
}
