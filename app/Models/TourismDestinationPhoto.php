<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TourismDestinationPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'tourism_destination_id',
        'photo',
        'caption',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    public function destination(): BelongsTo
    {
        return $this->belongsTo(TourismDestination::class, 'tourism_destination_id');
    }
}
