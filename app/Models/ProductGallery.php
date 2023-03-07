<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductGallery extends Model {

    protected $fillable = [
        'file',
        'product_id'
    ];

    protected $appends = [
        'thumbs'
    ];

    public function product() {
        $this->belongsTo(Product::class, 'product_id');
    }

    protected function file(): \Illuminate\Database\Eloquent\Casts\Attribute {
        return new \Illuminate\Database\Eloquent\Casts\Attribute(
            get: function ($file) {
                return \Storage::disk('public')->url('products/gallery/org/' . $file);
            }
        );
    }

    protected function thumbs(): \Illuminate\Database\Eloquent\Casts\Attribute {
        return new \Illuminate\Database\Eloquent\Casts\Attribute(
            get: function () {
                $fileHash = $this->original['file'];
                $thumbs = [];
                for ($i = 100; $i <= 800; $i += 100) {
                    $thumbs[$i] = \Storage::disk('public')->url('products/gallery/' . $i . '/' . $fileHash);
                }
                return $thumbs;

            }
        );
    }
}
