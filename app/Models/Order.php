<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model {

    protected $fillable = [
        'courier_price',
        'total_paid',
        'address_id',
        'discounts',
        'tax',
        'description',
        'total_price',
        'user_id',
        'printed_at',
        'paid_at',
        'sending_at',
    ];

    protected $casts = [
        'printed_at' => 'datetime',
        'sending_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    protected $appends = [
        'status'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function address() {
        return $this->belongsTo(Address::class);
    }

    public function items() {
        return $this->hasMany(OrderItem::class);
    }


    protected function status(): \Illuminate\Database\Eloquent\Casts\Attribute {
        return \Illuminate\Database\Eloquent\Casts\Attribute::get(function () {
            if (!$this->paid_at) {
                return 'پرداخت نشده';
            }
            if (!$this->printed_at) {
                return 'در انتظار برسی';
            }
            if (!$this->sending_at && $this->printed_at) {
                return 'برسی شده';
            }
            if ($this->sending_at) {
                return 'درحال ارسال';
            }
            return 'ناشناخته';
        });
    }

}
