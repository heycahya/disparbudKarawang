<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TourismSubmission extends Model
{
    use HasFactory;

    protected static function booted()
    {
        static::addGlobalScope('user_limit', function ($builder) {
            if (auth()->check() && auth()->user()->role === 'public') {
                $builder->where('user_id', auth()->id());
            }
        });
    }
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
        'converted_destination_id',
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'reviewed_at' => 'datetime',
        'status' => 'string',
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
