<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EventBroadcastRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'organization',
        'event_name',
        'event_date',
        'event_location',
        'description',
        'attachment',
        'status',
        'admin_note',
        'reviewed_by',
        'reviewed_at',
        'converted_news_id',
    ];

    protected $casts = [
        'event_date' => 'date',
        'reviewed_at' => 'datetime',
        'status' => 'string',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function convertedNews(): BelongsTo
    {
        return $this->belongsTo(News::class, 'converted_news_id');
    }
}
