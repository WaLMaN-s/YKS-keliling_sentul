<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class YksCategory extends Model
{
    protected $table = 'yks_categories';
    protected $guarded = ['id'];

    public function packages(): HasMany
    {
        return $this->hasMany(YksPackage::class, 'category_id');
    }
}
