<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductGallery extends Model {

    protected $fillable = [
        'file',
        'product_id'
    ];

    public function product() {
        $this->belongsTo(Product::class, 'product_id');
    }
}
