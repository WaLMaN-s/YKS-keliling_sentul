<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class YksAddon extends Model
{
    protected $table = 'yks_addons';
    protected $guarded = ['id'];

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }
}
