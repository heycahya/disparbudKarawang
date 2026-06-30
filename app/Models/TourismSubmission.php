<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TourismSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'address',
        'latitude',
        'longitude',
        'photo',
        'status',
        'admin_note',
        'reviewed_by',
        'reviewed_at',
        'converted_destination_id'
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function convertedDestination(): BelongsTo
    {
        return $this->belongsTo(TourismDestination::class, 'converted_destination_id');
    }
}
