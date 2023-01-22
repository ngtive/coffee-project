<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model {

    protected $fillable = [
        'amount',
        'quantity',
        'product_id',
        'product_attribute_id',
        'order_id',
        'discount',
        'total',
    ];

    public function product() {
        return $this->belongsTo(Product::class);
    }
    public function productAttribute() {
        return $this->belongsTo(ProductAttribute::class);
    }
    public function order() {
        return $this->belongsTo(Order::class);
    }

}
