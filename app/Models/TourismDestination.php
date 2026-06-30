<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class TourismDestination extends Model
{
    use HasFactory;

    protected $fillable = [
        'tourism_category_id',
        'name',
        'slug',
        'description',
        'address',
        'latitude',
        'longitude',
        'cover_image',
        'status',
        'views'
    ];

    protected $casts = [
        'status' => 'string',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(TourismCategory::class, 'tourism_category_id');
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($destination) {
            if (empty($destination->slug)) {
                $destination->slug = static::generateUniqueSlug($destination->name);
            }
        });
    }

    public static function generateUniqueSlug(string $name, int $excludeId = 0): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 1;

        while (static::where('slug', $slug)->where('id', '!=', $excludeId)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        return $slug;
    }
}
