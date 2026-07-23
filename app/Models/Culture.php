<?php

namespace App\Models;

use App\Services\CloudinaryService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Str;

class Culture extends Model
{
    use HasFactory;

    public function photos(): MorphMany
    {
        return $this->morphMany(GalleryPhoto::class, 'imageable')->orderBy('order');
    }

    protected $fillable = [
        'name',
        'slug',
        'category',
        'description',
        'cover_image',
        'status',
        'views'
    ];

    protected $casts = [
        'status' => 'string',
        'category' => 'string',
        'views' => 'integer'
    ];

    /**
     * Get a safe image URL, falling back to Cloudinary sample if null/empty.
     */
    public function getCoverImageUrlAttribute(): string
    {
        if (!empty($this->cover_image)) {
            return $this->cover_image;
        }
        return CloudinaryService::getSampleUrl('culture');
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($culture) {
            if (empty($culture->slug)) {
                $culture->slug = static::generateUniqueSlug($culture->name);
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
