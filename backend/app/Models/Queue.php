<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Queue extends Model
{
    use HasFactory;

    protected $fillable = [
        'sign_in_id',
        'queue_number',
        'status',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * 关联签到记录
     */
    public function signIn(): BelongsTo
    {
        return $this->belongsTo(SignIn::class, 'sign_in_id');
    }
}

