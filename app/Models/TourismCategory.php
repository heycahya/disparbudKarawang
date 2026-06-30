<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TourismCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    public function destinations(): HasMany
    {
        return $this->hasMany(TourismDestination::class);
    }
}
