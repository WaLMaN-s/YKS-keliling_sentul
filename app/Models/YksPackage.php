<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class YksPackage extends Model
{
    protected $table = 'yks_packages';
    protected $guarded = ['id'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(YksCategory::class, 'category_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(YksBooking::class, 'package_id');
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }
}
