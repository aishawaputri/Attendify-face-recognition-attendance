<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FaceToken extends Model
{
    protected $table = 'face_tokens';
    protected $primaryKey = 'id_face_token';

    protected $fillable = [
        'id_user',
        'descriptor'
    ];

    /**
     * Relasi: Token wajah ini milik satu User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}