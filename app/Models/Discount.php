<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Morilog\Jalali\Jalalian;

class Discount extends Model {
    use SoftDeletes;

    protected $fillable = [
        'product_id',
        'discount',
        'expire_at'
    ];

    protected $appends = [
        'is_expired',
        'expire'
    ];

    protected $casts = [
        'expire_at' => 'datetime'
    ];

    public function product() {
        return $this->belongsTo(Product::class);
    }

    protected function expireAt(): \Illuminate\Database\Eloquent\Casts\Attribute {
        return new \Illuminate\Database\Eloquent\Casts\Attribute(
            get: function ($expire_at) {
                return Jalalian::forge($expire_at)->ago();
            }
        );
    }


    protected function expire(): \Illuminate\Database\Eloquent\Casts\Attribute {
        return new \Illuminate\Database\Eloquent\Casts\Attribute(
            get: function () {
                return '' . ceil(now()->floatDiffInHours(
                        (new Carbon($this->original['expire_at'])),
                        false
                    ));
            }
        );
    }

    protected function isExpired(): \Illuminate\Database\Eloquent\Casts\Attribute {
        return \Illuminate\Database\Eloquent\Casts\Attribute::get(function () {
            return (new Carbon($this->original['expire_at']))->isPast();
        });
    }
}
