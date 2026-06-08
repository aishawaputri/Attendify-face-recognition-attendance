<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'check_in',
        'check_out',
        'status',
        'photo_snapshot',
    ];

    /**
     * Relasi: Kehadiran ini milik seorang User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}