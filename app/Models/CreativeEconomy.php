<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class CreativeEconomy extends Model
{
    use HasFactory;

    protected $table = 'creative_economies';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'owner_name',
        'contact',
        'address',
        'cover_image',
        'status'
    ];

    protected $casts = [
        'status' => 'string'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($ekraf) {
            if (empty($ekraf->slug)) {
                $ekraf->slug = static::generateUniqueSlug($ekraf->name);
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
