<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Str;

class Accommodation extends Model
{
    use HasFactory;

    public function photos(): MorphMany
    {
        return $this->morphMany(GalleryPhoto::class, 'imageable')->orderBy('order');
    }

    protected $fillable = [
        'name',
        'slug',
        'type',
        'description',
        'address',
        'phone',
        'price_range',
        'cover_image',
        'latitude',
        'longitude',
        'status'
    ];

    protected $casts = [
        'status' => 'string',
        'type' => 'string',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($accommodation) {
            if (empty($accommodation->slug)) {
                $accommodation->slug = static::generateUniqueSlug($accommodation->name);
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
