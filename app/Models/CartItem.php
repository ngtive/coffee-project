<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model {


    protected $fillable = [
        'product_id',
        'quantity',
        'product_attribute_id'
    ];


    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function productAttribute() {
        return $this->belongsTo(ProductAttribute::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

}
