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

    /**
     * Dapatkan alasan penolakan bukti pembayaran terakhir (jika ada).
     */
    public function getRejectionReasonAttribute(): ?string
    {
        if (empty($this->notes)) {
            return null;
        }

        if (preg_match_all('/\[Ditolak Admin:\s*(.*?)\]/s', $this->notes, $matches)) {
            $lastIndex = count($matches[1]) - 1;
            return trim($matches[1][$lastIndex]);
        }

        return null;
    }

    /**
     * Cek apakah terdapat penolakan aktif yang perlu ditindaklanjuti pelanggan.
     */
    public function getHasActiveRejectionAttribute(): bool
    {
        return $this->payment_status === 'Menunggu Pembayaran' && !empty($this->rejection_reason);
    }

    /**
     * Cek apakah user berhak mengubah jadwal (minimal H-2 dan status bukan Dibatalkan).
     */
    public function getCanRescheduleAttribute(): bool
    {
        if ($this->payment_status === 'Dibatalkan') {
            return false;
        }

        if (empty($this->trip_date)) {
            return false;
        }

        $tripCarbon = \Carbon\Carbon::parse($this->trip_date)->startOfDay();
        $daysDiff = \Carbon\Carbon::today()->diffInDays($tripCarbon, false);

        return $daysDiff >= 2;
    }

    public function getDaysUntilTripAttribute(): int
    {
        if (empty($this->trip_date)) {
            return 0;
        }

        $tripCarbon = \Carbon\Carbon::parse($this->trip_date)->startOfDay();
        return (int) \Carbon\Carbon::today()->diffInDays($tripCarbon, false);
    }

    /**
     * Resolusi URL Bukti Pembayaran yang fleksibel untuk segala format path.
     */
    public static function resolveProofUrl(?string $proof): ?string
    {
        if (empty($proof)) {
            return null;
        }

        if (str_starts_with($proof, 'http://') || str_starts_with($proof, 'https://')) {
            return $proof;
        }

        // Jika path lengkap di public (misal: uploads/bukti/BUKTI_...jpg)
        if (file_exists(public_path($proof))) {
            return asset($proof);
        }

        // Jika hanya filename dan berada di public/uploads/bukti/
        if (file_exists(public_path('uploads/bukti/' . $proof))) {
            return asset('uploads/bukti/' . $proof);
        }

        // Jika hanya filename dan berada di public/uploads/
        if (file_exists(public_path('uploads/' . $proof))) {
            return asset('uploads/' . $proof);
        }

        // Jika berada di symlink public/storage/
        if (file_exists(public_path('storage/' . $proof))) {
            return asset('storage/' . $proof);
        }

        // Fallback default
        if (str_starts_with($proof, 'uploads/') || str_starts_with($proof, 'storage/')) {
            return asset($proof);
        }

        return asset('uploads/bukti/' . $proof);
    }

    public function getProofUrlAttribute(): ?string
    {
        return self::resolveProofUrl($this->payment_proof);
    }
}
