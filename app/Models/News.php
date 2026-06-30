<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'news_category_id',
        'title',
        'slug',
        'thumbnail',
        'content',
        'status',
        'published_at',
        'views'
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'status' => 'string'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(NewsCategory::class, 'news_category_id');
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($news) {
            if (empty($news->slug)) {
                $news->slug = static::generateUniqueSlug($news->title);
            }
        });
    }

    public static function generateUniqueSlug(string $title, int $excludeId = 0): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;

        while (static::where('slug', $slug)->where('id', '!=', $excludeId)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        return $slug;
    }
}
