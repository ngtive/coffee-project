<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VerificationCode extends Model {

    protected $fillable = [
        'phone_number',
        'code',
        'expire_at'
    ];

    protected $appends = [
        'is_expired'
    ];

    protected $casts = [
        'expire_at' => 'datetime'
    ];

    public function isExpired(): \Illuminate\Database\Eloquent\Casts\Attribute {
        return \Illuminate\Database\Eloquent\Casts\Attribute::get(function () {
            return $this->expire_at->isPast();
        });
    }
}
