<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SignIn extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'company',
        'position',
        'queue_number',
        'signed_at',
    ];

    protected $casts = [
        'signed_at' => 'datetime',
    ];

    /**
     * 关联排队记录
     */
    public function queue(): HasOne
    {
        return $this->hasOne(Queue::class, 'sign_in_id');
    }
}

