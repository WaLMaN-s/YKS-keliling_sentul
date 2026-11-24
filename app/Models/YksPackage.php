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

    public function getIsPrivateTripAttribute(): bool
    {
        return ($this->booking_mode ?? '') === 'whatsapp_only'
            || stripos($this->name ?? '', 'private trip') !== false
            || ($this->category && $this->category->slug === 'private-trip');
    }

    public function getIsMotorPribadiAttribute(): bool
    {
        return stripos($this->name ?? '', 'motor pribadi') !== false
            || ($this->category && $this->category->slug === 'motor-pribadi');
    }

    public function getFormattedPriceAttribute(): string
    {
        if ($this->is_private_trip || (int) $this->price === 0) {
            return 'Harga & tanggal: konsultasi admin';
        }

        return 'Rp' . number_format($this->price, 0, ',', '.');
    }
}
